@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Transaction History'))

@push('css_or_js')
    <style>
        .tdBorder {
            border-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1px solid #f7f0f0;
        }
        .sidebarL h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}}    !important;
        }
        .marl {
            margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7px;
        }
        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{$web_config['primary_color']}};
            }
        }

    </style>
@endpush

@section('content')
<div class="__inline-49">
    <div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 float-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} headerTitle">{{\App\CPU\translate('purchase_statement')}}</h1>
            </div>
        </div>
    </div>
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 mt-3">
                <div class="card box-shadow-sm">

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr style="background: {{$web_config['secondary_color']}}">
                            <td class="tdBorder">
                                <div class="py-2"><span class="d-block spandHeadO ">{{\App\CPU\translate('Tranx')}} {{\App\CPU\translate('ID')}}</span></div>
                            </td>
                            <td class="tdBorder">
                                <div class="py-2 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"><span class="d-block spandHeadO ">{{\App\CPU\translate('payment_method')}}</span></div>
                            </td>
                            <td class="tdBorder">
                                <div class="py-2"><span class="d-block spandHeadO">{{\App\CPU\translate('Status')}} </span></div>
                            </td>
                            <td class="tdBorder">
                                <div class="py-2"><span class="d-block spandHeadO"> {{\App\CPU\translate('Total')}}</span></div>
                            </td>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($transactionHistory as $history)
                            <tr>
                                <td class="bodytr font-weight-bold __color-92C6FF"><span
                                        class="marl">{{$history['id']}}</span></td>
                                <td class="sellerName bodytr "><span
                                        class="">{{$history['payment_method']}}</span></td>
                                <td class="bodytr"><span class="">{{$history['payment_status']}}</span>
                                </td>
                                <td class="bodytr"><span class=" amount ">{{\App\CPU\Helpers::currency_converter($history->order->order_amount)}}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <!-- Orders list-->
    </div>
</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.js"></script>
@endpush
