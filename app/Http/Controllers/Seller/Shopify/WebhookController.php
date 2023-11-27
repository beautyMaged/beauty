<?php

namespace App\Http\Controllers\Seller\Shopify;

use Shopify\Context;
use Illuminate\Http\Request;
use Shopify\Webhooks\Topics;
use Shopify\Webhooks\Registry;

use Shopify\Auth\FileSessionStorage;
use App\Shopify\Actions\Product\Created;
use App\Shopify\Actions\Product\Deleted;
use App\Shopify\Actions\Product\Updated;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Context::initialize(
            apiKey: config('services.shopify.client_id'),
            apiSecretKey: config('services.shopify.client_secret'),
            scopes: config('services.shopify.scopes'),
            hostName: env('APP_URL'),
            sessionStorage: new FileSessionStorage('/tmp/php_sessions'),
        );
        $topic = explode('/', $request->headers->get('x-shopify-topic'));
        $classOfAction = sprintf('\\App\\Shopify\\Actions\\%s\\%s', ucfirst($topic[0]), ucfirst($topic[1]));
        Registry::addHandler(strtoupper(join('_', $topic)), new $classOfAction());
        Registry::process($request->headers->all(), $request->getContent());
        return response('🎉');
    }
}
