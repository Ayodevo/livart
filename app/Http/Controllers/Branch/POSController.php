<?php

namespace App\Http\Controllers\Branch;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\Category;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function App\CentralLogics\translate;

class POSController extends Controller
{
    public function __construct(
        private Branch $branch,
        private Category $category,
        private Order $order,
        private OrderDetail $order_detail,
        private Product $product,
        private User $user
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): Factory|View|Application
    {
        $category = $request->query('category_id', 0);
        $categories = $this->category->where(['position' => 0])->active()->get();
        $keyword = $request->keyword;
        $key = explode(' ', $keyword);
        $products = $this->product->where('total_stock', '>' , 0)
        ->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
            $query->whereJsonContains('category_ids', [['id' => (string)$request['category_id']]]);
        })
            ->when($keyword, function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->active()->latest()->paginate(Helpers::pagination_limit());

        $branch = $this->branch->find(auth('branch')->id());
        return view('branch-views.pos.index', compact('categories', 'products', 'category', 'keyword', 'branch'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function quick_view(Request $request): JsonResponse
    {
        $product = $this->product->findOrFail($request->product_id);

        return response()->json([
            'success' => 1,
            'view' => view('branch-views.pos._quick-view-data', compact('product'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return float[]|int[]
     */
    public function variant_price(Request $request): array
    {
        $product = $this->product->find($request->id);
        $str = '';
        $quantity = 0;
        $price = 0;
        $stock = 0;

        foreach (json_decode($product->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variations));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variations)[$i]->type == $str) {
                    $price = json_decode($product->variations)[$i]->price - Helpers::discount_calculate($product, $product->price);
                    $stock = json_decode($product->variations)[$i]->stock;
                }
            }
        } else {
            $price = $product->price - Helpers::discount_calculate($product, $product->price);
            $stock = $product->total_stock;
        }

        return array('price' => ($price * $request->quantity), 'stock' => $stock);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_customers(Request $request): JsonResponse
    {
        $key = explode(' ', $request['q']);
        $data = DB::table('users')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            })
            ->limit(8)
            ->latest()
            ->get([DB::raw('id, CONCAT(f_name, " ", l_name, " (", phone ,")") as text')]);

        $data[] = (object)['id' => false, 'text' => translate('walk_in_customer')];

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_tax(Request $request): RedirectResponse
    {
        if ($request->tax < 0) {
            Toastr::error(translate('Tax_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->tax > 100) {
            Toastr::error(translate('Tax_can_not_be_more_than_100_percent'));
            return back();
        }

        $cart = $request->session()->get('cart', collect([]));
        $cart['tax'] = $request->tax;
        $request->session()->put('cart', $cart);
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_discount(Request $request): RedirectResponse
    {
        $subtotal = session()->get('subtotal');
        $total = session()->get('total');

        if ($request->type == 'percent' && $request->discount < 0) {
            Toastr::error(translate('Extra_discount_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->type == 'amount' && $request->discount < 0) {
            Toastr::error(translate('Extra_discount_can_not_be_less_than_0'));
            return back();
        }elseif ($request->type == 'percent' && $request->discount > 100) {
            Toastr::error(translate('Extra_discount_can_not_be_more_than_100_percent'));
            return back();
        } elseif ($request->type == 'amount' && $request->discount > $total) {
            Toastr::error(translate('Extra_discount_can_not_be_more_than_total_price'));
            return back();
        }elseif ($request->type == 'percent' && ($request->session()->get('cart')) == null) {
            Toastr::error(translate('cart_is_empty'));
            return back();
        }elseif ($request->type == 'percent' && $request->discount > 0) {
            $extra_discount = ($subtotal * $request->discount) / 100;
            if($extra_discount >= $total){
                Toastr::error(translate('Extra_discount_can_not_be_more_than_total_price'));
                return back();
            }
        }

        $cart = $request->session()->get('cart', collect([]));
        $cart['extra_discount'] = $request->discount;
        $cart['extra_discount_type'] = $request->type;
        $request->session()->put('cart', $cart);
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request) {
            if ($key == $request->key) {
                $object['quantity'] = $request->quantity;
            }
            return $object;
        });
        $request->session()->put('cart', $cart);
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addToCart(Request $request): JsonResponse
    {
        $product = $this->product->find($request->id);

        $data = array();
        $data['id'] = $product->id;
        $str = '';
        $variations = [];
        $price = 0;

        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        foreach (json_decode($product->choice_options) as $key => $choice) {
            $data[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }
        $data['variations'] = $variations;
        $data['variant'] = $str;
        if ($request->session()->has('cart')) {
            if (count($request->session()->get('cart')) > 0) {
                foreach ($request->session()->get('cart') as $key => $cartItem) {
                    if (is_array($cartItem) && $cartItem['id'] == $request['id'] && $cartItem['variant'] == $str) {
                        return response()->json([
                            'data' => 1
                        ]);
                    }
                }

            }
        }
        //Check the string and decreases quantity for the stock
        if ($str != null) {
            $count = count(json_decode($product->variations));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($product->variations)[$i]->type == $str) {
                    $price = json_decode($product->variations)[$i]->price;
                }
            }
        } else {
            $price = $product->price;
        }

        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['name'] = $product->name;
        $data['discount'] = Helpers::discount_calculate($product, $price);
        $data['image'] = $product->image;

        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->push($data);
        } else {
            $cart = collect([$data]);
            $request->session()->put('cart', $cart);
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function cart_items(): Factory|View|Application
    {
        return view('branch-views.pos._cart');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emptyCart(Request $request): JsonResponse
    {
        session()->forget('cart');
        Session::forget('customer_id');
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function order_list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        $this->order->where(['checked' => 0])->update(['checked' => 1]);

        $query = $this->order->pos()->where(['branch_id' => auth('branch')->id()])->with(['customer', 'branch'])
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            });

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('transaction_reference', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $orders = $query->latest()->paginate(Helpers::getPagination())->appends($query_param);

        return view('branch-views.pos.order.list', compact('orders', 'search', 'start_date', 'end_date'));
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function order_details($id): View|Factory|RedirectResponse|Application
    {
        $order = $this->order->with('details')->where(['id' => $id, 'branch_id' => auth('branch')->id()])->first();
        if (isset($order)) {
            return view('branch-views.order.order-view', compact('order'));
        } else {
            Toastr::info('No more orders!');
            return back();
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function place_order(Request $request): RedirectResponse
    {
        if ($request->session()->has('cart')) {
            if (count($request->session()->get('cart')) < 1) {
                Toastr::error(translate('cart_empty_warning'));
                return back();
            }
        } else {
            Toastr::error(translate('cart_empty_warning'));
            return back();
        }

        $cart = $request->session()->get('cart');
        $total_tax_amount = 0;
        $product_price = 0;
        $order_details = [];

        $order_id = 100000 + $this->order->all()->count() + 1;
        if ($this->order->find($order_id)) {
            $order_id = $this->order->orderBy('id', 'DESC')->first()->id + 1;
        }

        $order = new Order();
        $order->id = $order_id;

        $order->user_id = session()->has('customer_id') ? session('customer_id') : null;
        $order->coupon_discount_title = $request->coupon_discount_title == 0 ? null : 'coupon_discount_title';
        $order->payment_status = 'paid';
        $order->order_status = 'delivered';
        $order->order_type = 'pos';
        $order->coupon_code = $request->coupon_code ?? null;
        $order->payment_method = $request->type;
        $order->transaction_reference = $request->transaction_reference ?? null;
        $order->delivery_charge = 0; //since pos, no distance, no d. charge
        $order->delivery_address_id = $request->delivery_address_id ?? null;
        $order->order_note = null;
        $order->checked = 1;
        $order->created_at = now();
        $order->updated_at = now();

        $total_product_main_price = 0;
        foreach ($cart as $c) {
            if (is_array($c)) {
                $product = $this->product->find($c['id']);

                $p['variations'] = gettype($product['variations']) != 'array' ? json_decode($product['variations'], true) : $product['variations'];

                if(!empty($p['variations'])){
                    $type = $c['variant'];
                    foreach ($p['variations'] as $var) {
                        if ($type == $var['type'] && $var['stock'] < $c['quantity']) {
                            Toastr::error($product->name . ' ' . $var['type'] . ' ' . translate('is out of stock'));
                            return back();
                        }
                    }
                }else{
                    if(($product->total_stock - $c['quantity']) < 0) {
                        Toastr::error($product->name . ' ' . translate('is out of stock'));
                        return back();
                    }
                }

                $discount_on_product = 0;
                $product_subtotal = ($c['price']) * $c['quantity'];
                $discount_on_product += ($c['discount'] * $c['quantity']);

                if(($product->total_stock - $c['quantity']) < 0 && count($cart) > 1) {
                    Toastr::error(translate($product->name . ' is out of stock'));
                    continue;
                } else if(($product->total_stock - $c['quantity']) < 0 && count($cart) <= 1) {
                    Toastr::error(translate($product->name . ' is out of stock'));
                    return back();
                }
                $product->total_stock -= $c['quantity'];
                if ($product) {
                    $price = $c['price'];

                    $product = Helpers::product_data_formatting($product);
                    $or_d = [
                        'product_id' => $c['id'],
                        'product_details' => $product,
                        'quantity' => $c['quantity'],
                        'price' => $price,
                        'tax_amount' => Helpers::tax_calculate($product, $price),
                        'discount_on_product' => Helpers::discount_calculate($product, $price),
                        'discount_type' => 'discount_on_product',
                        'variant' => json_encode($c['variant']),
                        'variation' => json_encode($c['variations']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $total_tax_amount += $or_d['tax_amount'] * $c['quantity'];
                    $product_price += $product_subtotal - $discount_on_product;
                    $total_product_main_price += $product_subtotal;
                    $order_details[] = $or_d;
                }

                $var_store = [];
                if(!empty($product['variations'])){
                    $type = $c['variant'];
                    foreach ($product['variations'] as $var) {
                        if ($type == $var['type']) {
                            $var['stock'] -= $c['quantity'];
                        }
                        $var_store[] = $var;
                    }
                }

                $this->product->where(['id' => $product['id']])->update([
                    'variations' => json_encode($var_store),
                    'total_stock' => $product['total_stock'] - $c['quantity'],
                ]);
            }
        }

        $total_price = $product_price;
        if (isset($cart['extra_discount'])) {
            $extra_discount = $cart['extra_discount_type'] == 'percent' && $cart['extra_discount'] > 0 ? (($total_product_main_price * $cart['extra_discount']) / 100) : $cart['extra_discount'];
            $total_price -= $extra_discount;
        }
        $tax = isset($cart['tax']) ? $cart['tax'] : 0;
        $total_tax_amount = ($tax > 0) ? (($total_price * $tax) / 100) : $total_tax_amount;
        try {
            $order->extra_discount = $extra_discount??0;
            $order->total_tax_amount = $total_tax_amount;
            $order->order_amount = $total_price + $total_tax_amount + $order->delivery_charge;
            $order->coupon_discount_amount = 0.00;
            $order->branch_id = auth('branch')->id();
            $order->save();
            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;
            }
            $this->order_detail->insert($order_details);

            // notification and email
            if ($order->user_id != null){
                $user = User::find($order->user_id);
                $fcm_token = $user?->cm_firebase_token;
                $value = Helpers::order_status_update_message('delivered');
                try {
                    if ($value && $fcm_token != null) {
                        $data = [
                            'title' => 'Order',
                            'description' => $value,
                            'order_id' => $order->id,
                            'image' => '',
                            'type' => 'general',
                        ];
                        Helpers::send_push_notif_to_device($fcm_token, $data);
                    }

                    $emailServices = Helpers::get_business_settings('mail_config');
                    if (isset($emailServices['status']) && $emailServices['status'] == 1 && isset($user)) {
                        Mail::to($user->email)->send(new \App\Mail\OrderPlaced($order->id));
                    }

                } catch (\Exception $e) {
                }
            }

            session()->forget('cart');
            session()->forget('customer_id');
            session(['last_order' => $order->id]);
            Toastr::success(translate('order_placed_successfully'));
            return back();
        } catch (\Exception $e) {
            info($e);
        }
        Toastr::warning(translate('failed_to_place_order'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store_keys(Request $request): JsonResponse
    {
        session()->put($request['key'], $request['value']);
        return response()->json('', 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function generate_invoice($id): JsonResponse
    {
        $order = $this->order->where('id', $id)->first();

        return response()->json([
            'success' => 1,
            'view' => view('branch-views.pos.order.invoice', compact('order'))->render(),
        ]);
    }

    public function customer_store(Request $request): RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        $user_phone = $this->user->where('phone', $request->phone)->first();
        if (isset($user_phone)){
            Toastr::error(translate('The phone is already taken'));
            return back();
        }

        $user_email = $this->user->where('email', $request->email)->first();
        if (isset($user_email)){
            Toastr::error(translate('The email is already taken'));
            return back();
        }

        $this->user->create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt('password'),
        ]);

        Toastr::success(translate('customer added successfully'));
        return back();
    }

    public function export_orders(Request $request): StreamedResponse|string
    {
        $query_param = [];
        $search = $request['search'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        $query = $this->order->pos()->with(['customer'])->where(['branch_id' => auth('branch')->id()])
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            });

        $query_param = ['start_date' => $start_date,'end_date' => $end_date ];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('transaction_reference', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $orders = $query->orderBy('id', 'desc')->get();
        $storage = [];
        foreach($orders as $order){
            $storage[] = [
                'Order Id' => $order['id'],
                'Order Date' => date('d M Y',strtotime($order['created_at'])),
                'Customer' => $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : 'Walking Customer',
                'Branch'=> $order->branch ? $order->branch->name : '',
                'Order Amount' => $order['order_amount'],
                'Order Status' => $order['order_status'],
                'Order Type' => $order['order_type'],
                'Payment Status' => $order['payment_status'],
                'Payment Method' => $order['payment_method'],
            ];
        }
        return (new FastExcel($storage))->download('pos-orders.xlsx');
    }
}