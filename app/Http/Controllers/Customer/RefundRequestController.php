<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\RefundRequestRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerRefundRequestMail;
use App\Model\RefundRequest;
use App\Model\OrderDetail;
use App\Model\Seller;
use Carbon\Carbon;
use Exception;

class RefundRequestController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:customer');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RefundRequestRequest $request)
    {
        $customerId = auth()->user()->id;

        $order_details = OrderDetail::find($request->order_details_id);

        $seller = Seller::find($order_details->seller_id);
        // dd($seller);
        // check if the ordered item is refundable
        if(!$this->isOrderRefundable($seller->sellerPolicy->refund_max, $order_details->created_at/* important!, change this to 'delivered_at' after handling it in orderController */)){
            return response()->json(['message' =>
                                    'The refund period for your order has expired. Please contact our customer service team for further assistance.'],400);
        }

        //check if the requested order_detail belongs to that customer 
        if($order_details->order->customer_id != $customerId){
            return response()->json(['message' =>'wrong order for this user'],401);
        }
        // handeling image
        $billImage = $request->file('bill_image');
        $billImagePath = $billImage->store('refund_request_images', 'public');
        try{
            RefundRequest::create([
                'order_details_id' => $order_details->id,
                'customer_id'=> $customerId,
                'status'=>'pending',
                'amount'=> $order_details->price,
                'product_id'=>$order_details->variant->values[0]->option->product->id,
                'order_id'=> $order_details->order_id,
                'refund_reason'=>$request->refund_reason,
                'refund_request_reason'=> $request->refund_request_reason,
                'bill_image'=> $billImagePath
            ]);
            // Send email to the seller
            Mail::to($seller->email)->send(new SellerRefundRequestMail([
                'seller' => $seller,
                'order_details' => $order_details,
                'order' => $order_details->order,
                'customer_name' => $order_details->order->customer->f_name . " " . $order_details->order->customer->l_name,
                'product' => $order_details->variant->values[0]->option->product,
                'quantity' => $order_details->qty,
                'amount' => $order_details->price,
                'refund_reason'=>$request->refund_reason,
                'refund_request_reason'=> $request->refund_request_reason,
                'bill_image'=> $billImagePath
            ]));
            return response()->json(['message'=>'refund request has been submitted, under review'],200);
        }catch(Exception $e){
            return response()->json(['message'=>'database error', 'error'=>$e->getMessage()],500);

        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RefundRequestRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function isOrderRefundable($refundMaxDays, $deliveredDate) {
        // dd($refundMaxDays, $deliveredDate);
        $deliveredDate = Carbon::parse($deliveredDate);

        $daysSinceDelivered = Carbon::now()->diffInDays($deliveredDate, false);

        return $daysSinceDelivered <= $refundMaxDays;
    }

}
