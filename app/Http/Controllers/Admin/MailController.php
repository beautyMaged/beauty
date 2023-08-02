<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.mail.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            "status" => 'required',
            "name" => 'required',
            "host" => 'required',
            "driver" => 'required',
            "port" => 'required',
            "username" => 'required',
            "email" => 'required',
            "encryption" => 'required',
            "password" => 'required',
        ]);

        if ($request['status'] == 1) {
            $mail_config_sendgrid = BusinessSetting::where(['type' => 'mail_config_sendgrid'])->first();
            $data_mail_sendgrid = json_decode($mail_config_sendgrid['value'], true);

            BusinessSetting::where(['type' => 'mail_config_sendgrid'])->update([
                'value' => json_encode([
                    "status" => 0,
                    "name" => $data_mail_sendgrid['name'],
                    "host" => $data_mail_sendgrid['host'],
                    "driver" => $data_mail_sendgrid['driver'],
                    "port" => $data_mail_sendgrid['port'],
                    "username" => $data_mail_sendgrid['username'],
                    "email_id" => $data_mail_sendgrid['email_id'],
                    "encryption" => $data_mail_sendgrid['encryption'],
                    "password" => $data_mail_sendgrid['password']
                ])
            ]);
        }

        BusinessSetting::where(['type' => 'mail_config'])->update([
            'value' => json_encode([
                "status" => $request['status'],
                "name" => $request['name'],
                "host" => $request['host'],
                "driver" => $request['driver'],
                "port" => $request['port'],
                "username" => $request['username'],
                "email_id" => $request['email'],
                "encryption" => $request['encryption'],
                "password" => $request['password']
            ])
        ]);
        Toastr::success('Configuration updated successfully!');
        return back();
    }

    public function update_sendgrid(Request $request)
    {
        $request->validate([
            "status" => 'required',
            "name" => 'required',
            "host" => 'required',
            "driver" => 'required',
            "port" => 'required',
            "username" => 'required',
            "email" => 'required',
            "encryption" => 'required',
            "password" => 'required',
        ]);

        if ($request['status'] == 1) {
            $mail_config = BusinessSetting::where(['type' => 'mail_config'])->first();
            $data_mail_smtp = json_decode($mail_config['value'], true);

            BusinessSetting::where(['type' => 'mail_config'])->update([
                'value' => json_encode([
                    "status" => 0,
                    "name" => $data_mail_smtp['name'],
                    "host" => $data_mail_smtp['host'],
                    "driver" => $data_mail_smtp['driver'],
                    "port" => $data_mail_smtp['port'],
                    "username" => $data_mail_smtp['username'],
                    "email_id" => $data_mail_smtp['email_id'],
                    "encryption" => $data_mail_smtp['encryption'],
                    "password" => $data_mail_smtp['password']
                ])
            ]);
        }
        BusinessSetting::where(['type' => 'mail_config_sendgrid'])->update([
            'value' => json_encode([
                "status" => $request['status'],
                "name" => $request['name'],
                "host" => $request['host'],
                "driver" => $request['driver'],
                "port" => $request['port'],
                "username" => $request['username'],
                "email_id" => $request['email'],
                "encryption" => $request['encryption'],
                "password" => $request['password']
            ])
        ]);
        Toastr::success('SendGrid Configuration updated successfully!');
        return back();
    }

    public function send(Request $request)
    {
        $response_flag = 0;
        try {
            $emailServices_smtp = Helpers::get_business_settings('mail_config');
            if ($emailServices_smtp['status'] == 0) {
                $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
            }
            if ($emailServices_smtp['status'] == 1) {
                Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
                $response_flag = 1;
            }
        } catch (\Exception $exception) {
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }
}
