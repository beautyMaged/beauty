<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\Product;
use Mockery\Undefined;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'breadcrumb' => $this->breadcrumb,

            'name' => $this->name,

            'slug'  => $this->slug,
            
            'brand_name' => $this->brand->name,

            'brand_id' => $this->brand->id,

            'description' => $this->meta_description,

            'main_image' => $this->meta_image,

            'images'   => $this->images,

            'details' =>$this->details,

            'coupons' =>$this->coupons,

            'discount' =>$this->discount,

            'discount_type' =>$this->discount_type,

            'current_stock' =>$this->current_stock,

            'oldPrice' =>$this->unit_price,

            'net_price' => ($this->unit_price * $this->disount) / 100,
            
            'options' => $this->options,

            'newProduct' => $this->created_at > now()->subDays(7),

            'bestSellingGlobally' => $this->categories()->first()->pivot->best_selling_globally ? true : false,
            
            'bestSellingIn' => [

                ...$this->categories()->where('best_selling', 1)->pluck('slug')

            ],

            'topRatedGlobally' => $this->categories()->first()->pivot->top_rated_globally ? true : false,
            
            'topRatedIn' => [
                ...$this->categories()->where('best_selling', 1)->pluck('slug')
            ],

            'bestPrice'=> $this->hasBestPrice($this->id),

            'limited_amount'=> $this->current_stock < 10,

            'coupons' => $this->coupons,

            'variants' => $this->variants->map(function($variant){

                return [
                    'id' => $variant->id,

                    'price' => $variant->price,

                    'discount' => ($variant->discount.'%'),

                    'sale_price' => $variant->price - ($variant->discount/100 * $variant->price),

                    'quantity' => $variant->quantity,

                    'sku' => $variant->sku,

                    'product_id' => $variant->product_id,

                    'is_default' => $variant->is_default,

                    'options' => $variant->values->map(function ($value) {

                        return [
                            'id' => $value->option->id,

                            'product_id' => $value->option->product_id,

                            'name' => $value->option->name,

                            'value' => $value->value,
                        ];
                    }),

                    'discount' => $variant->discount
                ];
            }),

            'similarProducts' => $this->getSimilarProducts($this->id),

            'seller\'s_recent_products' => $this->seller->products()->latest('created_at')->limit(3)->get(),

            'reviews' => $this->reviews,

            'brad_products' => $this->brandProducts(),
            
            'same_category_products' => $this->productsFromCategory(),
            'previous_category_products' => $this->productsFromCategory(null ,$this->category->parent),
        ];
    }

    // does the product has best price amoung similar products?
    private function hasBestPrice($id)
    {
        $product = Product::find($id);
        $similarProducts = $product->name()->getResults()->products;

        // no other prodcts with the same name
        if(count($similarProducts) == 1){
            return false;
        }
        $minPrice = $product->unit_price; //price is called unit_price

        foreach ($similarProducts as $product) {
            if ($product->unit_price < $minPrice) {
                return false; // Current product doesn't have the best price
            }
        }

        return true; // Current product has the best price
    }

    private function getSimilarProducts($id){
        $ProductName = Product::find($id)->name()->getResults();
        return $ProductName->products->filter(function($product) use($id) {
            return $product->id != $id;
        });
        // return SingleProductResource::collection($products);
    }

    private function brandProducts(){

        // products of the same seller in the same brand
        $sellerProducts = $this->brand->
                                products()->
                                where('user_id', $this->seller->id)
                                ->inRandomOrder()
                                ->limit(10)
                                ->get();


        if(count($sellerProducts) < 10 ){
            // product of other sellers in the same brand
            $sameBrandProducts = Product::where('brand_id', $this->brand->id)
                                        ->where('user_id','!=', $this->seller->id)
                                        ->inRandomOrder()
                                        ->limit(10 - count($sellerProducts))->get();
            
            $brandProducts = $sellerProducts->merge($sameBrandProducts);

            if(count($brandProducts) < 10){

                return $brandProducts->

                merge($this->productsFromCategory(10 - count($brandProducts)));
            }
            return $brandProducts;
        }else{
            return $sellerProducts;
        };
    }

    private function productsFromCategory($numberOfProducts = 10,$category = null){

        if ($category === null) {
            $category = $this->category;
        }

        $sellerProducts = $category
                        ->products()
                        ->where('user_id','!=', $this->seller->id)
                        ->where('user_id', $this->seller->id)
                        ->inRandomOrder()
                        ->limit($numberOfProducts)
                        ->get();

        if(count($sellerProducts) < 10 ){
            $sameCategoryProducts = $category
                                    ->products()
                                    ->where('user_id','!=', $this->seller->id)
                                    ->inRandomOrder()
                                    ->limit(10 - count($sellerProducts))->get();

            $brandProducts = $sellerProducts->merge($sameCategoryProducts);

            return $brandProducts;
        }else{
            return $sellerProducts;
        }
    }


}
