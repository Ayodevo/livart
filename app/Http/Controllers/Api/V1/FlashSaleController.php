<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\FlashSale;
use App\Model\FlashSaleProduct;
use App\Model\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function __construct(
        private FlashSale $flash_sale,
        private FlashSaleProduct $flash_sale_product,
        private Product $product
    )
    {}

    public function get_flash_sale(Request $request)
    {
        $sort_by = $request['sort_by'];
        $flash_sale = $this->flash_sale->active()->first();

        if (!isset($flash_sale)){
           // return response()->json(null, 200);
            $products = [
                'total_size' => null,
                'limit' => $request['limit'],
                'offset' => $request['offset'],
                'flash_sale' => $flash_sale,
                'products' => []
            ];
            return response()->json($products, 200);

        }

        $product_ids = $this->flash_sale_product->with(['product'])
            ->whereHas('product',function($q){
                $q->active();
            })
            ->where(['flash_sale_id' => $flash_sale->id])
            ->pluck('product_id')
            ->toArray();

        $paginator = $this->product->with(['rating'])
            ->when(isset($sort_by) && $sort_by == 'price_high_to_low', function ($query){
                return $query->orderBy('price', 'desc');
            })
            ->when(isset($sort_by) && $sort_by == 'price_low_to_high', function ($query){
                return $query->orderBy('price', 'asc');
            })
            ->latest()
            ->whereIn('id', $product_ids)
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $products = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'flash_sale' => $flash_sale,
            'products' => $paginator->items()
        ];

        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);

    }
}
