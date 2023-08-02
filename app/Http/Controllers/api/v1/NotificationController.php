<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\Notification;

class NotificationController extends Controller
{
    public function get_notifications()
    {
        try {
            return response()->json(Notification::active()->orderBy('id','DESC')->get(), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
