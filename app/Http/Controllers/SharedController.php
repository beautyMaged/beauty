<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Session;

class SharedController extends Controller
{
    public function lang($local)
    {
        $direction = 'ltr';
        $language = BusinessSetting::where('type', 'language')->first();
        foreach (json_decode($language['value'], true) as $data) {
            if ($data['code'] == $local)
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
        }
        session()->forget('language_settings');
        Helpers::language_load();
        session()->put('local', $local);
        Session::put('direction', $direction);
        return redirect()->back();
    }
}
