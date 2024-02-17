<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\RefundRequestRequest;
use App\Http\Resources\RefundRequestResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerRefundRequestMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\RefundRequest;
use App\Model\Notification;
use App\Model\OrderDetail;
use App\Model\Admin;
use App\Model\Seller;
use App\Model\SellerNotification;
use App\Model\AdminNotification;
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
        $refundRequests = RefundRequest::all()->where("customer_id", Auth::user()->id);
        return response()->json(RefundRequestResource::collection($refundRequests));
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

        if(!$order_details){
            return response()->json(['error' => 'not found'],404);
        }

        $seller = Seller::find($order_details->seller_id);
        // dd($seller);
        // check if the ordered item is refundable
        if(!$this->isOrderRefundable($seller->refundPolicy->refund_max, $order_details->created_at/* important!, change this to 'delivered_at' after handling it in orderController */)){
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
            DB::beginTransaction();
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
            $order_details->order->order_status = 'refundReview';
            $order_details->order->save();

            // send notifications
            $title = 'refund Request of '. $order_details->variant->values[0]->option->product->name;
            $description = 'user "'. Auth::user()->id. '" made a refund request';
            SellerNotification::create([
                'title' => $title,
                'description' => $description,
                'url' =>'/app/customer/refundRequests/'. RefundRequest::latest()->first()->id ,
                'seller_id' => $seller->id	
            ]);
            AdminNotification::create([
                'title' => $title,
                'description' => $description,
                'url' =>'/app/customer/refundRequests/'. RefundRequest::latest()->first()->id ,
                'admin_id' => Admin::where('admin_role_id',1)->first()->id,	
            ]);
            // Send email to the seller
            $recipients = [
                $seller->email,
                Admin::where('admin_role_id',1)->first()->email,
            ];
            Mail::to($recipients)->send(new SellerRefundRequestMail([
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
            DB::commit();
            return response()->json(['message'=>'refund request has been submitted, under review'],200);
        }catch(Exception $e){
            DB::rollBack();
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
        
        $refundRequest = RefundRequest::where("customer_id", Auth::user()->id)->find($id);
        if ($refundRequest) {
            $resource = new RefundRequestResource($refundRequest);
            return response()->json([
                "message" => "request found successfully",
                "refund_request" => $resource
            ], 200);
        } else {
            return response()->json(["message" => "Refund request not found"], 404);
        }
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
