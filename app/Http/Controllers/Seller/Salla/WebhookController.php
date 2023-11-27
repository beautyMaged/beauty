<?php

namespace App\Http\Controllers\Seller\Salla;

use App\Http\Requests\Salla\WebhookRequest;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function __invoke(WebhookRequest $request)
    {
        $event = explode('.', $request->get('event'));
        $component = $event[0];
        $action = Str::camel(Str::replace('.', '_', Str::after($request->get('event'), $component . '.')));
        $classOfAction = sprintf('\\App\\Salla\\Actions\\%s\\%s', ucfirst($component), ucfirst($action));
        if (!class_exists($classOfAction))
            return response('Ok, but without process');

        $classOfAction::make()->setRequest($request)->handle();
        return response('🎉');
    }
}
