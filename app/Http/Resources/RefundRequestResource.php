<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RefundRequestResource extends JsonResource
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
            'order_details_id'=>$this->order_details_id,
            'customer_name'=> $this->customer->f_name . " " . $this->customer->l_name,
            'status' =>$this->status,
            'approved_note'=>$this->approved_note,
            'rejected_note'=>$this->rejected_note,
            'amount'=>$this->amount,
            'product_id'=>$this->product_id,
            'order_id'=>$this->order_id,
            'refund_reason'=>$this->refund_reason,
            'bill_image'=>$this->bill_image,
            'order_date'=>$this->order->created_at,
            'product_name'=>$this->order_details->variant->values[0]->option->product->name,
            'product_slug'=>$this->order_details->variant->values[0]->option->product->slug,
            'seller_name'=>$this->order_details->seller->f_name . " " . $this->order_details->seller->l_name,
        ];
    }
}
