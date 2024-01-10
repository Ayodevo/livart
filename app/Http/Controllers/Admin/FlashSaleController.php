<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\FlashSale;
use App\Model\FlashSaleProduct;
use App\Model\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FlashSaleController extends Controller
{
    public function __construct(
        private FlashSale $flash_sale,
        private Product $product,
        private FlashSaleProduct $flash_sale_product,
    )
    {}

    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $flash_sale = $this->flash_sale
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('title', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $flash_sale = $this->flash_sale;
        }
        $flash_sales = $flash_sale->withCount('products')->latest()->paginate(Helpers::getPagination())->appends($query_param);

        return view('admin-views.flash-sale.index', compact('flash_sales', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
           // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'title.required'=>translate('Title is required'),
        ]);

        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('flash-sale/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }

        $flash_sale = $this->flash_sale;
        $flash_sale->title = $request->title;
        $flash_sale->start_date = $request->start_date;
        $flash_sale->end_date = $request->end_date;
        $flash_sale->status = 0;
        $flash_sale->image = $image_name;
        $flash_sale->save();
        Toastr::success(translate('Added successfully!'));
        return back();
    }

    public function status(Request $request)
    {
        $this->flash_sale->where(['status' => 1])->update(['status' => 0]);
        $flash_sale = $this->flash_sale->find($request->id);
        $flash_sale->status = $request->status;
        $flash_sale->save();
        Toastr::success(translate('Status updated!'));
        return back();
    }

    public function edit($id)
    {
        $flash_sale = $this->flash_sale->find($id);
        return view('admin-views.flash-sale.edit', compact('flash_sale'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ],[
            'title.required'=>translate('Title is required'),
        ]);

        $flash_sale = $this->flash_sale->find($id);
        $flash_sale->title = $request->title;
        $flash_sale->start_date = $request->start_date;
        $flash_sale->end_date = $request->end_date;
        $flash_sale->image = $request->has('image') ? Helpers::update('flash-sale/', $flash_sale->image, 'png', $request->file('image')) : $flash_sale->image;
        $flash_sale->save();
        Toastr::success(translate('Updated successfully!'));
        return redirect()->route('admin.flash-sale.index');
    }

    public function delete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $flash_sale = $this->flash_sale->find($request->id);
        if (Storage::disk('public')->exists('flash-sale/' . $flash_sale['image'])) {
            Storage::disk('public')->delete('flash-sale/' . $flash_sale['image']);
        }
        $flash_sale_ids = $this->flash_sale_product->where(['flash_sale_id' => $request->id])->pluck('flash_sale_id');
        $flash_sale->delete();
        $this->flash_sale_product->whereIn('flash_sale_id', $flash_sale_ids)->delete();

        Toastr::success(translate('Flash sale deleted!'));
        return back();
    }

    public function add_product(Request $request, $flash_sale_id)
    {
        $query_param = [];
        $search = $request['search'];

        $flash_sale = $this->flash_sale->where('id', $flash_sale_id)->first();
        $flash_sale_product_ids = $this->flash_sale_product->where('flash_sale_id', $flash_sale_id)->pluck('product_id');

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $flash_sale_products = $this->product
                ->whereIn('id', $flash_sale_product_ids)
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('name', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $flash_sale_products = $this->product
                ->whereIn('id', $flash_sale_product_ids);
        }

        $flash_sale_products = $flash_sale_products->paginate(Helpers::getPagination())->appends($query_param);

        $products = $this->product->active()
            ->whereNotIn('id', $flash_sale_product_ids)
            ->orderBy('id', 'DESC')->get();

        return view('admin-views.flash-sale.add-product', compact('products', 'flash_sale_products','flash_sale_id', 'search'));
    }

    public function add_product_to_session(Request $request, $flash_sale_id, $product_id)
    {
        $product = $this->product->find($product_id);

        $flash_sale_product = $this->flash_sale_product
            ->where(['product_id' => $product_id, 'flash_sale_id' => $flash_sale_id])
            ->first();

        if (isset($flash_sale_product)){
            Toastr::info($product['name']. ' is already exist in this flash sale!');
            return back();
        }

        $selected_product = [
            'flash_sale_id' => $flash_sale_id,
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => json_decode($product['image'], true)[0],
            'price' => $product->price,
            'total_stock' => $product->total_stock,
        ];

        $request->session()->put('selected_product', $selected_product);

        // Retrieve the existing selected products from the session or an empty array if it doesn't exist
        $selectedProducts = $request->session()->get('selected_products', []);

        $productExists = false;
        foreach ($selectedProducts as $key => $existingProduct) {
            if ($existingProduct['product_id'] == $selected_product['product_id'] && $existingProduct['flash_sale_id'] == $selected_product['flash_sale_id']) {
                $productExists = true;
                Toastr::info($existingProduct['name']. ' is already selected!');
                break;
            }
        }

        if (!$productExists) {
            $selectedProducts[] = $selected_product;
        }

        $request->session()->put('selected_products', $selectedProducts);

        Toastr::success(translate('Product added successfully!'));
        return redirect()->back();
    }

    public function delete_product_from_session(Request $request, $flash_sale_id, $product_id)
    {
        $selectedProducts = $request->session()->get('selected_products', []);

        foreach ($selectedProducts as $key => $product) {
            if ($product['flash_sale_id'] == $flash_sale_id && $product['product_id'] == $product_id) {
                unset($selectedProducts[$key]);
            }
        }

        // Re-index the array to remove gaps
        $selectedProducts = array_values($selectedProducts);
        $request->session()->put('selected_products', $selectedProducts);

        Toastr::success(translate('Product deleted successfully!'));
        return redirect()->back();
    }

    public function delete_all_products_from_session(Request $request, $flash_sale_id)
    {
        $selectedProducts = $request->session()->get('selected_products', []);

        foreach ($selectedProducts as $key => $product) {
            if ($product['flash_sale_id'] == $flash_sale_id) {
                unset($selectedProducts[$key]);
            }
        }

        // Re-index the array to remove gaps
        $selectedProducts = array_values($selectedProducts);

        $request->session()->put('selected_products', $selectedProducts);

        Toastr::success(translate('Reset successfully!'));
        return redirect()->back();
    }

    public function flash_sale_product_store(Request $request, $flash_sale_id)
    {
        $selectedProducts = $request->session()->get('selected_products', []);

        foreach ($selectedProducts as $key => $selectedProduct) {
            if ($selectedProduct['flash_sale_id'] == $flash_sale_id) {
                $existingProduct = $this->flash_sale_product
                    ->where(['product_id' => $selectedProduct['product_id'], 'flash_sale_id' => $flash_sale_id])
                    ->first();

                if (!isset($existingProduct)){
                    FlashSaleProduct::create([
                        'product_id' => $selectedProduct['product_id'],
                        'flash_sale_id' => $flash_sale_id,
                    ]);
                }
                unset($selectedProducts[$key]);
            }
        }
        $selectedProducts = array_values($selectedProducts);

        $request->session()->put('selected_products', $selectedProducts);

        Toastr::success('Product added successfully!');
        return back();
    }


    public function delete_flash_product(Request $request, $flash_sale_id, $product_id)
    {
        $this->flash_sale_product->where(['product_id' => $product_id, 'flash_sale_id' => $flash_sale_id])->delete();

        Toastr::success('Product deleted successfully!');
        return back();
    }
}
