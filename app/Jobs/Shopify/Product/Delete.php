<?php

namespace App\Jobs\Shopify\Product;

use App\Model\Product;
use Illuminate\Foundation\Bus\Dispatchable;

class Delete
{
    use Dispatchable;

    private $seller;
    private $product;

    public function __construct($seller, $product)
    {
        $this->seller = $seller;
        $this->product = $product;
    }
    public function handle()
    {
        Product::where(['user_id' => $this->seller, 'remote_id' => $this->product['id']])->delete();
    }
}
