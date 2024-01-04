<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ShippingAddressController;
use Aws\Middleware;

Route::prefix('app')->group(function () {

    Route::controller('StaticDataController')->group(function () {
        Route::get(...array_fill(0, 2, 'settings'));
        Route::get(...array_fill(0, 2, 'session'));
        Route::get(...array_fill(0, 2, 'token'));
        Route::get(...array_fill(0, 2, 'socialMedia'));
        Route::get(...array_fill(0, 2, 'getCart'));
        Route::get(...array_fill(0, 2, 'getCustomer'));
        Route::get(...array_fill(0, 2, 'isMultyCurrency'));
        Route::get(...array_fill(0, 2, 'wishlists'));
        Route::get(...array_fill(0, 2, 'csrfToken'));
        Route::get(...array_fill(0, 2, 'test2'));
        Route::post(...array_fill(0, 2, 'flash'));
        Route::get('translate/{locale}', 'translate');
        Route::get('banner/{type}', 'banner');
    });
    Route::resource('countries',CountryController::class);
    // routes for static pages
    Route::group(['namespace'=>'Admin'],function(){
        Route::resource('pages',PageController::class);
        Route::resource('page-categories',PageCategoryController::class);
        Route::get('footer','PageCategoryController@listPages');
    });

    Route::post('confirm-location', [ShippingAddressController::class,'confirmLocation'])->name('confirm.location');
    Route::post('confirm-location-ajax', [ShippingAddressController::class,'confirmLocationAjax'])->name('confirm.location.ajax');
    //for maintenance mode
    Route::get('maintenance-mode', 'Web\WebController@maintenance_mode')->name('maintenance-mode');

    // route for similar products
    Route::get('similar-products/{id}', 'Web\WebController@getSimilarProducts');

    Route::get('sallaTest', SallaTestController::class);
    Route::get('shopifyTest', ShopifyTestController::class);

    Route::get('tester', function () {
        $address_id = new \stdClass();
        $address_id->contact_person_name = session('person_name');
        $address_id->phone = session('person_phone');
        $address_id->city = session('person_city');
        $address_id->address = session('person_address');

        $billing_address_id = new \stdClass();
        $billing_address_id->contact_person_name = session('person_name');
        $billing_address_id->phone = session('person_phone');
        $billing_address_id->city = session('person_city');
        $billing_address_id->address = session('person_address');
        return json_encode($address_id);
    });

    Route::group(['namespace' => 'Web', 'middleware' => ['maintenance_mode']], function () {
        Route::get('/', 'WebController@home')->name('home');

        Route::get('static-page/{id}', 'WebController@staticPage')->name('static.page');
        // Route::post('confirm-location', 'WebController@confirmLocation')->name('confirm.location');
        // Route::post('confirm-location-ajax', 'WebController@confirmLocationAjax')->name('confirm.location.ajax');

        Route::get('quick-view', 'WebController@quick_view')->name('quick-view');
        Route::get('searched-products', 'WebController@searched_products')->name('searched-products');
        Route::get('shop-cart', 'WebController@shop_cart')->name('shop-cart');
        Route::get('checkout-payment', 'WebController@checkout_payment')->name('checkout-payment');
        Route::get('checkout-complete', 'WebController@checkout_complete')->name('checkout-complete');
        Route::post('complete_order_as_guest', 'WebController@complete_order_as_guest')->name('complete_order_as_guest');
        Route::get('pay_to_partner', 'WebController@payToPartner')->name('pay_to_partner');

        Route::group(['middleware' => ['customer']], function () {
            Route::get('checkout-details', 'WebController@checkout_details')->name('checkout-details');
            Route::get('checkout-shipping', 'WebController@checkout_shipping')->name('checkout-shipping')->middleware(
                'customer'
            );
            Route::get('checkout-review', 'WebController@checkout_review')->name('checkout-review')->middleware('customer');
            Route::post('offline-payment-checkout-complete', 'WebController@offline_payment_checkout_complete')->name(
                'offline-payment-checkout-complete'
            )->middleware('customer');
            Route::get('order-placed', 'WebController@order_placed')->name('order-placed')->middleware('customer');
            Route::post('order_note', 'WebController@order_note')->name('order_note');
            Route::get('digital-product-download/{id}', 'WebController@digital_product_download')->name(
                'digital-product-download'
            )->middleware('customer');
            Route::get('submit-review/{id}', 'UserProfileController@submit_review')->name('submit-review');
            Route::post('review', 'ReviewController@store')->name('review.store');
            Route::get('deliveryman-review/{id}', 'ReviewController@delivery_man_review')->name('deliveryman-review');
            Route::post('submit-deliveryman-review', 'ReviewController@delivery_man_submit')->name(
                'submit-deliveryman-review'
            );
        });

        //wallet payment
        Route::get('checkout-complete-wallet', 'WebController@checkout_complete_wallet')->name('checkout-complete-wallet');

        Route::post('subscription', 'WebController@subscription')->name('subscription');
        Route::get('search-shop', 'WebController@search_shop')->name('search-shop');

        Route::get('categories', 'WebController@all_categories')->name('categories');
        Route::get('orderedCategories', 'WebController@categories')->name('orderedCategories');
        Route::get('orderedCategoriesForHome', 'WebController@categories_home')->name('orderedCategoriesForHome');
        Route::get('CategoriesWithProductCount', 'WebController@categories_with_product_count')->name('CategoriesWithProductCount');
        Route::get('BrandsWithProductCount', 'WebController@brands_with_product_count')->name('BrandsWithProductCount');
        Route::get('level1Products', 'WebController@level1_products')->name('level1Products');
        Route::get('feedData', 'WebController@feedData')->name('feedData');
        Route::get('category-ajax/{id}', 'WebController@categories_by_category')->name('category-ajax');

        Route::get('brands', 'WebController@all_brands')->name('brands');
        Route::get('sellers', 'WebController@all_sellers')->name('sellers');
        Route::get('seller-profile/{id}', 'WebController@seller_profile')->name('seller-profile');

        Route::get('flash-deals/{id}', 'WebController@flash_deals')->name('flash-deals');
        Route::get('terms', 'WebController@termsandCondition')->name('terms');
        Route::get('privacy-policy', 'WebController@privacy_policy')->name('privacy-policy');
        Route::get('refund-policy', 'WebController@refund_policy')->name('refund-policy');
        Route::get('return-policy', 'WebController@return_policy')->name('return-policy');
        Route::get('cancellation-policy', 'WebController@cancellation_policy')->name('cancellation-policy');

        Route::get('product/{slug}', 'WebController@product')->name('product');
        Route::get('products', 'WebController@products')->name('products');
        Route::get('orderDetails', 'WebController@orderdetails')->name('orderdetails');
        Route::get('discounted-products', 'WebController@discounted_products')->name('discounted-products');

        Route::post('review-list-product', 'WebController@review_list_product')->name('review-list-product');
        //Chat with seller from product details
        Route::get('chat-for-product', 'WebController@chat_for_product')->name('chat-for-product');

        Route::get('wishlists', 'WebController@viewWishlist')->name('wishlists')->middleware('customer');
        Route::post('store-wishlist', 'WebController@storeWishlist')->name('store-wishlist');
        Route::post('delete-wishlist', 'WebController@deleteWishlist')->name('delete-wishlist');

        Route::post('currency', 'CurrencyController@changeCurrency')->name('currency.change');

        Route::get('about-us', 'WebController@about_us')->name('about-us');

        //profile Route
        Route::get('user-account', 'UserProfileController@user_account')->name('user-account');
        Route::post('user-account-update', 'UserProfileController@user_update')->name('user-update');
        Route::post('user-account-picture', 'UserProfileController@user_picture')->name('user-picture');
        Route::get('account-address', 'UserProfileController@account_address')->name('account-address');
        Route::post('account-address-store', 'UserProfileController@address_store')->name('address-store');
        Route::get('account-address-delete', 'UserProfileController@address_delete')->name('address-delete');
        ROute::get('account-address-edit/{id}', 'UserProfileController@address_edit')->name('address-edit');
        Route::post('account-address-update', 'UserProfileController@address_update')->name('address-update');
        Route::get('account-payment', 'UserProfileController@account_payment')->name('account-payment');
        Route::get('account-oder', 'UserProfileController@account_oder')->name('account-oder');
        Route::get('account-order-details', 'UserProfileController@account_order_details')->name(
            'account-order-details'
        )->middleware('customer');
        Route::get('generate-invoice/{id}', 'UserProfileController@generate_invoice')->name('generate-invoice');
        Route::get('account-wishlist', 'UserProfileController@account_wishlist')->name(
            'account-wishlist'
        ); //add to card not work
        Route::get('refund-request/{id}', 'UserProfileController@refund_request')->name('refund-request');
        Route::get('refund-details/{id}', 'UserProfileController@refund_details')->name('refund-details');
        Route::post('refund-store', 'UserProfileController@store_refund')->name('refund-store');
        Route::get('account-tickets', 'UserProfileController@account_tickets')->name('account-tickets');
        Route::get('order-cancel/{id}', 'UserProfileController@order_cancel')->name('order-cancel');
        Route::post('ticket-submit', 'UserProfileController@ticket_submit')->name('ticket-submit');
        Route::get('account-delete/{id}', 'UserProfileController@account_delete')->name('account-delete');
        // Chatting start
        Route::get('chat/{type}', 'ChattingController@chat_list')->name('chat');
        Route::get('messages', 'ChattingController@messages')->name('messages');
        Route::post('messages-store', 'ChattingController@messages_store')->name('messages_store');
        // chatting end

        //Support Ticket
        Route::group(['prefix' => 'support-ticket', 'as' => 'support-ticket.'], function () {
            Route::get('{id}', 'UserProfileController@single_ticket')->name('index');
            Route::post('{id}', 'UserProfileController@comment_submit')->name('comment');
            Route::get('delete/{id}', 'UserProfileController@support_ticket_delete')->name('delete');
            Route::get('close/{id}', 'UserProfileController@support_ticket_close')->name('close');
        });

        Route::get('account-transaction', 'UserProfileController@account_transaction')->name('account-transaction');
        Route::get('account-wallet-history', 'UserProfileController@account_wallet_history')->name(
            'account-wallet-history'
        );

        Route::get('wallet', 'UserWalletController@index')->name('wallet');
        Route::get('loyalty', 'UserLoyaltyController@index')->name('loyalty');
        Route::post('loyalty-exchange-currency', 'UserLoyaltyController@loyalty_exchange_currency')->name(
            'loyalty-exchange-currency'
        );

        Route::group(['prefix' => 'track-order', 'as' => 'track-order.'], function () {
            Route::get('', 'UserProfileController@track_order')->name('index');
            Route::get('result-view', 'UserProfileController@track_order_result')->name('result-view');
            Route::get('last', 'UserProfileController@track_last_order')->name('last');
            Route::any('result', 'UserProfileController@track_order_result')->name('result');
        });
        //FAQ route
        Route::get('helpTopic', 'WebController@helpTopic')->name('helpTopic');
        //Contacts
        Route::get('contacts', 'WebController@contacts')->name('contacts');

        //sellerShop
        Route::get('shopView/{id}', 'WebController@seller_shop')->name('shopView');
        Route::post('shopView/{id}', 'WebController@seller_shop_product');

        //top Rated
        Route::get('top-rated', 'WebController@top_rated')->name('topRated');
        Route::get('best-sell', 'WebController@best_sell')->name('bestSell');
        Route::get('new-product', 'WebController@new_product')->name('newProduct');

        Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
            Route::post('store', 'WebController@contact_store')->name('store');
            Route::get('/code/captcha/{tmp}', 'WebController@captcha')->name('default-captcha');
        });
    });

    //Seller shop apply
    Route::group(['prefix' => 'shop', 'as' => 'shop.', 'namespace' => 'Seller\Auth'], function () {
        Route::get('apply', 'RegisterController@create')->name('apply');
        Route::post('apply', 'RegisterController@store');
    });

    //check done
    Route::group(['prefix' => 'cart', 'as' => 'cart.', 'namespace' => 'Web'], function () {
        Route::post('variant_price', 'CartController@variant_price')->name('variant_price');
        Route::post('add', 'CartController@addToCart')->name('add');
        Route::post('get-stock', 'CartController@getStock')->name('getStock');
        Route::post('remove', 'CartController@removeFromCart')->name('remove');
        Route::post('nav-cart-items', 'CartController@updateNavCart')->name('nav-cart');
        Route::post('updateQuantity', 'CartController@updateQuantity')->name('updateQuantity');
    });

    //Seller shop apply
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'namespace' => 'Web'], function () {
        Route::post('apply', 'CouponController@apply')->name('apply');
        Route::get('list', 'CouponController@list')->name('list');
    });
    //check done

    Route::get('pay-telr-vie', 'TelrController@view')->name('pay-telr-view');
    Route::post('pay-telr', 'TelrController@store')->name('pay-telr');
    Route::get('/handle-payment/success', 'TelrController@success');
    Route::get('/handle-payment/cancel', 'TelrController@cancel');
    Route::get('/handle-payment/declined', 'TelrController@declined');


    // SSLCOMMERZ Start
    /*Route::get('/example1', 'SslCommerzPaymentController@exampleEasyCheckout');
Route::get('/example2', 'SslCommerzPaymentController@exampleHostedCheckout');*/
    Route::post('pay-ssl', 'SslCommerzPaymentController@index');
    Route::post('/success', 'SslCommerzPaymentController@success')->name('ssl-success');
    Route::post('/fail', 'SslCommerzPaymentController@fail')->name('ssl-fail');
    Route::post('/cancel', 'SslCommerzPaymentController@cancel')->name('ssl-cancel');
    Route::post('/ipn', 'SslCommerzPaymentController@ipn')->name('ssl-ipn');
    //SSLCOMMERZ END

    /*paypal*/
    /*Route::get('/paypal', function (){return view('paypal-test');})->name('paypal');*/
    Route::post('pay-paypal', 'PaypalPaymentController@payWithpaypal')->name('pay-paypal');
    Route::get('paypal-status', 'PaypalPaymentController@getPaymentStatus')->name('paypal-status');
    Route::get('paypal-success', 'PaypalPaymentController@success')->name('paypal-success');
    Route::get('paypal-fail', 'PaypalPaymentController@fail')->name('paypal-fail');
    /*paypal*/

    /*Route::get('stripe', function (){
return view('stripe-test');
});*/
    Route::get('pay-stripe', 'StripePaymentController@payment_process_3d')->name('pay-stripe');
    Route::get('pay-stripe/success', 'StripePaymentController@success')->name('pay-stripe.success');
    Route::get('pay-stripe/fail', 'StripePaymentController@success')->name('pay-stripe.fail');

    // Get Route For Show Payment razorpay Form
    Route::get('paywithrazorpay', 'RazorPayController@payWithRazorpay')->name('paywithrazorpay');
    Route::post('payment-razor', 'RazorPayController@payment')->name('payment-razor');
    Route::post('payment-razor/payment2', 'RazorPayController@payment_mobile')->name('payment-razor.payment2');
    Route::get('payment-razor/success', 'RazorPayController@success')->name('payment-razor.success');
    Route::get('payment-razor/fail', 'RazorPayController@success')->name('payment-razor.fail');

    Route::get('payment-success', 'Customer\PaymentController@success')->name('payment-success');
    Route::get('payment-fail', 'Customer\PaymentController@fail')->name('payment-fail');


    //senang pay
    Route::match(['get', 'post'], '/return-senang-pay', 'SenangPayController@return_senang_pay')->name('return-senang-pay');

    //paystack
    Route::post('/paystack-pay', 'PaystackController@redirectToGateway')->name('paystack-pay');
    Route::get('/paystack-callback', 'PaystackController@handleGatewayCallback')->name('paystack-callback');
    Route::get('/paystack', function () {
        return view('paystack');
    });

    // paymob
    Route::post('/paymob-credit', 'PaymobController@credit')->name('paymob-credit');
    Route::get('/paymob-callback', 'PaymobController@callback')->name('paymob-callback');


    //paytabs
    Route::any('/paytabs-payment', 'PaytabsController@payment')->name('paytabs-payment');
    Route::any('/paytabs-response', 'PaytabsController@callback_response')->name('paytabs-response');

    //bkash
    Route::group(['prefix' => 'bkash'], function () {
        // Payment Routes for bKash
        Route::get('make-payment', 'BkashPaymentController@make_tokenize_payment')->name('bkash-make-payment');
        Route::any('callback', 'BkashPaymentController@callback')->name('bkash-callback');

        // Refund Routes for bKash
        Route::get('refund', 'BkashRefundController@index')->name('bkash-refund');
        Route::post('refund', 'BkashRefundController@refund')->name('bkash-refund');
    });

    //fawry
    Route::get('/fawry', 'FawryPaymentController@index')->name('fawry');
    Route::any('/fawry-payment', 'FawryPaymentController@payment')->name('fawry-payment');

    // The callback url after a payment
    Route::get('mercadopago/home', 'MercadoPagoController@index')->name('mercadopago.index');
    Route::post('mercadopago/make-payment', 'MercadoPagoController@make_payment')->name('mercadopago.make_payment');
    Route::get('mercadopago/get-user', 'MercadoPagoController@get_test_user')->name('mercadopago.get-user');

    // The route that the button calls to initialize payment
    Route::post('/flutterwave-pay', 'FlutterwaveController@initialize')->name('flutterwave_pay');
    // The callback url after a payment
    Route::get('/rave/callback', 'FlutterwaveController@callback')->name('flutterwave_callback');

    // The callback url after a payment PAYTM
    Route::get('paytm-payment', 'PaytmController@payment')->name('paytm-payment');
    Route::any('paytm-response', 'PaytmController@callback')->name('paytm-response');

    // The callback url after a payment LIQPAY
    Route::get('liqpay-payment', 'LiqPayController@payment')->name('liqpay-payment');
    Route::any('liqpay-callback', 'LiqPayController@callback')->name('liqpay-callback');

    Route::get('/test', function () {
        return view('welcome');
    });

    // Webhooks
    //Route::prefix('webhooks')->group(function () {
    //    Route::get('/configure/{id}', [\App\Http\Controllers\WebhookController::class, 'createWebhooks']);
    //    Route::get('/delete/{id}', [\App\Http\Controllers\WebhookController::class, 'deleteWebhooks']);
    //    Route::any('/product/created', [\App\Http\Controllers\WebhookController::class, 'productCreated']);
    //    Route::any('/product/updated', [\App\Http\Controllers\WebhookController::class, 'productUpdated']);
    //    Route::any('/product/deleted', [\App\Http\Controllers\WebhookController::class, 'productDeleted']);
    //});
});
