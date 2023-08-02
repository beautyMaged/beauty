<?php

namespace App\Http\Controllers\Web;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Review;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function App\CPU\translate;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $image_array = [];
        if ($request->has('fileUpload')) {
            foreach ($request->file('fileUpload') as $image) {
                array_push($image_array, ImageManager::upload('review/', 'png', $image));
            }
        }

        Review::updateOrCreate(
            [
                'delivery_man_id' => null,
                'customer_id' => auth('customer')->id(),
                'product_id' => $request->product_id
            ],
            [
                'customer_id' => auth('customer')->id(),
                'product_id' => $request->product_id,
                'comment' => $request->comment,
                'rating' => $request->rating,
                'attachment' => json_encode($image_array),
            ]
        );

        Toastr::success(translate('successfully_added_review'));
        return redirect()->route('account-order-details', ['id' => $request->order_id]);
    }

    public function delivery_man_review(Request $request, $id)
    {
        $order = Order::where(['id' => $id, 'customer_id' => auth('customer')->id(), 'payment_status' => 'paid'])->first();

        if (!$order) {
            Toastr::error(translate('Invalid order!'));
            return redirect('/');
        }

        return view('web-views.users-profile.submit-delivery-man-review', compact('order'));
    }

    public function delivery_man_submit(Request $request)
    {

        $order = Order::where([
            'id' => $request->order_id,
            'customer_id' => auth('customer')->id(),
            'payment_status' => 'paid'])->first();

        if (!isset($order->delivery_man_id)) {
            Toastr::error(translate('Invalid review!'));
            return redirect('/');
        }

        Review::updateOrCreate(
            ['delivery_man_id' => $order->delivery_man_id,
                'customer_id' => auth('customer')->id(),
                'order_id' => $request->order_id
            ],
            [
                'customer_id' => auth('customer')->id(),
                'delivery_man_id' => $order->delivery_man_id,
                'order_id' => $request->order_id,
                'comment' => $request->comment,
                'rating' => $request->rating,
            ]
        );
        Toastr::success(translate('successfully_added_review'));
        return redirect()->route('account-order-details', ['id' => $order->id]);
    }
}
