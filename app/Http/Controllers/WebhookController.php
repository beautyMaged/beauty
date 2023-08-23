<?php

namespace App\Http\Controllers;

use App\Helpers\Shopify;
use App\Jobs\CreateDraftOrderJob;
use App\Jobs\CreateWebhooks;
use App\Jobs\DeleteWebhooks;
use App\Jobs\products\CreateOrUpdateJob;
use App\Jobs\products\DeleteJob;
use App\Jobs\products\UpdateJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function createWebhooks($id)
    {
        $api = DB::table('shop_rest_api')->where('id', $id)->get();
        $api = $api->all()[0];
        $shopify = new Shopify(
            $api->host,
            $api->access_token,
            $api->api_key,
            $api->api_secret
        );
        dispatch(new CreateWebhooks($shopify));
        return redirect()->back();
    }

    public function deleteWebhooks($id)
    {
        $api = DB::table('shop_rest_api')->where('id', $id)->get();
        $api = $api->all()[0];
        $shopify = new Shopify(
            $api->host,
            $api->access_token,
            $api->api_key,
            $api->api_secret
        );
        dispatch(new DeleteWebhooks($shopify));
        return redirect()->back();
    }

    public function productCreated(Request $request)
    {
        Log::info('Recieved webhook for event product created');
        Log::info($request->all());
        dispatch(new CreateOrUpdateJob($request->all()));
        return response()->json(['status' => true], 200);
    }

    public function productUpdated(Request $request)
    {
        Log::info('Recieved webhook for event product updated');
        Log::info($request->all());
        dispatch(new CreateOrUpdateJob($request->all()));
        return response()->json(['status' => true], 200);
    }

    public function productDeleted(Request $request)
    {
        Log::info('Recieved webhook for event product deleted');
        Log::info($request->all());
        dispatch(new DeleteJob($request->all()));
        return response()->json(['status' => true], 200);
    }

    public function orderCreated(Request $request){
        Log::info('Recieved webhook for event order created');
        Log::info($request->all());
        dispatch(new CreateDraftOrderJob($request->all()));
        return response()->json(['status' => true], 200);
    }
}
