<?php

namespace App\Http\Controllers\Seller\Zid;

use App\Http\Requests\Zid\WebhookRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __invoke(WebhookRequest $request)
    {
        Log::channel('job')->info('ZidWebHook: ' . json_encode($request->all()));
        $event = explode('.', $request->get('event'));
        $component = $event[0];
        $action = Str::camel(Str::replace('.', '_', Str::after($request->get('event'), $component . '.')));

        $classOfAction = sprintf('\\App\\Zid\\Actions\\%s\\%s', ucfirst($component), ucfirst($action));
        if (!class_exists($classOfAction))
            return response('Ok, but without process');

        $classOfAction::make()->setRequest($request)->handle();
        return response('🎉');
    }
}
