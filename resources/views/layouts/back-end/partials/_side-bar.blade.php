<div id="sidebarMain" class="d-none">
    <aside
        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="bg-white js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                    <a class="navbar-brand" href="{{route('admin.dashboard.index')}}" aria-label="Front">
                        <img onerror="this.src='{{asset('assets/back-end/img/900x400/img1.jpg')}}'"
                             class="navbar-brand-logo-mini for-web-logo max-h-30"
                             src="{{asset("storage/company/$e_commerce_logo")}}" alt="Logo">
                    </a>
                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="d-none js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->

                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                           data-placement="right" title="" data-original-title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                           data-template="<div class=&quot;tooltip d-none d-sm-block&quot; role=&quot;tooltip&quot;><div class=&quot;arrow&quot;></div><div class=&quot;tooltip-inner&quot;></div></div>"
                           data-toggle="tooltip" data-placement="right" title="" data-original-title="Expand"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content">
                    <!-- Search Form -->
                    <div class="sidebar--search-form pb-3 pt-4">
                        <div class="search--form-group">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control form--control" id="search-bar-input"
                                   placeholder="{{\App\CPU\translate('search_menu')}}...">
                        </div>
                    </div>
                <!-- <div class="input-group">
                        <diV class="card search-card" id="search-card"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                            <div class="card-body search-result-box" id="search-result-box">

                            </div>
                        </diV>
                    </div> -->
                    <!-- End Search Form -->
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/dashboard')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               title="{{\App\CPU\translate('Dashboard')}}"
                               href="{{route('admin.dashboard.index')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Dashboard')}}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/employee*') || Request::is('admin/custom-role*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                               href="javascript:" title="{{\App\CPU\translate('employees')}}">
                                <i class="tio-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('employees')}}
                            </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/employee*') || Request::is('admin/custom-role*')?'block':'none'}}">
                                <li class="navbar-vertical-aside-has-menu {{Request::is('admin/custom-role*')?'active':''}}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                       href="{{route('admin.custom-role.create')}}"
                                       title="{{\App\CPU\translate('Employee_Role_Setup')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Employee_Role_Setup')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{(Request::is('admin/employee/list') || Request::is('admin/employee/add-new') || Request::is('admin/employee/update*'))?'active':''}}">
                                    <a class="nav-link" href="{{route('admin.employee.list')}}"
                                       title="{{\App\CPU\translate('Employees')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('Employees')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- End Dashboards -->

                    {{--                        <!-- POS -->--}}
                    {{--                        @if (\App\CPU\Helpers::module_permission_check('pos_management'))--}}
                    {{--                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/pos*')?'active':''}}">--}}
                    {{--                                <a class="js-navbar-vertical-aside-menu-link nav-link"--}}
                    {{--                                   title="{{\App\CPU\translate('POS')}}" href="{{route('admin.pos.index')}}">--}}
                    {{--                                    <i class="tio-shopping nav-icon"></i>--}}
                    {{--                                    <span--}}
                    {{--                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('POS')}}</span>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                        @endif--}}
                    <!-- End POS -->

                        <!-- Order Management -->
                        @if(\App\CPU\Helpers::module_permission_check('order_management'))
                            <li class="nav-item {{Request::is('admin/orders*')?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('order_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <!-- Order -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/orders*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:void(0)" title="{{\App\CPU\translate('orders')}}">
                                    <i class="tio-shopping-cart-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('orders')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/order*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/orders/list/all')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.orders.list',['all'])}}"
                                           title="{{\App\CPU\translate('All')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('All')}}
                                                <span class="badge badge-soft-info badge-pill ml-1">
                                                    {{\App\Model\Order::count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/pending')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['pending'])}}"
                                           title="{{\App\CPU\translate('pending')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('pending')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/confirmed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['confirmed'])}}"
                                           title="{{\App\CPU\translate('confirmed')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('confirmed')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'confirmed'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/processing')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['processing'])}}"
                                           title="{{\App\CPU\translate('Packaging')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('Packaging')}}
                                                <span class="badge badge-soft-warning badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'processing'])->count()}}
                                                </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/out_for_delivery')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['out_for_delivery'])}}"
                                           title="{{\App\CPU\translate('out_for_delivery')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('out_for_delivery')}}
                                                <span class="badge badge-soft-warning badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'out_for_delivery'])->count()}}
                                                </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/delivered')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['delivered'])}}"
                                           title="{{\App\CPU\translate('delivered')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('delivered')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'delivered'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/returned')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['returned'])}}"
                                           title="{{\App\CPU\translate('returned')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('returned')}}
                                                    <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\Order::where('order_status','returned')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/orders/list/failed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['failed'])}}"
                                           title="{{\App\CPU\translate('failed')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('Failed_to_Deliver')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'failed'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/orders/list/canceled')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['canceled'])}}"
                                           title="{{\App\CPU\translate('canceled')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('canceled')}}
                                                    <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\Order::where(['order_status'=>'canceled'])->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/refund-section/refund/*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('Refund_Requests')}}">
                                    <i class="tio-receipt-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Refund_Requests')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/refund-section/refund*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/pending')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['pending'])}}"
                                           title="{{\App\CPU\translate('pending')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                              {{\App\CPU\translate('pending')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','pending')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/approved')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['approved'])}}"
                                           title="{{\App\CPU\translate('approved')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{\App\CPU\translate('approved')}}
                                                <span class="badge badge-soft-info badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','approved')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/refunded')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['refunded'])}}"
                                           title="{{\App\CPU\translate('refunded')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{\App\CPU\translate('refunded')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','refunded')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/refund-section/refund/list/rejected')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.refund-section.refund.list',['rejected'])}}"
                                           title="{{\App\CPU\translate('rejected')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{\App\CPU\translate('rejected')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                    {{\App\Model\RefundRequest::where('status','rejected')->count()}}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    <!--Order Management Ends-->

                        <!--Product Management -->
                        @if(\App\CPU\Helpers::module_permission_check('product_management'))
                            <li class="nav-item {{(Request::is('admin/brand*') || Request::is('admin/category*') || Request::is('admin/sub*') || Request::is('admin/attribute*') || Request::is('admin/product*'))?'scroll-here':''}}">
                                <small class="nav-subtitle"
                                       title="">{{\App\CPU\translate('product_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/category*') ||Request::is('admin/sub*')) ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('Category_Setup')}}">
                                    <i class="tio-filter-list nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Category_Setup')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('admin/category*') ||Request::is('admin/sub*'))?'block':''}}">
                                    <li class="nav-item {{Request::is('admin/category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.category.view')}}"
                                           title="{{\App\CPU\translate('Categories')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('Categories')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/sub-category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.sub-category.view')}}"
                                           title="{{\App\CPU\translate('Sub_Categories')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('Sub_Categories')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/sub-sub-category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.sub-sub-category.view')}}"
                                           title="{{\App\CPU\translate('Sub_Sub_Categories')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{\App\CPU\translate('Sub_Sub_Categories')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/brand*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('brands')}}">
                                    <i class="tio-star nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('brands')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/brand*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/brand/add-new')?'active':''}}"
                                        title="{{\App\CPU\translate('add_new')}}">
                                        <a class="nav-link " href="{{route('admin.brand.add-new')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/brand/list')?'active':''}}"
                                        title="{{\App\CPU\translate('List')}}">
                                        <a class="nav-link " href="{{route('admin.brand.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/attribute*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.attribute.view')}}"
                                   title="{{\App\CPU\translate('Product_Attributes')}}">
                                    <i class="tio-category-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Product_Attributes')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/product/list/in_house') || Request::is('admin/product/bulk-import') || (Request::is('admin/product/add-new')) || (Request::is('admin/product/view/*')) || (Request::is('admin/product/barcode/*')))?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('InHouse Products')}}">
                                    <i class="tio-shop nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        <span class="text-truncate">{{\App\CPU\translate('InHouse Products')}}</span>
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('admin/product/list/in_house') || (Request::is('admin/product/stock-limit-list/in_house')) || (Request::is('admin/product/bulk-import')) || (Request::is('admin/product/add-new')) || (Request::is('admin/product/view/*')) || (Request::is('admin/product/barcode/*')))?'block':''}}">
                                    <li class="nav-item {{(Request::is('admin/product/list/in_house') || (Request::is('admin/product/add-new')) || (Request::is('admin/product/view/*')) || (Request::is('admin/product/barcode/*')))?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.list',['in_house', ''])}}"
                                           title="{{\App\CPU\translate('Products')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/product/bulk-import')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.bulk-import')}}"
                                           title="{{\App\CPU\translate('bulk_import')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('bulk_import')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/product/list/seller*')||Request::is('admin/product/updated-product-list')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:"
                                   title="{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Products')}}">
                                    <i class="tio-airdrop nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Seller Products')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/product/list/seller*')||Request::is('admin/product/updated-product-list')?'block':''}}">

                                    @if (\App\CPU\Helpers::get_business_settings('product_wise_shipping_cost_approval')==1)
                                        <li class="nav-item {{Request::is('admin/product/updated-product-list')?'active':''}}">
                                            <a class="nav-link" title="{{\App\CPU\translate('updated_products')}}"
                                               href="{{route('admin.product.updated-product-list')}}">
                                                <span class="tio-circle nav-indicator-icon"></span>
                                                <span
                                                    class="text-truncate">{{\App\CPU\translate('updated_products')}} </span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=0')==1?'active':''}}">
                                        <a class="nav-link"
                                           title="{{\App\CPU\translate('New')}} {{\App\CPU\translate('Products')}}"
                                           href="{{route('admin.product.list',['seller', 'status'=>'0'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{\App\CPU\translate('New')}} {{\App\CPU\translate('Products')}} </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=1')==1?'active':''}}">
                                        <a class="nav-link"
                                           title="{{\App\CPU\translate('Approved')}} {{\App\CPU\translate('Products')}}"
                                           href="{{route('admin.product.list',['seller', 'status'=>'1'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{\App\CPU\translate('Approved')}} {{\App\CPU\translate('Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{str_contains(url()->current().'?status='.request()->get('status'),'/admin/product/list/seller?status=2')==1?'active':''}}">
                                        <a class="nav-link"
                                           title="{{\App\CPU\translate('Denied')}} {{\App\CPU\translate('Products')}}"
                                           href="{{route('admin.product.list',['seller', 'status'=>'2'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="text-truncate">{{\App\CPU\translate('Denied')}} {{\App\CPU\translate('Products')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    <!--Product Management Ends-->

                        @if(\App\CPU\Helpers::module_permission_check('promotion_management'))
                        <!--promotion management start-->
                            <li class="nav-item {{(Request::is('admin/banner*') || Request::is('admin/home-banner-settings*') || (Request::is('admin/coupon*')) || (Request::is('admin/notification*')) || (Request::is('admin/deal*')))?'scroll-here':''}}">
                                <small class="nav-subtitle"
                                       title="">{{\App\CPU\translate('promotion_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/banner*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.banner.list')}}" title="{{\App\CPU\translate('banners')}}">
                                    <i class="tio-photo-square-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('banners')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/home-banner-settings*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.home-banner-settings.list')}}" title="إعدادات البانر الرئيسي">
                                    <i class="tio-photo-square-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">إعدادات البانر الرئيسي</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/coupon*') || Request::is('admin/deal*'))?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('Offers_&_Deals')}}">
                                    <i class="tio-users-switch nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Offers_&_Deals')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('admin/coupon*') || Request::is('admin/deal*'))?'block':'none'}}">
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/coupon*')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.coupon.add-new')}}"
                                           title="{{\App\CPU\translate('coupon')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('coupon')}}</span>
                                        </a>
                                    </li>
                                    <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/flash') || (Request::is('admin/deal/update*')))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.deal.flash')}}"
                                           title="{{\App\CPU\translate('Flash_Deals')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Flash_Deals')}}</span>
                                        </a>
                                    </li>
                                    <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/day') || (Request::is('admin/deal/day-update*')))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.deal.day')}}"
                                           title="{{\App\CPU\translate('deal_of_the_day')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('deal_of_the_day')}}
                                        </span>
                                        </a>
                                    </li>
                                    <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/deal/feature') || Request::is('admin/deal/edit*'))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.deal.feature')}}"
                                           title="{{\App\CPU\translate('Featured_Deal')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('Featured_Deal')}}
                                        </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/budget-filter*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.budget-filter.view')}}"
                                   title="التسوق حسب الميزانية">
                                    <i class="tio-category-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">التسوق حسب الميزانية</span>
                                </a>
                            </li>


                            {{--                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/notification*')?'active':''}}">--}}
                            {{--                            <a class="js-navbar-vertical-aside-menu-link nav-link"--}}
                            {{--                               href="{{route('admin.notification.add-new')}}"--}}
                            {{--                               title="{{\App\CPU\translate('Push_Notification')}}">--}}
                            {{--                                <i class="tio-notifications-on-outlined nav-icon"></i>--}}
                            {{--                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">--}}
                            {{--                                    {{\App\CPU\translate('Push_Notification')}}--}}
                            {{--                                </span>--}}
                            {{--                            </a>--}}
                            {{--                        </li>--}}
                            {{--                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/announcement')?'active':''}}">--}}
                            {{--                            <a class="js-navbar-vertical-aside-menu-link nav-link"--}}
                            {{--                               href="{{route('admin.business-settings.announcement')}}"--}}
                            {{--                               title="{{\App\CPU\translate('announcement')}}">--}}
                            {{--                                <i class="tio-mic-outlined nav-icon"></i>--}}
                            {{--                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">--}}
                            {{--                                    {{\App\CPU\translate('announcement')}}--}}
                            {{--                                </span>--}}
                            {{--                            </a>--}}
                            {{--                        </li>--}}
                        <!--promotion management end-->
                        @endif

                    <!-- end refund section -->
                        @if(\App\CPU\Helpers::module_permission_check('support_section'))
                            <li class="nav-item {{(Request::is('admin/support-ticket*') || Request::is('admin/contact*'))?'scroll-here':''}}">
                                <small class="nav-subtitle"
                                       title="">{{\App\CPU\translate('help_&_support_section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/contact*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.contact.list')}}" title="{{\App\CPU\translate('messages')}}">
                                    <i class="tio-messages nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                <span class="position-relative">
                                    {{\App\CPU\translate('messages')}}
                                    @php($message=\App\Model\Contact::where('seen',0)->count())
                                    @if($message!=0)
                                        <span
                                            class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                    @endif
                                </span>
                            </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/support-ticket*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.support-ticket.view')}}"
                                   title="{{\App\CPU\translate('Support_Ticket')}}">
                                    <i class="tio-chat nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                <span class="position-relative">
                                    {{\App\CPU\translate('Support_Ticket')}}
                                    @if(\App\Model\SupportTicket::where('status','open')->count()>0)
                                        <span
                                            class="btn-status btn-xs-status btn-status-danger position-absolute top-0 menu-status"></span>
                                    @endif
                                </span>
                            </span>
                                </a>
                            </li>
                        @endif
                    <!--support section ends here-->

                        <!--Reports & Analytics section-->
                        @if(\App\CPU\Helpers::module_permission_check('report'))
                            <li class="nav-item {{(Request::is('admin/report/earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/list') || Request::is('admin/refund-section/refund-list') || Request::is('admin/stock/product-in-wishlist') || Request::is('admin/reviews*') || Request::is('admin/stock/product-stock')) ? 'scroll-here':''}}">
                                <small class="nav-subtitle" title="">
                                    {{\App\CPU\translate('Reports')}} & {{\App\CPU\translate('Analysis')}}
                                </small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list')) ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('Sales_&_Transaction_Report')}}">
                                    <i class="tio-chart-bar-4 nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('Sales_&_Transaction_Report')}}
                            </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning') || Request::is('admin/report/inhoue-product-sale') || Request::is('admin/report/seller-report') || Request::is('admin/report/earning') || Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list')) ?'block':'none'}}">
                                    <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/report/admin-earning') || Request::is('admin/report/seller-earning'))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.report.admin-earning')}}"
                                           title="{{\App\CPU\translate('Earning')}} {{\App\CPU\translate('reports')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                       {{\App\CPU\translate('Earning reports')}}
                                    </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/report/inhoue-product-sale')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.report.inhoue-product-sale')}}"
                                           title="{{\App\CPU\translate('inhouse')}} {{\App\CPU\translate('sales')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                        {{\App\CPU\translate('inhouse')}} {{\App\CPU\translate('sales')}}
                                    </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/report/seller-report')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.report.seller-report')}}"
                                           title="{{\App\CPU\translate('seller')}} {{\App\CPU\translate('sales')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate text-capitalize">
                                        {{\App\CPU\translate('seller report')}}
                                    </span>
                                        </a>
                                    </li>
                                    <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/transaction/order-transaction-list') || Request::is('admin/transaction/expense-transaction-list') || Request::is('admin/transaction/refund-transaction-list'))?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.transaction.order-transaction-list')}}"
                                           title="{{\App\CPU\translate('Transaction_Report')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                     {{\App\CPU\translate('Transaction_Report')}}
                                    </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{ (Request::is('admin/report/all-product') ||Request::is('admin/stock/product-in-wishlist') || Request::is('admin/stock/product-stock')) ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.report.all-product')}}"
                                   title="{{\App\CPU\translate('Product_Report')}}">
                                    <i class="tio-chart-bar-4 nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                            <span class="position-relative">
                                {{\App\CPU\translate('Product_Report')}}
                            </span>
                        </span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/report/order')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.report.order')}}"
                                   title="{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Report')}}">
                                    <i class="tio-chart-bar-1 nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                             {{\App\CPU\translate('Order_Report')}}
                            </span>
                                </a>
                            </li>
                        @endif
                    <!--Reports & Analytics section End-->

                        <!--User management-->
                        @if(\App\CPU\Helpers::module_permission_check('user_section'))
                            <li class="nav-item {{(Request::is('admin/customer/list') ||Request::is('admin/sellers/subscriber-list')||Request::is('admin/sellers/seller-add') || Request::is('admin/sellers/seller-list') || Request::is('admin/delivery-man*'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('user_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/customer/wallet*') || Request::is('admin/customer/list') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('customers')}}">
                                    <i class="tio-wallet nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('customers')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('admin/customer/wallet*') || Request::is('admin/customer/list') || Request::is('admin/customer/view*') || Request::is('admin/reviews*') || Request::is('admin/customer/loyalty/report'))?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/customer/list') || Request::is('admin/customer/view*')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.customer.list')}}"
                                           title="{{\App\CPU\translate('Customer_List')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('Customer_List')}} </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/reviews*')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.reviews.list')}}"
                                           title="{{\App\CPU\translate('Customer Reviews')}}"> {{--Edited--}}
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Customer Reviews')}}
                                </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/customer/wallet/report')?'active':''}}">
                                        <a class="nav-link" title="{{\App\CPU\translate('wallet')}}"
                                           href="{{route('admin.customer.wallet.report')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                        {{\App\CPU\translate('wallet')}}
                                    </span>
                                        </a>
                                    </li>
                                    {{--                                <li class="nav-item {{Request::is('admin/customer/loyalty/report')?'active':''}}">--}}
                                    {{--                                    <a class="nav-link" title="{{\App\CPU\translate('Loyalty_Points')}}"--}}
                                    {{--                                       href="{{route('admin.customer.loyalty.report')}}">--}}
                                    {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                                    {{--                                        <span class="text-truncate">--}}
                                    {{--                                        {{\App\CPU\translate('Loyalty_Points')}}--}}
                                    {{--                                    </span>--}}
                                    {{--                                    </a>--}}
                                    {{--                                </li>--}}
                                </ul>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/seller*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('Sellers')}}">
                                    <i class="tio-users-switch nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Sellers')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/seller*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('admin/sellers/seller-add')?'active':''}}">
                                        <a class="nav-link" title="{{\App\CPU\translate('Add_New_Seller')}}"
                                           href="{{route('admin.sellers.seller-add')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                        {{\App\CPU\translate('Add_New_Seller')}}
                                    </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/sellers/seller-list')?'active':''}}">
                                        <a class="nav-link" title="{{\App\CPU\translate('Seller_List')}}"
                                           href="{{route('admin.sellers.seller-list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                        {{\App\CPU\translate('Seller_List')}}
                                    </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('admin/sellers/seller-commission-list')?'active':''}}">
                                        <a class="nav-link" title="{{\App\CPU\translate('commission')}}"
                                           href="{{route('admin.sellers.seller-commission')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                        {{\App\CPU\translate('commission')}}
                                    </span>
                                        </a>
                                    </li>
                                    {{--                                <li class="nav-item {{Request::is('admin/sellers/withdraw_list')?'active':''}}">--}}
                                    {{--                                    <a class="nav-link " href="{{route('admin.sellers.withdraw_list')}}"--}}
                                    {{--                                       title="{{\App\CPU\translate('withdraws')}}">--}}
                                    {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                                    {{--                                        <span class="text-truncate">{{\App\CPU\translate('withdraws')}}</span>--}}
                                    {{--                                    </a>--}}
                                    {{--                                </li>--}}
                                    {{--                                <li class="nav-item {{(Request::is('admin/sellers/withdraw-method/list') || Request::is('admin/sellers/withdraw-method/*'))?'active':''}}">--}}
                                    {{--                                    <a class="nav-link " href="{{route('admin.sellers.withdraw-method.list')}}"--}}
                                    {{--                                       title="{{\App\CPU\translate('Withdrawal_Methods')}}">--}}
                                    {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                                    {{--                                        <span class="text-truncate">{{\App\CPU\translate('Withdrawal_Methods')}}</span>--}}
                                    {{--                                    </a>--}}
                                    {{--                                </li>--}}
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/subscriber-list')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.customer.subscriber-list')}}"
                                   title="{{\App\CPU\translate('subscribers')}}">
                                    <span class="tio-user nav-icon"></span>
                                    <span class="text-truncate">{{\App\CPU\translate('subscribers')}} </span>
                                </a>
                            </li>

                            {{--                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/delivery-man*')?'active':''}}">--}}
                            {{--                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"--}}
                            {{--                               href="javascript:" title="{{\App\CPU\translate('delivery-man')}}">--}}
                            {{--                                <i class="tio-user nav-icon"></i>--}}
                            {{--                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">--}}
                            {{--                                {{\App\CPU\translate('delivery-man')}}--}}
                            {{--                            </span>--}}
                            {{--                            </a>--}}
                            {{--                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"--}}
                            {{--                                style="display: {{Request::is('admin/delivery-man*')?'block':'none'}}">--}}
                            {{--                                <li class="nav-item {{Request::is('admin/delivery-man/add')?'active':''}}">--}}
                            {{--                                    <a class="nav-link " href="{{route('admin.delivery-man.add')}}"--}}
                            {{--                                       title="{{\App\CPU\translate('add_new')}}">--}}
                            {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                            {{--                                        <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                                <li class="nav-item {{Request::is('admin/delivery-man/list') || Request::is('admin/delivery-man/earning-statement*') || Request::is('admin/delivery-man/order-history-log*') || Request::is('admin/delivery-man/order-wise-earning*')?'active':''}}">--}}
                            {{--                                    <a class="nav-link" href="{{route('admin.delivery-man.list')}}"--}}
                            {{--                                       title="{{\App\CPU\translate('List')}}">--}}
                            {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                            {{--                                        <span class="text-truncate">{{\App\CPU\translate('List')}}</span>--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                                <li class="nav-item {{Request::is('admin/delivery-man/chat')?'active':''}}">--}}
                            {{--                                    <a class="nav-link" href="{{route('admin.delivery-man.chat')}}"--}}
                            {{--                                       title="{{\App\CPU\translate('Chat')}}">--}}
                            {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                            {{--                                        <span class="text-truncate">{{\App\CPU\translate('chat')}}</span>--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                                <li class="nav-item {{Request::is('admin/delivery-man/withdraw-list') || Request::is('admin/delivery-man/withdraw-view*')?'active':''}}">--}}
                            {{--                                    <a class="nav-link " href="{{route('admin.delivery-man.withdraw-list')}}"--}}
                            {{--                                       title="{{\App\CPU\translate('withdraws')}}">--}}
                            {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                            {{--                                        <span class="text-truncate">{{\App\CPU\translate('withdraws')}}</span>--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}

                            {{--                                <li class="nav-item {{Request::is('admin/delivery-man/emergency-contact')?'active':''}}">--}}
                            {{--                                    <a class="nav-link " href="{{route('admin.delivery-man.emergency-contact.index')}}"--}}
                            {{--                                       title="{{\App\CPU\translate('emergency_contact')}}">--}}
                            {{--                                        <span class="tio-circle nav-indicator-icon"></span>--}}
                            {{--                                        <span class="text-truncate">{{\App\CPU\translate('Emergency_Contact')}}</span>--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                            </ul>--}}
                            {{--                        </li>--}}

                        @endif
                    <!--User management end-->

                        <!--System Settings-->
                        @if(\App\CPU\Helpers::module_permission_check('system_settings'))
                            <li class="nav-item {{(Request::is('admin/business-settings/social-media') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/fcm-index') || Request::is('admin/business-settings/mail')|| Request::is('admin/business-settings/web-config/db-index')||Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config') || Request::is('admin/business-settings/cookie-settings'))?'scroll-here':''}}">
                                <small class="nav-subtitle"
                                       title="">{{\App\CPU\translate('System_Settings')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config') || Request::is('admin/currency/view') || Request::is('admin/business-settings/web-config/app-settings') || Request::is('admin/product-settings/inhouse-shop') || Request::is('admin/business-settings/seller-settings') || Request::is('admin/customer/customer-settings') || Request::is('admin/refund-section/refund-index') || Request::is('admin/business-settings/shipping-method/setting') || Request::is('admin/business-settings/order-settings/index') || Request::is('admin/product-settings') || Request::is('admin/business-settings/web-config/delivery-restriction') || Request::is('admin/business-settings/cookie-settings'))?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.business-settings.web-config.index')}}"
                                   title="{{\App\CPU\translate('Business_Setup')}}">
                                    <i class="tio-globe nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('Business_Setup')}}
                            </span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/mail') || Request::is('admin/business-settings/sms-module') || Request::is('admin/business-settings/captcha') || Request::is('admin/social-login/view') || Request::is('admin/social-media-chat/view') || Request::is('admin/business-settings/map-api') || Request::is('admin/business-settings/payment-method') || Request::is('admin/business-settings/fcm-index'))?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.sms-module')}}"
                                   title="{{\App\CPU\translate('3rd_party')}}">
                                    <span class="tio-key nav-icon"></span>
                                    <span class="text-truncate">{{\App\CPU\translate('3rd_party')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:" title="{{\App\CPU\translate('Pages_&_Media')}}">
                                    <i class="tio-pages-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{\App\CPU\translate('Pages_&_Media')}}
                            </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list') || Request::is('admin/business-settings/social-media') || Request::is('admin/file-manager*')?'block':'none'}}">
                                    <li class="nav-item {{(Request::is('admin/static-pages*') )?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.static-pages.index')}}"
                                           title="صفحات الهبوط">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                      صفحات الهبوط
                                    </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{(Request::is('admin/business-settings/terms-condition') || Request::is('admin/business-settings/page*') || Request::is('admin/business-settings/privacy-policy') || Request::is('admin/business-settings/about-us') || Request::is('admin/helpTopic/list'))?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.business-settings.terms-condition')}}"
                                           title="{{\App\CPU\translate('pages')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                      {{\App\CPU\translate('pages')}}
                                    </span>
                                        </a>
                                    </li>
                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/social-media')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.business-settings.social-media')}}"
                                           title="{{\App\CPU\translate('Social_Media_Links')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Social_Media_Links')}}
                                </span>
                                        </a>
                                    </li>

                                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/file-manager*')?'active':''}}">
                                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                                           href="{{route('admin.file-manager.index')}}"
                                           title="{{\App\CPU\translate('gallery')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('gallery')}}
                                    </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        {{--                        <li class="navbar-vertical-aside-has-menu {{(Request::is('admin/business-settings/web-config/environment-setup') || Request::is('admin/business-settings/web-config/mysitemap') || Request::is('admin/business-settings/analytics-index') || Request::is('admin/currency/view') || Request::is('admin/business-settings/web-config/db-index') || Request::is('admin/business-settings/language*'))?'active':''}}">--}}
                        {{--                            <a class="js-navbar-vertical-aside-menu-link nav-link"--}}
                        {{--                               title="{{\App\CPU\translate('System_Setup')}}"--}}
                        {{--                               href="{{route('admin.business-settings.web-config.environment-setup')}}">--}}
                        {{--                                <i class="tio-labels nav-icon"></i>--}}
                        {{--                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">--}}
                        {{--                                {{\App\CPU\translate('System_Setup')}}--}}
                        {{--                            </span>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}
                    @endif
                    <!--System Settings end-->

                        <li class="nav-item pt-5">
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

@push('script_2')
    <script>
        $(window).on('load', function () {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        //Sidebar Menu Search
        var $rows = $('.navbar-vertical-content .navbar-nav > li');
        $('#search-bar-input').keyup(function () {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function () {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });

    </script>
@endpush


