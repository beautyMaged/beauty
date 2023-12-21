<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Model\Customer;
use App\Model\ShippingAddress;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\NewShippingAddressRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;



class ShippingAddressController extends Controller
{
    function __construct(){
        // handle authentication here
        $this->middleware('auth:customer');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('it works!');
        $customer_id = Auth::user()->id;
        $addresses = ShippingAddress::where('customer_id', $customer_id)->get();

        if(!empty($addresses)){
            return response()->json($addresses,200);
        }else{
            return response()->json(["error" => "no addresses found"],404);
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
    public function store( NewShippingAddressRequest $request )
    {

        $customer_id = Auth::user()->id;

        // if address already exists
        $existingAddress = ShippingAddress::where('latitude', $request->head_new_lat)
                                        ->where('longitude', $request->head_new_long)
                                        ->first();

        if ($existingAddress) {
            return response()->json(['error' => 'Address already exists'], 400);
        }
        // default address
        $default_user_address = Auth::user()->shippingAddresses->where('default_address', 1)->first();
        
        $is_default = 0;

        if(!$default_user_address){
            // the new address will be the default
            $is_default = 1;
        }else{
            if($request->default_address == 1){
                // the new address will be the default and the old won't

                $default_user_address->default_address = 0;
                $is_default = 1;
            }else{
                $is_default = 0;
            }
        }
        
        // end defining
        $shippingAddress = new ShippingAddress([
            'is_billing' => $request->is_billing,
            'zip' => $request->zip,
            'apartment_number' => $request->apartment_number,
            'country' => $request->head_country,
            'city' => $request->head_city,
            'street_address' => $request->head_address,
            'latitude' => $request->head_new_lat,
            'longitude' => $request->head_new_long,
            'default_address' => $is_default,
            'customer_id' => $customer_id,
        ]);

        try {
            
            DB::beginTransaction();

            $shippingAddress->save();
            if($default_user_address){
                $default_user_address->save();
            }
            

            DB::commit();
            

            return response()->json(['status' => 'success',
                                    'message' => 'Address saved successfully',
                                    'address'=> $shippingAddress], 201); 
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create shipping address', "error" => $e], 500);
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
        {
            $shippingAddress = Auth::user()->shippingAddresses->find($id);
        
            if (!$shippingAddress) {
                return response()->json(['error' => 'Address not found '], 404);
            }
        
            return response()->json(['address' => $shippingAddress],
                                  200);
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
        $shippingAddress = Auth::user()->shippingAddresses->find($id);
        
        $default_user_address = Auth::user()->shippingAddresses->where('default_address', 1)->first();
        
        // handling errors
        if (!$shippingAddress) {
            return response()->json(['error' => 'address not found'], 404);
        }
        if (!$default_user_address) {
            return response()->json(['error' => 'Default address not found'], 404);
        }

        // save changes
        try{
            DB::beginTransaction();
                        
                $default_user_address->update(['default_address' => 0]);
                $shippingAddress->update(['default_address' => 1]);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['error' => 'Failed to update shipping address', "error" => $e], 500);
        }

        return response()->json(['message' => 'address updated successfully',
                                 'address' => Auth::user()->shippingAddresses->find($id)],
                                  200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $shippingAddress = Auth::user()->shippingAddresses->find($id);
        

        if (!$shippingAddress) {
            return response()->json(['error' => 'address not found'], 404);
        }
        // check if it is the default address
        $is_default = $shippingAddress->default_address;

        // save changes
        try{
            DB::beginTransaction();
                $shippingAddress->delete();

                $new_default = Auth::user()->shippingAddresses->first();
                if($new_default && $is_default){
                    $new_default->default_address = 1; 
                    $new_default->save();
                }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete shipping address', "error" => $e], 500);
        }
        
        return response()->json(['message' => 'address deleted successfully'], 200);
    }
    // copied///////////////////////////////////////////////////////////////////////
    public function confirmLocation(NewShippingAddressRequest $request)
    {
        //        return $request;
        if (auth('customer')->check()) {
            $user = auth('customer')->user();
            // $user->country = $request->head_country;
            // $user->city = $request->head_city;
            // $user->street_address = $request->head_address;
            // $user->lat = $request->head_new_lat;
            // $user->long = $request->head_new_long;
            // $user->save();
            $this->store($request);
        } else {
            session::forget('current_location');
            session::forget('current_city');
            session::forget('current_country');
            session::forget('new_lat');
            session::forget('new_long');

            session::put('current_location', $request->head_address);
            session::put('current_city', $request->head_city);
            session::put('current_country', $request->head_country);
            session::put('new_lat', $request->head_new_lat);
            session::put('new_long', $request->head_new_long);
            //                return $request->city . session('current_city');

        }
        Toastr::success(translate('Location Confirmed'));
        return back();
    }

    // copied //////////////////////////////////////////////////////////////////////////////////
    public function confirmLocationAjax(NewShippingAddressRequest $request)
    {
        if (auth('customer')->check()) {
            // $user = auth('customer')->user();
            // $user->country = $request->country;
            // $user->city = $request->city;
            // $user->street_address = $request->address;
            // $user->lat = $request->new_lat;
            // $user->long = $request->new_long;
            // $user->save();
            // Toastr::success(translate('Location Confirmed'));
            // return response()->json([
            //     'success' => true,
            //     'message' => translate('Location Confirmed')
            // ]);
            $this->store($request);

        }
        session::forget('current_location');
        session::forget('current_city');
        session::forget('current_country');
        session::forget('new_lat');
        session::forget('new_long');

        session::put('current_location', $request->address);
        session::put('current_city', $request->city);
        session::put('current_country', $request->country);
        session::put('new_lat', $request->new_lat);
        session::put('new_long', $request->new_long);

        //                return 'test';
        // Toastr::success(translate('Location Confirmed'));

        return response()->json([
            'success' => true,
            'message' => translate('Location Confirmed')
        ]);
    }
}
