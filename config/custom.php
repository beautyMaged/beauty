<?php


return  [
    'webhooks' => [
        'products/create' => 'product/created', //When products are created
        'products/update' => 'product/updated', //When products are updated
        'products/delete' => 'product/deleted', //When products are deleted
        'orders/create' => 'orders/created', //When orders are created
    ]

];
