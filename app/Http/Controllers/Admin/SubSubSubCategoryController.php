<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use App\Services\CategoryService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubSubSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = Category::where(['position' => 3])->where(function ($q) use ($key) {
                foreach ($key as $value)
                    $q->orWhere('name', 'like', "%{$value}%");
            });
            $query_param = ['search' => $request['search']];
        } else
            $categories = Category::where(['position' => 3]);
        $categories = $categories->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.sub-sub-sub-category-view', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'required',
            'image' => 'required'
        ], [
            'name.required' => 'Category name is required!',
            'image.required' => 'icon is required!',
            'parent_id.required' => 'Sub Category field is required!',
        ]);

        $category = new Category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        $category->parent_id = $request->parent_id;
        $category->position = 3;
        $category->priority = $request->priority;
        $category->icon = ImageManager::upload('category/', 'png', $request->file('image'));

        $category->save();
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => Category::class,
                        'translationable_id'    => $category->id,
                        'locale'                => $key,
                        'key'                   => 'name'
                    ],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        Toastr::success('Category updated successfully!');
        return back();
    }

    public function edit(Request $request)
    {
        $data = Category::where('id', $request->id)->first();
        return response()->json($data);
    }

    public function delete(Request $request, CategoryService $service)
    {
        $service->delete($request->id);
        return response()->json();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::where('position', 3)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }
}
