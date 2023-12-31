<?php

namespace App\Salla\Actions\Order;

use App\Salla\Actions\BaseAction;

/**
 * @property string merchant example 674390266
 * @property string created_at example "2021-06-02 22:17:06"
 * @property string event example "order.status.updated"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/OrdersUpdateStatusWebhookResponse
 */
class StatusUpdated extends BaseAction
{
    public function handle()
    {
        // you can do whatever you want
    }
}
