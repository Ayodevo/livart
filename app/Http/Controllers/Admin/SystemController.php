<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Branch;
use App\Model\Category;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function __construct(
        private Admin $admin,
        private Branch $branch,
        private Category $category,
        private Order $order,
        private OrderDetail $order_detail,
        private Product $product,
        private User $user
    ){}

    /**
     * @param $id
     * @return string
     */
    public function fcm($id): string
    {
        $fcm_token =  $this->admin->find(auth('admin')->id())->fcm_token;
        $data = [
            'title' => 'New auto generate message arrived from admin dashboard',
            'description' => $id,
            'order_id' => '',
            'image' => '',
            'type' => 'general',
        ];
        Helpers::send_push_notif_to_device($fcm_token, $data);

        return "Notification sent to admin";
    }

    /**
     * @return Application|Factory|View
     */
    public function dashboard(): Factory|View|Application
    {
        $top_sell =  $this->order_detail->with(['product'])
            ->whereHas('order', function ($query){
                $query->where('order_status', 'delivered');
            })
            ->select('product_id', DB::raw('SUM(quantity) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $most_rated_products =  $this->product->rightJoin('reviews', 'reviews.product_id', '=', 'products.id')
            ->groupBy('product_id')
            ->select(['product_id',
                DB::raw('AVG(reviews.rating) as ratings_average'),
                DB::raw('count(*) as total')
            ])
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();

        $top_customer = $this->order->with(['customer'])
            ->select('user_id', DB::raw('COUNT(user_id) as count'))
            ->groupBy('user_id')
            ->orderBy("count", 'desc')
            ->take(6)
            ->get();

        $data = self::order_stats_data();

        $data['customer'] = $this->user->count();
        $data['product'] =  $this->product->count();
        $data['order'] = $this->order->count();
        $data['category'] = $this->category->where('parent_id', 0)->count();
        $data['branch'] = $this->branch->count();

        $data['top_sell'] = $top_sell;
        $data['most_rated_products'] = $most_rated_products;
        $data['top_customer'] = $top_customer;

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = \Illuminate\Support\Carbon::now()->endOfYear()->format('Y-m-d');

        /*earning statistics chart*/

        $earning = [];
        $earning_data = $this->order->where([
            'order_status' => 'delivered'
        ])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; $inc++) {
            $earning[$inc] = 0;
            foreach ($earning_data as $match) {
                if ($match['month'] == $inc) {
                    $earning[$inc] = $match['sums'];
                }
            }
        }
        return view('admin-views.dashboard', compact('data', 'earning'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function order_stats(Request $request): JsonResponse
    {
        session()->put('statistics_type', $request['statistics_type']);
        $data = self::order_stats_data();

        return response()->json([
            'view' => view('admin-views.partials._dashboard-order-stats', compact('data'))->render()
        ], 200);
    }

    /**
     * @return array
     */
    public function order_stats_data(): array
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $pending = $this->order->where(['order_status' => 'pending'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $confirmed = $this->order->where(['order_status' => 'confirmed'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $processing = $this->order->where(['order_status' => 'processing'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $out_for_delivery = $this->order->where(['order_status' => 'out_for_delivery'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $delivered = $this->order->where(['order_status' => 'delivered'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $all = $this->order
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', \Illuminate\Support\Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $returned = $this->order->where(['order_status' => 'returned'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $failed = $this->order->where(['order_status' => 'failed'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();
        $canceled = $this->order->where(['order_status' => 'canceled'])
                        ->when($today, function ($query) {
                            return $query->whereDate('created_at', Carbon::today());
                        })
                        ->when($this_month, function ($query) {
                            return $query->whereMonth('created_at', Carbon::now());
                        })
                        ->count();

        $data = [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'processing' => $processing,
            'out_for_delivery' => $out_for_delivery,
            'delivered' => $delivered,
            'all' => $all,
            'returned' => $returned,
            'failed' => $failed,
            'canceled' => $canceled
        ];

        return $data;
    }

    /**
     * @return JsonResponse
     */
    public function restaurant_data(): JsonResponse
    {
        $new_order = DB::table('orders')->where(['checked' => 0])->count();
        return response()->json([
            'success' => 1,
            'data' => ['new_order' => $new_order]
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function settings(): Factory|View|Application
    {
        return view('admin-views.settings');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_update(Request $request): RedirectResponse
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!',
        ]);

        $admin =  $this->admin->find(auth('admin')->id());
        $admin->f_name = $request->f_name;
        $admin->l_name = $request->l_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->image = $request->has('image') ? Helpers::update('admin/', $admin->image, 'png', $request->file('image')) : $admin->image;
        $admin->save();
        Toastr::success(translate('Admin updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settings_password_update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
            'confirm_password' => 'required',
        ]);

        $admin =  $this->admin->find(auth('admin')->id());
        $admin->password = bcrypt($request['password']);
        $admin->save();
        Toastr::success(translate('Admin password updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_earning_statitics(Request $request): JsonResponse
    {
        $dateType = $request->type;

        $earning_data = array();
        if($dateType == 'yearEarn') {
            $number = 12;
            $from = \Illuminate\Support\Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');

            $earning = $this->order->where([
                'order_status' => 'delivered'
            ])->select(
                DB::raw('IFNULL(sum(order_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earning_data[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['month'] == $inc) {
                        $earning_data[$inc] = $match['sums'];
                    }
                }
            }
            $key_range = array("Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

        }elseif($dateType == 'MonthEarn') {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $number = date('d',strtotime($to));
            $key_range = range(1, $number);

            $earning = $this->order->where([
                'order_status' => 'delivered'
            ])->select(
                DB::raw('IFNULL(sum(order_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month', 'day')->get()->toArray();

            for ($inc = 1; $inc <= $number; $inc++) {
                $earning_data[$inc] = 0;
                foreach ($earning as $match) {
                    if ($match['day'] == $inc) {
                        $earning_data[$inc] = $match['sums'];
                    }
                }
            }

        }elseif($dateType == 'WeekEarn') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            Carbon::setWeekEndsAt(Carbon::SATURDAY);

            $from = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
            $to = Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59');
            $date_range = CarbonPeriod::create($from, $to)->toArray();
            $day_range = array();
            foreach($date_range as $date){
                $day_range[] =$date->format('d');
            }
            $day_range = array_flip($day_range);
            $day_range_keys = array_keys($day_range);
            $day_range_values = array_values($day_range);
            $day_range_intKeys = array_map('intval', $day_range_keys);
            $day_range = array_combine($day_range_intKeys, $day_range_values);

            $earning = $this->order->where([
                'order_status' => 'delivered'
            ])->select(
                DB::raw('IFNULL(sum(order_amount),0) as sums'),
                DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
            )->whereBetween('created_at', [$from, $to])->groupby('year', 'month', 'day')->orderBy('created_at', 'ASC')->pluck('sums', 'day')->toArray();

            $earning_data = array();
            foreach($day_range as $day=>$value){
                $day_value = 0;
                $earning_data[$day] = $day_value;
            }

            foreach($earning as $order_day => $order_value){
                if(array_key_exists($order_day, $earning_data)){
                    $earning_data[$order_day] = $order_value;
                }
            }

            $key_range = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        }

        $label = $key_range;
        $earning_data_final = $earning_data;

        $data = array(
            'earning_label' => $label,
            'earning' => array_values($earning_data_final),
        );
        return response()->json($data);
    }

    public function ignore_check_order()
    {
        $this->order->where(['checked' => 0])->update(['checked' => 1]);
        return redirect()->back();
    }
}
