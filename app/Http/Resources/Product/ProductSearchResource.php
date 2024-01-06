<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSearchResource extends JsonResource
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
            'product_name'=>$this->name,
            'product_id'=>$this->id,
            'options'=>$this->options->map(function($option){
                return[
                    'id'=>$option->id,
                    'option_name'=>$option->name,
                    'values'=>$option->values->map(function($value){
                        return [
                            'id'=>$value->id,
                            'value'=>$value->value
                        ];
                    })
                ];
            })
        ];
    }
}
