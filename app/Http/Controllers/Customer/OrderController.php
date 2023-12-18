<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Order;
use App\Http\Resources\OrdersResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
// use Seshac\Shiprocket\Resources\OrderResource;

class OrderController extends Controller
{
    function __construct(){

        $this->middleware('auth:customer');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

     
     public function index(Request $request)
     {
        // validate query parameters
         try {
             $validatedData = $this->validate($request, [
                 'per_page' => 'nullable|integer|min:1',
                 'order_status' => 'nullable|integer|in:1,2,3,4',
             ]);
         } catch (ValidationException $e) {
             return response()->json(["message" => "Validation error", "errors" => $e->errors()], 422);
         }
         
         $perPage = $validatedData['per_page'] ?? 10;
         $orderStatus = $validatedData['order_status']?? null;
     
         // Define enum codes 
         $orderStatusCodes = [
             'pending' => 1,
             'confirmed' => 2,
             'shipped' => 3,
             'delivered' => 4
         ];
     
         // numeric code to enum value
         $orderStatus = array_search($orderStatus, $orderStatusCodes);
     
         $ordersQuery = Order::with("details.variant.values.option.product")
             ->where("customer_id", Auth::user()->id);
     
         if ($orderStatus) {
             $ordersQuery->where('order_status', $orderStatus);
         }
     
         $orders = $ordersQuery->paginate($perPage);
     
         if ($orders->isNotEmpty()) {
             return response()->json([
                 "message" => "Orders",
                 "orders" => OrdersResource::collection($orders),
                 "pagination" => [
                     "total" => $orders->total(),
                     "per_page" => $orders->perPage(),
                     "current_page" => $orders->currentPage(),
                     "last_page" => $orders->lastPage(),
                     "from" => $orders->firstItem(),
                     "to" => $orders->lastItem(),
                 ],
             ], 200);
         } else {
             return response()->json(["message" => "There are no orders to view"], 404);
         }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::where("customer_id", Auth::user()->id)->find($id);

        if ($order) {
            // $orderDetails = $order->details;
            $resource = new OrdersResource($order);
            return response()->json([
                "message" => "Order found successfully",
                "order" => $resource
            ], 200);
        } else {
            return response()->json(["message" => "Order not found"], 404);
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
    public function update(Request $request, $id)
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
}
