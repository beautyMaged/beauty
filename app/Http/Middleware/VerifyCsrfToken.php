<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/pay-via-ajax', '/success', '/cancel', '/fail', '/ipn', '/bkash/*',
        '/paytabs-response', '/customer/choose-shipping-address', '/system_settings',
        '/paytm*', '/admin/webhooks*',
        '/app/flash',
        '/seller/salla/webhook',
        '/seller/zid/webhook',
        '/seller/shopify/webhook',
        '/seller/partner',
        '/seller/register',
        '/app/customer/*',
        '/app/confirm-location', '/app/confirm-location-ajax',
        '/app/countries',
        '/app/*',
        '/admin/category-commission/*',
        '/admin/product-commission/*'
    ];
}
