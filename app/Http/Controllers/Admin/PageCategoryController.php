<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PageCategory;
use Exception;
use Illuminate\Http\Request;

class PageCategoryController extends Controller
{
    function __construct(){
        $this->middleware('auth:admin')->only('store', 'update', 'destroy');
    }
    public function index()
    {
        $categories = PageCategory::all();

        if(!empty($categories)){
            
        return response()->json(['categories' => $categories],200);
        }else{
            return response()->json(['message'=>'no categories found'],404);
        }
    }


    // return all categories associated with their orderd pages list
    public function listPages()
    {
        $categories = PageCategory::with(['pages' => function ($query) {
            $query->select('id', 'title', 'list_order', 'page_category_id')
            ->orderBy('list_order', 'asc');
        }])->get();

        return response()->json(['categories' => $categories], 200);
    }

    public function show($id)
    {
        $category = PageCategory::find($id);
        
        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }

        return response()->json(['data' => $category], 200);
    }

    // make a category
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string'
        ]);
        $oldCategory = PageCategory::all()->where('name', $request->name)->first();
        if($oldCategory){
            return response()->json(['error'=>"the category already exists"],402);
        }
        try{
            $page = PageCategory::create([
                'name' => $request->input('name')
            ]);
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()],402);
        }


        return response()->json(['data' => $page], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $category = PageCategory::find($id);

        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }

        try{
            $category->update([
                'name' => $request->input('name')
            ]);
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()],402);
        }


        return response()->json(['data' => $category], 200);
    }
    public function destroy($id)
    {
        $category = PageCategory::find($id);

        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'category deleted successfully'], 200);
    }

}
