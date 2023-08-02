<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class ChattingController extends Controller
{
    /**
     * chatting list
     */
    public function chat(Request $request)
    {
        $last_chat = Chatting::where('admin_id', 0)
            ->whereNotNull(['delivery_man_id', 'admin_id'])
            ->orderBy('created_at', 'DESC')
            ->first();

        if (isset($last_chat)) {
            Chatting::where(['admin_id'=>0, 'delivery_man_id'=> $last_chat->delivery_man_id])->update([
                'seen_by_admin' => 1
            ]);


            $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                ->where('chattings.admin_id', 0)
                ->where('delivery_man_id', $last_chat->delivery_man_id)
                ->orderBy('chattings.created_at', 'desc')
                ->get();

            $chattings_user = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image', 'delivery_men.phone')
                ->where('chattings.admin_id', 0)
                ->orderBy('chattings.created_at', 'desc')
                ->get()
                ->unique('delivery_man_id');

            return view('admin-views.delivery-man.chat', compact('chattings', 'chattings_user', 'last_chat'));
        }

        return view('admin-views.delivery-man.chat', compact('last_chat'));
    }

    /**
     * ajax request - get message by delivery man
     */
    public function ajax_message_by_delivery_man(Request $request)
    {

        Chatting::where(['admin_id' => 0, 'delivery_man_id' => $request->delivery_man_id])
            ->update([
                'seen_by_admin' => 1
            ]);

        $sellers = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
            ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
            ->where('chattings.admin_id', 0)
            ->where('chattings.delivery_man_id', $request->delivery_man_id)
            ->orderBy('created_at', 'ASC')
            ->get();

        return response()->json($sellers);
    }

    /**
     * ajax request - Store massage for deliveryman
     */
    public function ajax_admin_message_store(Request $request)
    {
        if ($request->message == '') {
            Toastr::warning('Type Something!');
            return response()->json(['message' => 'type something!']);
        }

        $message = $request->message;
        $time = now();

        Chatting::create([
            'delivery_man_id' => $request->delivery_man_id,
            'admin_id' => 0,
            'message' => $request->message,
            'sent_by_admin' => 1,
            'seen_by_admin' => 1,
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

        return response()->json(['message' => $message, 'time' => $time]);
    }
}
