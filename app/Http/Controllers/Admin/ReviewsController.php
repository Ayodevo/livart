<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\Review;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function __construct(
        private Product $product,
        private Review $review
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $products = $this->product->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->pluck('id')->toArray();
            $reviews = $this->review->whereIn('product_id',$products);
            $query_param = ['search' => $request['search']];
        }else{
            $reviews = $this->review;
        }

        $reviews = $reviews->with(['product','customer'])->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.reviews.list',compact('reviews', 'search'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $products=$this->product->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->pluck('id')->toArray();
        $reviews= $this->review->whereIn('product_id',$products)->get();
        return response()->json([
            'view'=>view('admin-views.reviews.partials._table',compact('reviews'))->render()
        ]);
    }
}
