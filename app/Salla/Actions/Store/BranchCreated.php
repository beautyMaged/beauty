<?php

namespace App\Salla\Actions\Store;

use App\Salla\Actions\BaseAction;

/**
 * @property string merchant example "1029864349"
 * @property string created_at example "Wed Jun 30 2021 12:16:25 GMT+030"
 * @property string event example "store.branch.created"
 * @property array data @see https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/StoreWebhookResponse
 */
class BranchCreated extends BaseAction
{
    public function handle()
    {
        // you can do whatever you want
    }
}
