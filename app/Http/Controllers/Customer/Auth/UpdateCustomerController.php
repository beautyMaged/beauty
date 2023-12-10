<?php

namespace App\Http\Controllers\Customer\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerPasswordRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class UpdateCustomerController extends Controller
{
    //
    function __construct(){
        $this->middleware('auth:customer');
    }

    // update customer account data
    public function update(UpdateCustomerRequest $request)
    {
        
        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {


            $user->email = $request->email;
            $user->f_name = $request->f_name;
            $user->l_name = $request->l_name;
            $user->phone = $request->phone;

            // Check image is provided
            if ($request->hasFile('image')) {
                
                $imagePath = $request->file('image')->store('images', 'public');
                $user->image = $imagePath;
            }

            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Account data updated successfully'], 200);
        }else{

            return response()->json(['status' => 'error', 'message' => 'Invalid old password'], 422);
        }
        


    }
    // update account password
    public function updatePassword(UpdateCustomerPasswordRequest $request)
    {
        $user = Auth::user();

        if (Hash::check($request->old_password, $user->password)) {
            
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Password updated successfully'], 200);

        } else {
            // Passwords do not match
            return response()->json(['status' => 'error', 'message' => 'Invalid old password'], 422);
        }
    }

}
