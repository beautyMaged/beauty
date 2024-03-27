<?php

namespace App\Http\Controllers\Customer;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;
use Illuminate\Support\Facades\Log;
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

    public function followSeller($id){
        try {
            $seller = Seller::find($id);
            if ($seller) {
                $user = User::find(Auth::user()->id);
                $user->followedSellers()->attach($id);
                return response()->json(['data' => 'followed'], 200);
            } else {
                return response()->json(['data' => 'not found'], 404);
            }
        } catch (Exception $e) {
            Log::info(response()->json(['error' => $e->getMessage()]));
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
    public function unFollowSeller($id){
        try {
            $user = User::find(Auth::user()->id);
            $seller = $user->followedSellers()->find($id);
            if ($seller) {
                $user->followedSellers()->detach($id);
                return response()->json(['data' => 'unfollowed'], 200);
            } else {
                return response()->json(['data' => 'not found'], 404);
            }
        } catch (Exception $e) {
            Log::info(response()->json(['error' => $e->getMessage()]));
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
}
