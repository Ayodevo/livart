<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\ProductLogic;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Model\Translation;
use App;

class ProductController extends Controller
{
    public function __construct(
        private Product $product,
        private Review $review,
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_latest_products(Request $request): JsonResponse
    {
        $products = ProductLogic::get_latest_products($request['sort_by'], $request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_searched_products(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $products = ProductLogic::search_products(
            name:$request['name'],
            price_low:$request['price_low'],
            price_high:$request['price_high'],
            rating: $request['rating'],
            category_id:$request['category_id'],
            sort_by: $request['sort_by'],
            limit: $request['limit'],
            offset: $request['offset']
        );

        if (count($products['products']) == 0) {
            $key = explode(' ', $request['name']);
            $ids = [];

            $product_ids = [];
            if (isset($rating)){
                $product_ids = Product::active()
                    ->with('reviews')
                    ->whereHas('reviews', function ($q) use($rating){
                        $q->havingRaw("AVG(reviews.rating) <= $rating");
                    })
                    ->pluck('id')
                    ->toArray();
            }

            $product_ids_for_category = [];
            if (isset($category_id)){
                foreach (json_decode($category_id, true) as $categoryId) {
                    $product_ids = Product::active()
                        ->where(function ($query) use ($categoryId) {
                            $query->whereJsonContains('category_ids', ['id' => (string)$categoryId]);
                        })
                        ->pluck('id')
                        ->toArray();
                    $product_ids_for_category = array_unique(array_merge($product_ids_for_category, $product_ids));
                }
            }

            if (!empty($key)){
                $ids = Translation::where(['key' => 'name'])
                    ->where(['translationable_type' => 'App\Model\Product'])
                    ->where(function ($query) use ($key) {
                        foreach ($key as $value) {
                            $query->orWhere('value', 'like', "%{$value}%");
                        }
                    })->pluck('translationable_id')->toArray();
            }

            $paginator = $this->product->active()
                ->with(['rating'])
                ->whereIn('id', $ids)
                ->withCount(['wishlist'])
                ->when(isset($request['sort_by']) && $request['sort_by'] == 'new_arrival', function ($query) use ($request) {
                    return $query->where('created_at', '>=', now()->subMonths(3));
                })
                ->when(isset($request['sort_by']) && $request['sort_by'] == 'offer_product', function ($query) use ($request) {
                    return $query->where('discount', '>', 0);
                })
                ->when(($request['price_low'] != null && $request['price_high'] != null), function ($query) use ($request) {
                    return $query->whereBetween('price',[$request['price_low'], $request['price_high']]);
                })
                ->when(isset($request['category_id']), function ($query) use ($product_ids_for_category) {
                    $query->whereIn('id', $product_ids_for_category);
                })
                ->when(isset($request['rating']), function ($query) use ($product_ids) {
                    $query->whereIn('id', $product_ids);
                })
                ->paginate($request['limit'], ['*'], 'page', $request['offset']);

            $products = [
                'total_size' => $paginator->total(),
                'limit' => $request['limit'],
                'offset' => $request['offset'],
                'products' => $paginator->items()
            ];
        }
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);
    }


    public function get_product($id)
    {
        try {
            $product = ProductLogic::get_product($id);
            $product = Helpers::product_data_formatting($product, false);
           // return $product;

            $category_id = $product['category_ids'];
            foreach ($product['category_ids'] as $category_ids){
                if ($category_ids->position == 1){
                    $category_id = $category_ids->id;
                }
            }

            $products = Product::active()->get();
            $product_ids = [];
            foreach ($products as $pro) {
                foreach (json_decode($pro['category_ids'], true) as $category) {
                    if ($category['id'] == $category_id) {
                        $product_ids[] = $pro['id'];
                    }
                }
            }

            $related_products = Product::active()
                ->with('rating')
                ->withCount(['wishlist'])
                ->whereIn('id', $product_ids)
                ->whereNot('id', $id)
                ->inRandomOrder()
                ->limit(10)
                ->get();

            $related_products = Helpers::product_data_formatting($related_products, true);

            $data = [
                'product' => $product,
                'related_products' => $related_products
            ];
            return response()->json($data,  200);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['code' => 'product-001', 'message' => 'Product not found!']
            ], 200);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_related_products($id): JsonResponse
    {
        if ($this->product->find($id)) {
            $products = ProductLogic::get_related_products($id);
            $products = Helpers::product_data_formatting($products, true);
            return response()->json($products, 200);
        }
        return response()->json([
            'errors' => ['code' => 'product-001', 'message' => 'Product not found!']
        ], 404);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_product_reviews($id): JsonResponse
    {
        $reviews = $this->review->with(['customer'])->where(['product_id' => $id])->get();

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $storage[] = $item;
        }

        return response()->json($storage, 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get_product_rating($id): JsonResponse
    {
        try {
            $product = $this->product->find($id);
            $overallRating = ProductLogic::get_overall_rating($product->reviews);
            return response()->json(floatval($overallRating[0]), 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function submit_product_review(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'order_id' => 'required',
            'comment' => 'required',
            'rating' => 'required|numeric|max:5',
        ]);

        $product = $this->product->find($request->product_id);
        if (!isset($product)) {
            $validator->errors()->add('product_id', 'There is no such product');
        }

        $multi_review = $this->review->where(['product_id' => $request->product_id, 'user_id' => $request->user()->id])->first();
        if (isset($multi_review)) {
            $review = $multi_review;
        } else {
            $review = $this->review;
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $image_array = [];
        if (!empty($request->file('attachment'))) {
            foreach ($request->file('attachment') as $image) {
                if ($image != null) {
                    if (!Storage::disk('public')->exists('review')) {
                        Storage::disk('public')->makeDirectory('review');
                    }
                    $image_array[] = Storage::disk('public')->put('review', $image);
                }
            }
        }

        $review->user_id = $request->user()->id;
        $review->product_id = $request->product_id;
        $review->order_id = $request->order_id;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->attachment = json_encode($image_array);
        $review->save();

        return response()->json(['message' => 'successfully review submitted!'], 200);
    }

    /**
     * @return JsonResponse
     */
    public function get_discounted_products(): JsonResponse
    {
        try {
            $products = Helpers::product_data_formatting($this->product->orderBy('id', 'desc')->active()->withCount(['wishlist'])->with(['rating'])->where('discount', '>', 0)->get(), true);
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_new_arrival_products(Request $request): JsonResponse
    {
        $products = ProductLogic::get_new_arrival_products($request['limit'], $request['offset']);
        $products['products'] = Helpers::product_data_formatting($products['products'], true);
        return response()->json($products, 200);
    }
}
