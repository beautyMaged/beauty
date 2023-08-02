@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Wallet'))

@section('content')

    <div class="container text-center">
        <h3 class="headerTitle my-3">{{\App\CPU\translate('my_wallet')}}</h3>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 col-md-9">

                <div class="card __card">
                    <div class="card-header border-0">
                        <div class="d-flex __gap-6px flex-wrap justify-content-between">
                            <div>
                                <span>
                                    {{\App\CPU\translate('transaction_history')}}
                                </span>
                            </div>
                            <div>
                                <span>
                                    {{\App\CPU\translate('wallet_amount')}} : {{\App\CPU\Helpers::currency_converter($total_wallet_balance)}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table __table">
                                <thead class="thead-light">
                                    <tr>
                                        <td class="tdBorder">
                                            <div class="py-2"><span
                                                    class="d-block spandHeadO ">{{\App\CPU\translate('SL')}}</span></div>
                                        </td>
                                        <td class="tdBorder">
                                            <div class="py-2"><span
                                                    class="d-block spandHeadO">{{\App\CPU\translate('transaction_type')}} </span>
                                            </div>
                                        </td>
                                        <td class="tdBorder">
                                            <div class="py-2"><span
                                                    class="d-block spandHeadO">{{\App\CPU\translate('credit')}} </span>
                                            </div>
                                        </td>
                                        <td class="tdBorder">
                                            <div class="py-2"><span
                                                    class="d-block spandHeadO"> {{\App\CPU\translate('debit')}}</span></div>
                                        </td>
                                        <td class="tdBorder">
                                            <div class="py-2"><span
                                                    class="d-block spandHeadO"> {{\App\CPU\translate('balance')}}</span></div>
                                        </td>
                                        <td class="tdBorder">
                                            <div class="py-2"><span
                                                    class="d-block spandHeadO"> {{\App\CPU\translate('date')}}</span></div>
                                        </td>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($wallet_transactio_list as $key=>$item)
                                    <tr>
                                        <td class="bodytr">
                                            {{$wallet_transactio_list->firstItem()+$key}}
                                        </td>
                                        <td class="bodytr"><span class="text-capitalize">{{\App\CPU\translate(str_replace('_',' ',$item['transaction_type']))}}</span></td>
                                        <td class="bodytr"><span class="">{{\App\CPU\Helpers::currency_converter($item['credit'])}}</span></td>
                                        <td class="bodytr"><span class="">{{\App\CPU\Helpers::currency_converter($item['debit'])}}</span></td>
                                        <td class="bodytr"><span class="">{{\App\CPU\Helpers::currency_converter($item['balance'])}}</span></td>
                                        <td class="bodytr"><span class="">{{$item['created_at']}}</span></td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if($wallet_transactio_list->count()==0)
                                <center class="mt-3 mb-2">{{\App\CPU\translate('no_transaction_found')}}</center>
                            @endif

                            <div class="card-footer border-0">
                                {{$wallet_transactio_list->links()}}
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script')

@endpush
