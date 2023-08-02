<?php

namespace App\Http\Controllers\api\v3\seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use App\Model\Shop;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class ChatController extends Controller
{

    public function list(Request $request, $type)
    {
        $seller = $request->seller;

        if ($type == 'customer') {
            $with_param = 'customer';
            $id_param = 'user_id';
        } elseif ($type == 'delivery-man') {
            $with_param = 'delivery_man';
            $id_param = 'delivery_man_id';
        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $total_size = Chatting::where(['seller_id' => $seller['id']])
            ->whereNotNull($id_param)
            ->select($id_param)
            ->distinct()
            ->get()
            ->count();

        $unique_chat_ids = Chatting::where(['seller_id' => $seller['id']])
            ->whereNotNull($id_param)
            ->select($id_param)
            ->distinct()
            ->paginate($request->limit, ['*'], 'page', $request->offset);

        $chats = array();
        if ($unique_chat_ids) {
            foreach ($unique_chat_ids as $unique_chat_id) {
                $chats[] = Chatting::with([$with_param])
                    ->where(['seller_id' => $seller['id'], $id_param => $unique_chat_id->$id_param])
                    ->whereNotNull($id_param)
                    ->latest()
                    ->first();
            }
        }

        $data = array(
            'data' => $seller,
            'total_size' => $total_size,
            'limit' => $request->limit,
            'offset' => $request->offset,
            'chat' => $chats,
        );

        return response()->json($data, 200);
    }

    public function search(Request $request, $type)
    {
        $seller = $request->seller;

        $terms = explode(" ", $request->input('search'));
        if ($type == 'customer') {
            $with_param = 'customer';
            $id_param = 'user_id';
            $users = User::where('id', '!=', 0)
                ->when($request->search, function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->where('f_name', 'like', '%' . $term . '%')
                            ->orWhere('l_name', 'like', '%' . $term . '%');
                    }
                })->pluck('id')->toArray();

        } elseif ($type == 'delivery-man') {
            $with_param = 'delivery_man';
            $id_param = 'delivery_man_id';
            $users = DeliveryMan::where(['seller_id' => $seller['id']])
                ->when($request->search, function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->where('f_name', 'like', '%' . $term . '%')
                            ->orWhere('l_name', 'like', '%' . $term . '%');
                    }
                })->pluck('id')->toArray();
        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $unique_chat_ids = Chatting::where(['seller_id' => $seller['id']])
            ->whereIn($id_param, $users)
            ->select($id_param)
            ->distinct()
            ->get()
            ->toArray();
        $unique_chat_ids = call_user_func_array('array_merge', $unique_chat_ids);

        $chats = array();
        if ($unique_chat_ids) {
            foreach ($unique_chat_ids as $unique_chat_id) {
                $chats[] = Chatting::with([$with_param])
                    ->where(['seller_id' => $seller['id'], $id_param => $unique_chat_id])
                    ->whereNotNull($id_param)
                    ->latest()
                    ->first();
            }
        }

        return response()->json($chats, 200);
    }

    public function get_message(Request $request, $type, $id)
    {
        $seller = $request->seller;
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
            'limit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($type == 'customer') {
            $id_param = 'user_id';
            $sent_by = 'sent_by_customer';
            $with = 'customer';
        } elseif ($type == 'delivery-man') {
            $id_param = 'delivery_man_id';
            $sent_by = 'sent_by_delivery_man';
            $with = 'delivery_man';

        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $query = Chatting::with($with)->where(['seller_id' => $seller['id'], $id_param => $id])->latest();

        if (!empty($query->get())) {
            $message = $query->paginate($request->limit, ['*'], 'page', $request->offset);

            if ($query->where($sent_by, 1)->latest()->first()) {
                $query->where($sent_by, 1)->latest()->first()
                    ->update(['seen_by_seller' => 1]);
            }

            $data = array(
                'data' => $seller,
                'total_size' =>$message->total(),
                'limit' =>$request->limit,
                'offset' =>$request->offset,
                'message' =>$message->items(),
            );
            return response()->json($data, 200);
        }
        return response()->json(['message' => translate('no messages found!')], 200);

    }

    public function send_message(Request $request, $type)
    {
        $seller = $request->seller;

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'message' => 'required',
        ], [
            'message.required' => translate('type something!')
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $shop_id = Shop::where('seller_id', $seller['id'])->first()->id;

        $chatting = new Chatting();
        $chatting->seller_id = $seller->id;
        $chatting->message = $request->message;
        $chatting->sent_by_seller = 1;
        $chatting->seen_by_seller = 1;
        $chatting->shop_id = $shop_id;

        if ($type == 'delivery-man') {
            $chatting->delivery_man_id = $request->id;
            $chatting->seen_by_delivery_man = 0;

            $dm = DeliveryMan::find($request->id);
            $fcm_token = $dm->fcm_token;
        } elseif ($type == 'customer') {
            $chatting->user_id = $request->id;
            $chatting->seen_by_customer = 0;

            $dm = User::find($request->id);
            $fcm_token = $dm->cm_firebase_token;
        } else {
            return response()->json(translate('Invalid Chatting Type!'), 403);
        }

        if ($chatting->save()) {

            if (!empty($fcm_token)) {
                $data = [
                    'title' => translate('message'),
                    'description' => $request->message,
                    'order_id' => '',
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }

            return response()->json(['message' => $request->message, 'time' => now()], 200);
        } else {
            return response()->json(['message' => translate('Message sending failed')], 403);
        }
    }
}
