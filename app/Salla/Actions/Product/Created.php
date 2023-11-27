<?php

namespace App\Salla\Actions\Product;

use App\Model\Seller;
use App\Model\SallaOauthToken;
use App\Salla\Actions\BaseAction;
use App\Jobs\Salla\Product\UpdateOrCreate;

/**
 * @property string merchant example "1029864349"
 * @property string created_at example "Wed Jun 30 2021 12:16:25 GMT+030"
 * @property string event example "product.created"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/ProductsWebhookResponse
 */
class Created extends BaseAction
{
    public function handle()
    {
        $seller = SallaOauthToken::where('merchant', $this->merchant)->first()->shop->seller;
        UpdateOrCreate::dispatchSync($seller->id, [$this->data]);
    }
}
