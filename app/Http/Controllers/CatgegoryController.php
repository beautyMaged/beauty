<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Category;
use Illuminate\Support\Str;
use App\CPU\ImageManager;
use Exception;
use Illuminate\Support\Facades\Log;

class CatgegoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin')->except(['store']);
        $this->middleware('auth:admin,seller')->only(['store']);
    }

    public function index()
    {
        $categories = Category::paginate(10); 
        return response()->json(['categories' => $categories], 200);
    }

    public function show(Category $category)
    {
        return response()->json(['category' => $category], 200);
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'priority'=>'required',
            'position'=>'required|in:1,2,3',
            'parent_id'=>'required|exists:categories,id'
        ], [
            'name.required' => 'Category name is required!',
            'image.required' => 'Category image is required!',
            'priority.required' => 'Category priority is required!',
            'position.required' => 'Category position is required!',
            'position.in'=>'Category position must be in 1,2,3',
            'parent_id.required' => 'Category parent_id is required!',
            'parent_id.exists'=>'No parent category found!',
        ]);
        $parent = Category::find($request->parent_id);
        if($parent->position >=3 ){
            return response()->json(['message' => 'only three levels of categoies allowed',405]);
        }

        $category = new Category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->slug = Str::slug($request->name[array_search('en', $request->lang)]);
        $category->icon = ImageManager::upload('category/', 'png', $request->file('image'));
        $category->parent_id = $request->parent_id;
        $category->position = $request->position;
        $category->priority = $request->priority;
        $category->home_status = 1;
        $category->status = 'pending';
        try{
            $category->save();
        }catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message' => 'error when saving category data',
            'error'    =>$e->getMessage()], 500);
        };

        return response()->json(['message' => 'Category created successfully'], 201);

    }

    public function update(Category $category, Request $request){
        $category->status = $request->status;
        try{
            $category->save();
        }catch(Exception $e){

            return response()->json(['message' => 'error when updating category data',
                                    'error'    =>$e->getMessage()], 500);

        };
        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error when deleting category', 'error' => $e->getMessage()], 500);
        }
    }
}
