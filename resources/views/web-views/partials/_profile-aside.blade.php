<div class="sidebarR col-lg-3 col-md-3 pr-lg-3 pr-xl-4">
    <!--Price Sidebar-->
    <div class="__customer-sidebar" id="shop-sidebar">
        <div>
            <!-- Filter by price-->
            <div class="widget-title">
                <a class="{{Request::is('account-oder*') || Request::is('account-order-details*') ? 'active-menu' :''}}" href="{{route('account-oder') }} ">{{\App\CPU\translate('my_order')}}</a>
            </div>
        </div>
        @php
            $wallet_status = App\CPU\Helpers::get_business_settings('wallet_status');
            $loyalty_point_status = App\CPU\Helpers::get_business_settings('loyalty_point_status');
        @endphp
        @if ($wallet_status == 1)
            <div>
                <!-- Filter by price-->
                <div class="widget-title">
                    <a class="{{Request::is('wallet')?'active-menu':''}}" href="{{route('wallet') }} ">{{\App\CPU\translate('my_wallet')}} </a>
                </div>
            </div>
        @endif
        @if ($loyalty_point_status == 1)
            <div>
                <!-- Filter by price-->
                <div class="widget-title">
                    <a class="{{Request::is('loyalty')?'active-menu':''}}" href="{{route('loyalty') }} ">{{\App\CPU\translate('my_loyalty_point')}}</a>
                </div>
            </div>
        @endif
        <div>
            <!-- Filter by price-->
            <div class="widget-title">
                <a class="{{Request::is('track-order*')?'active-menu':''}}" href="{{route('track-order.index') }} ">{{\App\CPU\translate('track_your_order')}}</a>
            </div>
        </div>
        <div>
            <!-- Filter by price-->
            <div class="widget-title">
                <a class="{{Request::is('wishlists*')?'active-menu':''}}" href="{{route('wishlists')}}"> {{\App\CPU\translate('wish_list')}}  </a>
            </div>
        </div>

        {{--to do--}}
        @php($business_mode=\App\CPU\Helpers::get_business_settings('business_mode'))
        @if ($business_mode == 'multi')
            <div>
                <!-- Filter by price-->
                <div class="widget-title">
                    <a class="{{Request::is('chat/seller')?'active-menu':''}}" href="{{route('chat', ['type' => 'seller'])}}">{{\App\CPU\translate('chat_with_seller')}}</a>
                </div>
            </div>
            <div>
                <div class="widget-title">
                    <a class="{{Request::is('chat/delivery-man')?'active-menu':''}}" href="{{route('chat', ['type' => 'delivery-man'])}}">{{\App\CPU\translate('chat_with_delivery-man')}}</a>
                </div>
            </div>
        @endif

        <div>
            <!-- Filter by price-->
            <div class="widget-title">
                <a class="{{Request::is('user-account*')?'active-menu':''}}" href="{{route('user-account')}}">
                    {{\App\CPU\translate('profile_info')}}
                </a>
            </div>
        </div>
        <div>
            <!-- Filter by price-->
            <div class="widget-title">
                <a class="{{Request::is('account-address*')?'active-menu':''}}"
                    href="{{ route('account-address') }}">{{\App\CPU\translate('address')}} </a>
            </div>
        </div>
        <div>
            <!-- Filter by price-->
            <div class="widget-title">
                <a class="{{(Request::is('account-ticket*') || Request::is('support-ticket*'))?'active-menu':''}}"
                    href="{{ route('account-tickets') }}">{{\App\CPU\translate('support_ticket')}}</a>
            </div>
        </div>

    </div>
</div>


















