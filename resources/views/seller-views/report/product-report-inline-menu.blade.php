<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('seller/report/all-product') ?'active':'' }}"><a href="{{route('seller.report.all-product')}}">{{\App\CPU\translate('All_Products')}}</a></li>
        <li class="{{ Request::is('seller/report/stock-product-report') ?'active':'' }}"><a href="{{route('seller.report.stock-product-report')}}">{{\App\CPU\translate('Products_Stock')}}</a></li>
    </ul>
</div>
