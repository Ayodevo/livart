<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct(
        private Category $category,
        private Translation $translation
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    function index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = $this->category->where(['position' => 0])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $categories = $this->category->where(['position' => 0]);
        }
        $categories = $categories->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.category.index', compact('categories', 'search'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    function sub_index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = $this->category->with(['parent'])->where(['position' => 1])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $categories = $this->category->with(['parent'])->where(['position' => 1]);
        }
        $categories = $categories->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.category.sub-index', compact('categories', 'search'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $categories = $this->category->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.category.partials._table', compact('categories'))->render()
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    function sub_sub_index(): View|Factory|Application
    {
        return view('admin-views.category.sub-sub-index');
    }

    /**
     * @return Application|Factory|View
     */
    function sub_category_index(): View|Factory|Application
    {
        return view('admin-views.category.index');
    }

    /**
     * @return Application|Factory|View
     */
    function sub_sub_category_index(): View|Factory|Application
    {
        return view('admin-views.category.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    function store(Request $request): RedirectResponse
    {
        //dd($request->all());
        $request->validate([
            'name' => 'required',
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(\App\CentralLogics\translate('Name is too long!'));
                return back();
            }
        }

        $cat = $this->category->where('name', $request->name)->where('parent_id', $request->parent_id ?? 0)->first();
        if (isset($cat)) {
            Toastr::error(\App\CentralLogics\translate(($request->parent_id == null ? 'Category' : 'Sub-category') . ' already exists!'));
            return back();
        }

        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('category/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        if (!empty($request->file('banner_image'))) {
            $banner_image_name = Helpers::upload('category/banner/', 'png', $request->file('banner_image'));
        } else {
            $banner_image_name = 'def.png';
        }
        //dd($image_name, $banner_image_name);

        $category = $this->category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->image = $image_name;
        $category->banner_image = $banner_image_name;
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->position = $request->position;
        //dd($category);
        $category->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            }
        }
        if (count($data)) {
            $this->translation->insert($data);
        }

        Toastr::success($request->parent_id == 0 ? \App\CentralLogics\translate('Category Added Successfully!') : \App\CentralLogics\translate('Sub Category Added Successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): View|Factory|Application
    {
        $category = $this->category->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.category.edit', compact('category'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        $category->status = $request->status;
        $category->save();
        Toastr::success($category->parent_id == 0 ? \App\CentralLogics\translate('Category status updated!') : \App\CentralLogics\translate('Sub Category status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(\App\CentralLogics\translate('Name is too long!'));
                return back();
            }
        }

        $category = $this->category->find($id);
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->image = $request->has('image') ? Helpers::update('category/', $category->image, 'png', $request->file('image')) : $category->image;
        $category->banner_image = $request->has('banner_image') ? Helpers::update('category/banner/', $category->banner_image, 'png', $request->file('banner_image')) : $category->banner_image;
        $category->save();
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $this->translation->updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }
        Toastr::success($category->parent_id == 0 ? \App\CentralLogics\translate('Category updated successfully!') : \App\CentralLogics\translate('Sub Category updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        if (Storage::disk('public')->exists('category/' . $category['image'])) {
            Storage::disk('public')->delete('category/' . $category['image']);
        }
        if ($category->childes->count() == 0) {
            $category->delete();
            Toastr::success($category->parent_id == 0 ? \App\CentralLogics\translate('Category removed!') : \App\CentralLogics\translate('Sub Category removed!'));
        } else {
            Toastr::warning($category->parent_id == 0 ? \App\CentralLogics\translate('Remove subcategories first!') : \App\CentralLogics\translate('Sub Remove subcategories first!'));
        }
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function featured(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        $category->is_featured = $request->featured;
        $category->save();
        Toastr::success(\App\CentralLogics\translate('Featured status updated!'));
        return back();
    }
}
