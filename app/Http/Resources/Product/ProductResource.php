<?php

namespace App\Http\Resources\Product;
use App\Model\Product;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $badges = [
            'newProduct' => $this->created_at > now()->subDays(7),

            'bestSellingGlobally' => $this->categories()->first()->pivot->best_selling_globally ? true : false,
            
            'bestSellingIn' => [
                ...$this->categories()->where('best_selling', 1)->pluck('id')
            ],

            'topRatedGlobally' => $this->categories()->first()->pivot->top_rated_globally ? true : false,
            
            'topRatedIn' => [
                ...$this->categories()->where('best_selling', 1)->pluck('id')
            ],

            'bestPrice'=> $this->hasBestPrice($this->id),

            'limited_amount'=> $this->current_stock < 10,
        ];
        return array_merge($data, $badges);
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
}
