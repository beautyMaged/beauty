<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Model\BestProduct;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BestProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['store']);
        $this->middleware('auth:admin,seller')->only(['store']);
    }

    public function index()
    {
        try {
            $bestProducts = BestProduct::with('product')
                ->orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')")
                ->paginate(10);
        
            return response()->json(['bestProducts' => $bestProducts], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving best products', 'error' => $e->getMessage()], 500);
        }
    }
    
    

    public function store(Request $request){
    try {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $user = auth('admin')->check() ? 'admin' : (auth('seller')->check() ? 'seller' : null);

        if ($user === 'seller') {
            $sellerId = auth('seller')->user()->id;
            $product = Product::where('id', $request->product_id)->where('user_id', $sellerId)->first();
            if($product->bestProduct){
                return response()->json(['message'=>'the product is already set in best products'],409);
            }

            if (!$product) {
                return response()->json(['message' => 'Invalid product ID for the authenticated seller'], 422);
            }
        }

        DB::beginTransaction();

        BestProduct::create(['product_id' => $request->product_id]);

        DB::commit();

        return response()->json(['message' => 'Best product created successfully'], 201);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json(['message' => 'Error creating best product', 'error' => $e->getMessage()], 500);
    }
}


    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:approved,rejected'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }

            $product = Product::findOrFail($id);
            if(!$product->bestProduct){
                return response()->json(['message' => 'Product is not set as best product'], 500);
            }
            $product->bestProduct->status = $request->status;
            $product->bestProduct->save();

            return response()->json(['message' => 'product updated successfully'], 200);
        }catch (\Exception $e) {
            return response()->json(['message' => 'Error updating best product', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if(!$product || !$product->bestProduct){
                return response()->json(['message' => 'not found'],404);
            }
            $product->bestProduct->delete();

            return response()->json(['message' => 'Best product deleted successfully'], 200);
        }catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting best product', 'error' => $e->getMessage()], 500);
        }
    }
}
