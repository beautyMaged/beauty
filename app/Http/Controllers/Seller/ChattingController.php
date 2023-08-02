<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use App\Model\Seller;
use App\Model\Shop;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class ChattingController extends Controller
{
    /**
     * chatting list
     */
    public function chat(Request $request, $type)
    {
        $shop = Shop::where('seller_id', auth('seller')->id())->first();
        $shop_id = $shop->id;

        if ($type == 'delivery-man') {
            $last_chat = Chatting::where('seller_id', auth('seller')->id())
                ->whereNotNull(['delivery_man_id', 'seller_id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            if (isset($last_chat)) {
                Chatting::where(['seller_id'=> auth('seller')->id(), 'delivery_man_id'=> $last_chat->delivery_man_id])->update([
                    'seen_by_seller' => 1
                ]);

                $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                    ->where('chattings.seller_id', auth('seller')->id())
                    ->where('delivery_man_id', $last_chat->delivery_man_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get();

                $chattings_user = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image', 'delivery_men.phone')
                    ->where('chattings.seller_id', auth('seller')->id())
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('delivery_man_id');

                return view('seller-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop'));
            }

        }elseif($type == 'customer'){
            $last_chat = Chatting::where('shop_id', $shop_id)
                ->whereNotNull(['user_id', 'seller_id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            if (isset($last_chat)) {
                Chatting::where(['shop_id' => $shop_id, 'user_id' => $last_chat->user_id])->update([
                    'seen_by_seller' => 1
                ]);

                $chattings = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                    ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image')
                    ->where('chattings.shop_id', $shop_id)
                    ->where('user_id', $last_chat->user_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get();

                $chattings_user = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                    ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image', 'users.phone')
                    ->where('chattings.shop_id', $shop_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('user_id');

                return view('seller-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop'));
            }
        }

        return view('seller-views.chatting.chat', compact('last_chat', 'shop'));
    }

    /**
     * ajax request - get message by delivery man and customer
     */
    public function ajax_message_by_user(Request $request)
    {
        if ($request->has('delivery_man_id')) {
            Chatting::where(['seller_id' => auth('seller')->id(), 'delivery_man_id' => $request->delivery_man_id])
                ->update([
                    'seen_by_seller' => 1
                ]);

            $sellers = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                ->where('chattings.seller_id', auth('seller')->id())
                ->where('chattings.delivery_man_id', $request->delivery_man_id)
                ->orderBy('created_at', 'ASC')
                ->get();

        }
        elseif ($request->has('user_id')) {
            $shop_id = Shop::where('seller_id', auth('seller')->id())->first()->id;

            Chatting::where(['seller_id' => auth('seller')->id(), 'user_id' => $request->user_id])
                ->update([
                    'seen_by_seller' => 1
                ]);

            $sellers = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image')
                ->where('chattings.shop_id', $shop_id)
                ->where('chattings.user_id', $request->user_id)
                ->orderBy('created_at', 'ASC')
                ->get();

        }

        return response()->json($sellers);
    }

    /**
     * ajax request - Store massage
     */
    public function ajax_seller_message_store(Request $request)
    {
        if ($request->message == '') {
            return response()->json(translate('type_something!'), 403);
        }

        $shop_id = Shop::where('seller_id', auth('seller')->id())->first()->id;

        $message = $request->message;
        $time = now();

        if ($request->has('delivery_man_id')) {

            Chatting::create([
                'delivery_man_id' => $request->delivery_man_id,
                'seller_id' => auth('seller')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'sent_by_seller' => 1,
                'seen_by_seller' => 1,
                'created_at' => now(),
            ]);

            $dm = DeliveryMan::find($request->delivery_man_id);
            if(!empty($dm->fcm_token)) {
                $data = [
                    'title' => translate('message'),
                    'description' => $request->message,
                    'order_id' => '',
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($dm->fcm_token, $data);
            }

        }elseif ($request->has('user_id')) {
            Chatting::create([
                'user_id' => $request->user_id,
                'seller_id' => auth('seller')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'sent_by_seller' => 1,
                'seen_by_seller' => 1,
                'created_at' => now(),
            ]);

            $dm = User::find($request->user_id);
            $data = [
                'title' => translate('message'),
                'description' => $request->message,
                'order_id' => '',
                'image' => '',
            ];
            if(!empty($dm->cm_firebase_token)) {
                Helpers::send_push_notif_to_device($dm->cm_firebase_token, $data);
            }
        }

        return response()->json(['message' => $message, 'time' => $time]);
    }

}
