<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CustomerWallet;
use Illuminate\Support\Facades\Auth;

class CustomerWalletController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function show(){
        $customerWallet = CustomerWallet::where('cutomer_id', Auth::user()->id);
        return response()->json($customerWallet,200);
    }
}
