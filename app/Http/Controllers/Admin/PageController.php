<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PageCategory;
use Illuminate\Support\Facades\DB;
use App\Model\Page;
use Exception;
use Illuminate\Http\Request;

class PageController extends Controller
{
    function __construct(){
        $this->middleware('auth:admin')->only('store', 'update', 'destroy');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::orderBy('list_order', 'asc')->get();
        if(!empty($pages)){
            return response()->json(['pages'=>$pages],200);
        }else{
            return response()->json(['message'=>'no pages found'],404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'list_order' => 'required|integer',
            'page_category_id' => 'required|exists:page_categories,id',
        ]);
        // check if the order is already present, if so, moves up all records
        if(Page::where('list_order', $request->list_order)->first()){
            Page::where( 'list_order' , '>=' , $request->list_order )
            ->each(function ($page) {
                $page->increment('list_order');
              });
        }
        try{
            $page = Page::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'list_order' => $request->input('list_order'),
                'page_category_id' => $request->input('page_category_id'),
            ]);
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()],402);
        }


        return response()->json(['data' => $page], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::find($id);
        
        if (!$page) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        return response()->json(['data' => $page], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'list_order' => 'required|integer'
        ]);

        $page = Page::find($id);

        if (!$page) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        $oldListOrder = $page->list_order;
        $newListOrder = $request->input('list_order');

        try {
            DB::beginTransaction();

            // Temporarily set list_order to a very high value to avoid unique constraint
            $page->update(['list_order' => PHP_INT_MAX]);

            // If the new list_order is greater, move down
            if ($newListOrder > $oldListOrder) {
                Page::where('list_order', '>', $oldListOrder)
                    ->where('list_order', '<=', $newListOrder)
                    ->decrement('list_order');
            } else if ($newListOrder < $oldListOrder) {
                // If the new list_order is less, move up
                Page::where('list_order', '>=', $newListOrder)
                    ->where('list_order', '<', $oldListOrder)
                    ->increment('list_order');
            }

            // Update the page with the new data and list_order
            $page->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'list_order' => $newListOrder            
            ]);

            // Commit the transaction if everything is successful
            DB::commit();
        } catch (Exception $e) {
            // Roll back the transaction in case of an exception
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 402);
        }

        return response()->json(['data' => $page], 200);
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);
    
        if (!$page) {
            return response()->json(['error' => 'Page not found'], 404);
        }
    
        $listOrder = $page->list_order;
    
        // Delete the page
        $page->delete();
    
        // Decrement list_order for all pages with list_order greater than the deleted page
        Page::where('list_order', '>', $listOrder)
            ->decrement('list_order');
    
        return response()->json(['message' => 'Page deleted successfully'], 200);
    }
    
}
