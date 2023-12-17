<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;
class NotificationController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:customer');

    }
    public function allow_notifications(){
        // dd(Auth::user());
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->allow_notifications = true;
        try{
            $user->save();
            return response()->json(["message" => "notifications allowed"],200);
        }catch(Exception $e){
            return response()->json(["message" => "database error", "error"=>$e->getMessage()],500);

        }
        
    }

    public function block_notifications(){
        $user = Auth::user();
        $user->allow_notifications = false;
        try{
            $user->save();
            return response()->json(["message" => "notifications blocked"],200);
        }catch(Exception $e){
            return response()->json(["message" => "database error", "error"=>$e->getMessage()],500);

        }
        
    }
}
