<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerRefundRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $seller;
    public $order_details;
    public $customer_name;
    public $product;
    public $quantity;
    public $amount;
    public $refund_reason;
    public $refund_request_reason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    

    public function __construct($data)
    {
        $this->seller = $data['seller'];
        $this->order_details = $data['order_details'];
        $this->customer_name = $data['customer_name'];
        $this->product = $data['product'];
        $this->quantity = $data['quantity'];
        $this->amount = $data['amount'];
        $this->refund_reason = $data['refund_reason'];
        $this->refund_request_reason = $data['refund_request_reason'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
    

    return $this->subject('Refund Request Notification')
                ->markdown('email-templates.refund-request-seller');
}

}
