<?php

namespace App\Http\Controllers\Admin;

use App\Model\SellerCategoryCommission;
use App\Model\Category;
use App\Model\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class CommissionController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }
    
        
    
    // edit general category comission
    public function updateCategoryCommission($id, Request $request){

        $request->validate([
            'category_commission' => 'required|numeric|min:0'
        ]);

        $category = Category::find($id);
        if(!$category){
            return response()->json(['error'=>'category not found'],404);
        };
        $category->update([
            'category_commission' => $request->category_commission
        ]);
        return response()->json(['message' => 'commission edited', 'category'=>$category->name, 'commission'=>$category->category_commission],200);
    }

    // edit seler commission for each category
    public function updateSellerCategoryCommission($cId, $sId, Request $request){
        
        // Validate the request data
        $request->validate([
            'commission' => 'required|numeric|min:0'
        ]);
        $sellerCategoryCommission = SellerCategoryCommission::where('seller_id',$sId)->where('category_id',$cId)->first();
        
        if(!$sellerCategoryCommission){
            try{
                return $this->setSellerCategoryCommission($cId, $sId, $request);
            }catch(Exception $e){
                return response()->json(['error'=>'seller id or category id is invalid'],500);
            }
        }

        $sellerCategoryCommission->update([
            'commission' => $request->commission
        ]); 
        $seller = $sellerCategoryCommission->seller;
        return response()->json(['message' => 'commission edited',
                                 'category'=>$sellerCategoryCommission->category->name,
                                  'seller'=>$seller->f_name . " " .$seller->l_name ],200);

    }
    
    // set a new commission
    public function setSellerCategoryCommission($cId, $sId, Request $request){
        
        $sellerCategoryCommission = SellerCategoryCommission::create([
            'seller_id'=>$sId,
            'category_id'=>$cId,
            'commission'=>$request->commission
        ]);
        $seller = $sellerCategoryCommission->seller;
        return response()->json(['message' => 'commission set',
                                 'category'=>$sellerCategoryCommission->category->name,
                                  'seller'=>$seller->f_name . " " .$seller->l_name ],200);
                                  
    }

    // edit product commission
    public function updateProductCommission($id, Request $request){

        $request->validate([
            'product_commission' => 'required|numeric|min:0'
        ]);
        
        $product = Product::find($id);
        if(!$product){
            return response()->json(['error'=>'product not found'],404);
        };
        $product->product_commission = $request->product_commission;
        $product->save();
        return response()->json(['message'=>'Product commission updated',
                                 'product_name'=>$product->name,
                                'product_commission'=>$product->product_commission],200);
    }

    public function deleteSellerCategoryCommission($cId, $sId){
        try{
            SellerCategoryCommission::where('category_id',$cId)->where('seller_id',$sId)->delete();
            return response()->json(['message'=>'commission deleted successfully'],200);
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()],404);
        }
    }
}
