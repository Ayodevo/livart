<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CategoryLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        private Category $category
    ){}

    /**
     * @return JsonResponse
     */
    public function get_categories(): JsonResponse
    {
        try {
            $categories = $this->category->where(['position'=>0,'status'=>1])->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_childes($id): JsonResponse
    {
        try {
            $categories = $this->category->where(['parent_id' => $id,'status'=>1])->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_products($id): JsonResponse
    {
        return response()->json(Helpers::product_data_formatting(CategoryLogic::products($id), true), 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_all_products($id): JsonResponse
    {
        try {
            return response()->json(Helpers::product_data_formatting(CategoryLogic::all_products($id), true), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    /**
     * @return JsonResponse
     */
    public function get_featured_category(): JsonResponse
    {
        $featured_category_list = Category::active()->where(['is_featured' => 1])->get();

        $featured_data=[];

        foreach($featured_category_list as $category){
            $products = Product::whereJsonContains('category_ids', ['id'=>(string)$category->id])->take(15)->get();
            if ($products->count() > 0){

                $featured_data[] = [
                    'category' => $category,
                    'products' =>  Helpers::product_data_formatting($products, true)
                ];
            }
        }

        return response()->json(['featured_data' => $featured_data], 200);
    }
}
