<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
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
            "orderId" => "order_".$this->id,
            "paymentStatus" => $this->payment_status,
            "orderStatus" => $this->order_status,
            "orderAmount" => $this->order_amount,
            "orderDate" => $this->created_at,
            "discountAmount" => $this->discount_amount,
            "couponCode" => $this->coupon_code,
            "couponDiscountBearer" => $this->coupon_discount_bearer,
            // "shippingMethod" => $this->shipping_method->title,       uncomment after fixing relation
            "shippingCost" => $this->shipping_cost,
            "orderGroupId" => $this->order_group_id,
            "verificationCode" => $this->verification_code,
            "expectedDeliveryDate" => $this->expected_delivery_date,
            "extraDiscount"=>$this->extra_discount,
            "checked"=>$this->checked,
            "shippingType"=>$this->shipping_type,
            "deliveryType"=>$this->delivery_type,
            "deliveryServiceName"=>$this->delivery_service_name,
            "thirdPartyDeliveryTrackingId"=>$this->third_party_delivery_tracking_id,
            "order_details" => $this->details->map(function($detail){
                return ['order_id' => $detail->order_id,
                        'price' => $detail->price,
                        'discount' => $detail->discount,
                        'qty' => $detail->qty,
                        'tax' => $detail->tax,
                        'shipping_method_id' => $detail->shipping_method_id,
                        "sellerName" => $detail->seller->f_name ." ". $detail->seller->l_name,
                        'created_at' => $detail->created_at,
                        'refundRequest'=>$detail->refund_request,
                        'productSku'=>$detail->variant->sku,
                        'productValues'=>$detail->variant->values->map(function($value){
                            return[
                                    'option'=>$value->option->name,
                                    'value'=>$value->value,];
                        }),
                        'brand' => $detail->variant->values[0]->option->product->brand->name,
                        'refundable' => $detail->variant->values[0]->option->product->refundable,
                        'name' => $detail->variant->values[0]->option->product->name,
                        'images' => $detail->variant->values[0]->option->product->images];
                        
                }),
                
            
        ];
    }
}
