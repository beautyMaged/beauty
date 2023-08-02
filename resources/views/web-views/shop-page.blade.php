@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Shop Page'))

@push('css_or_js')
    @if($shop['id'] != 0)
        <meta property="og:image" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="og:title" content="{{ $shop->name}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="og:title" content="{{ $shop['name']}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @endif
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    @if($shop['id'] != 0)
        <meta property="twitter:card" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="twitter:card"
              content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @endif

    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">


    <link href="{{asset('public/assets/front-end')}}/css/home.css" rel="stylesheet">
    <style>

        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}                        !important;
        }

        /*  */
    </style>
@endpush

@section('content')

    @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 __inline-67">
        <div class="row rtl">
            <!-- banner  -->
            <div class="col-lg-12 mt-2">
                <div class="bg-white">
                    @if($shop['id'] != 0)
                        <img class="__shop-page-banner"
                             src="{{asset('storage/app/public/shop/banner')}}/{{$shop->banner}}"
                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                             alt="">
                    @else
                        @php($banner=\App\CPU\Helpers::get_business_settings('shop_banner'))
                        <img class="__shop-page-banner"
                             src="{{asset("storage/app/public/shop")}}/{{$banner??""}}"
                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                             alt="">
                    @endif
                </div>
            </div>
            {{-- sidebar opener --}}
            <div class="col-md-12 mt-2 rtl"
                 style=" text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <a class="openbtn-tab __text-20px font-semibold" onclick="openNav()">
                    <div style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}"
                         class="for-tab-display"> ☰ {{\App\CPU\translate('categories')}}</div>
                </a>
            </div>
            {{-- seller info+contact --}}
            <div class="col-lg-12 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <div class="__rounded-10 bg-white"
                     style="{{Session::get('direction') === "rtl" ? 'padding-left:5px;' : 'padding-left:5px;'}}">
                    <div class="row d-flex justify-content-between seller-details">
                        {{-- logo --}}
                        <div class="d-flex align-items-start p-2">
                            <div class="">

                                @if($shop['id'] != 0)
                                    <div class="position-relative">
                                        @if($seller_temporary_close || $inhouse_temporary_close)
                                            <span class="temporary-closed-details">
                                                <span>{{\App\CPU\translate('closed_now')}}</span>
                                            </span>
                                        @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                         $seller_id!=0 && $seller_vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date)
                                            <span class="temporary-closed-details">
                                                <span>{{\App\CPU\translate('closed_now')}}</span>
                                            </span>
                                        @endif
                                        <img class="__inline-68"
                                             src="{{asset('storage/app/public/shop')}}/{{$shop->image}}"
                                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                             alt="">
                                    </div>
                                @else
                                    <div class="position-relative">
                                        @if($seller_temporary_close || $inhouse_temporary_close)
                                            <span class="temporary-closed-details">
                                                <span>{{\App\CPU\translate('closed_now')}}</span>
                                            </span>
                                        @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                         $seller_id!=0 && $seller_vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date)
                                            <span class="temporary-closed-details">
                                                <span>{{\App\CPU\translate('closed_now')}}</span>
                                            </span>
                                        @endif
                                        <img class="__inline-68"
                                             src="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"
                                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                             alt="">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <span class="ml-4 font-weight-bold ">
                                    @if($shop['id'] != 0)
                                        {{ $shop->name}}
                                    @else
                                        {{ $web_config['name']->value }}
                                    @endif
                                </span>
                                <div class="ml-4">
                                    <div>
                                        @for($count=0; $count<5; $count++)
                                            @if($avg_rating >= $count+1)
                                                <i class="sr-star czi-star-filled active"></i>
                                            @else
                                                <i class="sr-star czi-star active __color-fea569"></i>
                                            @endif
                                        @endfor
                                        (<span class="ml-1">{{round($avg_rating,2)}}</span>)
                                    </div>
                                    <div class="d-flex __text-12px">
                                        <span>{{ $total_review}} {{\App\CPU\translate('reviews')}} </span>

                                        <span class="__inline-69"></span>

                                        <span>{{ $total_order}} {{\App\CPU\translate('orders')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- contact --}}
                        <div class="d-flex align-items-center">
                            <div class="{{Session::get('direction') === "rtl" ? 'ml-4' : 'mr-4'}}">
                                @if($seller_id!=0)
                                    @if (auth('customer')->check())
                                        <div class="d-flex">
                                            <button class="btn btn-block __inline-70" data-toggle="modal"
                                                    data-target="#exampleModal" {{ ($shop->temporary_close || (date('Y-m-d') >= date('Y-m-d', strtotime($shop->vacation_start_date)) && date('Y-m-d') <= date('Y-m-d', strtotime($shop->vacation_end_date)))) ? 'disabled' : '' }}>
                                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                                {{\App\CPU\translate('Chat with seller')}}
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-flex">
                                            <a href="{{route('customer.auth.login')}}"
                                               class="btn btn-block __inline-70">
                                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                                {{\App\CPU\translate('Chat with seller')}}
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
                @if(isset($shop->temporary_close) && ($shop->temporary_close || (date('Y-m-d') >= date('Y-m-d', strtotime($shop->vacation_start_date)) && date('Y-m-d') <= date('Y-m-d', strtotime($shop->vacation_end_date)))))
                    <span></span>
                @else
                    {{-- Modal --}}
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="card-header">
                                    {{\App\CPU\translate('write_something')}}
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('messages_store')}}" method="post" id="chat-form">
                                        @csrf
                                        @if($shop['id'] != 0)
                                            <input value="{{$shop->id}}" name="shop_id" hidden>
                                            <input value="{{$shop->seller_id}}}" name="seller_id" hidden>
                                        @endif

                                        <textarea name="message" class="form-control" required></textarea>
                                        <br>
                                        @if($shop['id'] != 0)
                                            <button
                                                class="btn btn--primary text-white">{{\App\CPU\translate('send')}}</button>
                                        @else
                                            <button class="btn btn--primary text-white"
                                                    disabled>{{\App\CPU\translate('send')}}</button>
                                        @endif
                                    </form>
                                </div>
                                <div class="card-footer justify-content-between d-flex flex-wrap">
                                    <a href="{{route('chat', ['type' => 'seller'])}}" class="btn btn--primary">
                                        {{\App\CPU\translate('go_to')}} {{\App\CPU\translate('chatbox')}}
                                    </a>
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{\App\CPU\translate('close')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>


        </div>


        <div class="row mt-1 mr-0 rtl">
            {{-- sidebar (Category) - before toggle --}}
            <div class="col-lg-3 mt-3  mr-0 {{Session::get('direction') === "rtl" ? 'pl-4' : 'pr-4'}}">
                <aside class=" hidden-xs SearchParameters" id="SearchParameters">
                    <!-- Categories Sidebar-->
                    <div class=" rounded-lg " id="shop-sidebar">
                        <div class="">
                            <!-- Categories-->
                            <div class="widget widget-categories mb-4 ">
                                <div>
                                    <div class="d-inline">
                                        <h3 class="widget-title font-bold __text-18px d-inline">{{\App\CPU\translate('categories')}}</h3>
                                    </div>
                                </div>

                                <div class="accordion mt-2" id="shop-categories">
                                    @foreach($categories as $category)
                                        <div class="card __inline-71">


                                            <div class="card-header p-1 flex-between">
                                                <div class="d-flex ">
                                                    <img
                                                        class="__inline-72 {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                        src="{{asset('storage/app/public/category')}}/{{$category['icon']}}">
                                                    <label class="for-hover-lable cursor-pointer"
                                                           onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$category['id']])}}'" {{--onclick="productSearch({{$seller_id}}, {{$category['id']}})"--}}>
                                                        {{$category['name']}}
                                                    </label>
                                                </div>
                                                <strong class="pull-right for-brand-hover cursor-pointer"
                                                        onclick="$('#collapse-{{$category['id']}}').toggle(400);if($(this).hasClass('active')){
                                                $(this).removeClass('active');
                                                $(this).text('+')
                                            }else {$(this).addClass('active');
                                                $(this).text('-')}">
                                                    {{$category->childes->count()>0?'+':''}}
                                                </strong>
                                            </div>
                                            <div
                                                class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                id="collapse-{{$category['id']}}"
                                                style="display: none">
                                                @foreach($category->childes as $child)
                                                    <div class=" for-hover-lable card-header p-1 flex-between">
                                                        <label class="cursor-pointer"
                                                               onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$child['id']])}}'">
                                                            {{$child['name']}}
                                                        </label>
                                                        <strong class="pull-right cursor-pointer"
                                                                onclick="$('#collapse-{{$child['id']}}').toggle(400);if($(this).hasClass('active')){
                                                $(this).removeClass('active');
                                                $(this).text('+')
                                            }else {$(this).addClass('active');
                                                $(this).text('-')}">
                                                            {{$child->childes->count()>0?'+':''}}
                                                        </strong>
                                                    </div>
                                                    <div
                                                        class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                        id="collapse-{{$child['id']}}"
                                                        style="display: none">
                                                        @foreach($child->childes as $ch)
                                                            <div class="card-header p-1 flex-between">
                                                                <label class="for-hover-lable cursor-pointer"
                                                                       onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$ch['id']])}}'">
                                                                    {{$ch['name']}}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
            {{-- sidebar (Category mobile) - after toggle --}}
            <div id="mySidepanel" class="sidepanel"
                 style="text-align: {{Session::get('direction') === "rtl" ? 'right:0; left:auto' : 'right:auto; left:0'}};">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <div class="cz-sidebar-body">
                    <div class="widget widget-categories mb-4 pb-4 border-bottom">
                        <div>
                            <div class="d-inline">
                                <h3 class="widget-title font-700 d-inline">{{\App\CPU\translate('categories')}}</h3>
                            </div>
                        </div>
                        <div class="divider-role __inline-73"></div>
                        <div class="accordion mt-n1" id="shop-categories"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @foreach($categories as $category)
                                <div class="card">
                                    <div class="card-header p-1 flex-between">
                                        <label class="for-hover-lable cursor-pointer"
                                               onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$category['id']])}}'" {{--onclick="productSearch({{$seller_id}}, {{$category['id']}})"--}}>
                                            {{$category['name']}}
                                        </label>
                                        <strong class="pull-right for-brand-hover cursor-pointer"
                                                onclick="$('#collapse-m-{{$category['id']}}').toggle(400)">
                                            {{$category->childes->count()>0?'+':''}}
                                        </strong>
                                    </div>
                                    <div class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                         id="collapse-m-{{$category['id']}}"
                                         style="display: none">
                                        @foreach($category->childes as $child)
                                            <div class=" for-hover-lable card-header p-1 flex-between">
                                                <label class="cursor-pointer"
                                                       onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$child['id']])}}'">
                                                    {{$child['name']}}
                                                </label>
                                                <strong class="pull-right cursor-pointer"
                                                        onclick="$('#collapse-m-{{$child['id']}}').toggle(400)">
                                                    {{$child->childes->count()>0?'+':''}}
                                                </strong>
                                            </div>
                                            <div
                                                class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                id="collapse-m-{{$child['id']}}"
                                                style="display: none">
                                                @foreach($child->childes as $ch)
                                                    <div class="card-header p-1 flex-between">
                                                        <label class="for-hover-lable cursor-pointer"
                                                               onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$ch['id']])}}'">
                                                            {{$ch['name']}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {{-- main body (Products) --}}
            <div class="col-lg-9 product-div">
                <div class="row d-flex justify-content-end">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 pt-2 __dir-ltr">
                        <form class="{{--form-inline--}} md-form form-sm mt-0" method="get"
                              action="{{route('shopView',['id'=>$seller_id])}}">
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" class="form-control" name="product_name"
                                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                       placeholder="{{\App\CPU\translate('Search products from this store')}}"
                                       aria-label="Recipient's username"
                                       aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text __bg-F3F5F9" id="basic-addon2">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Products grid-->
                <div class="row" id="ajax-products">
                    @include('web-views.products._ajax-products',['products'=>$products,'decimal_point_settings'=>$decimal_point_settings])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function productSearch(seller_id, category_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                url: '{{url('/')}}/shopView/' + seller_id + '?category_id=' + category_id,

                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>

    <script>
        function openNav() {

            document.getElementById("mySidepanel").style.width = "50%";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
    </script>

    <script>
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                url: '{{route('messages_store')}}',
                data: $('#chat-form').serialize(),
                success: function (respons) {

                    toastr.success('{{\App\CPU\translate('send successfully')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#chat-form').trigger('reset');
                }
            });

        });
    </script>
@endpush
