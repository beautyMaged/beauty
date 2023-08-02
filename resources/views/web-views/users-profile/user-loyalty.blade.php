@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Loyalty Point'))

@section('content')

    <div class="container text-center">
        <h3 class="headerTitle my-3">{{\App\CPU\translate('my_loyalty_point')}}</h3>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 col-md-9">
                @php
                    $wallet_status = App\CPU\Helpers::get_business_settings('wallet_status');
                    $loyalty_point_status = App\CPU\Helpers::get_business_settings('loyalty_point_status');
                @endphp
                <div class="card __card">
                    <div class="card-header border-0">
                        <div class="d-flex flex-wrap __gap-6 justify-content-between align-items-center __gap-15">
                            <div>
                                <span>
                                    {{\App\CPU\translate('loyalty_point_history')}}
                                </span>
                            </div>
                            <div>
                                <span>
                                    {{\App\CPU\translate('total_loyalty_point')}} : {{$total_loyalty_point}}
                                </span>
                            </div>
                            <div>
                                @if ($wallet_status == 1 && $loyalty_point_status == 1)
                                <button type="button" class="btn btn--primary" data-toggle="modal" data-target="#convertToCurrency">
                                    {{\App\CPU\translate('convert_to_currency')}}
                                </button>
                                @endif
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
                                @foreach($loyalty_point_list as $key=>$item)
                                    <tr>
                                        <td class="bodytr">
                                            {{$loyalty_point_list->firstItem()+$key}}
                                        </td>
                                        <td class="bodytr"><span class="text-capitalize">{{str_replace('_', ' ',$item['transaction_type'])}}</span></td>
                                        <td class="bodytr"><span class="">{{ $item['credit']}}</span></td>
                                        <td class="bodytr"><span class="">{{ $item['debit']}}</span></td>
                                        <td class="bodytr"><span class="">{{ $item['balance']}}</span></td>
                                        <td class="bodytr"><span class="">{{$item['created_at']}}</span></td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if($loyalty_point_list->count()==0)
                                <center class="mt-3 mb-2">{{\App\CPU\translate('no_transaction_found')}}</center>
                            @endif
                            <div class="card-footer border-0">
                                {{$loyalty_point_list->links()}}
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>


  <!-- Modal -->
  <div class="modal fade rtl" id="convertToCurrency" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{\App\CPU\translate('convert_to_currency')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('loyalty-exchange-currency')}}" method="POST">
            @csrf
        <div class="modal-body">
            <div class="text-start">
                    {{\App\CPU\translate('your loyalty point will convert to currency and transfer to your wallet')}}
            </div>
            <div class="text-center">
                <span class="text-warning">
                    {{\App\CPU\translate('minimum point for convert to currency is :')}} {{App\CPU\Helpers::get_business_settings('loyalty_point_minimum_point')}} {{\App\CPU\translate('point')}}
                </span>
            </div>
            <div class="text-center">
                <span >
                    {{App\CPU\Helpers::get_business_settings('loyalty_point_exchange_rate')}} {{\App\CPU\translate('point')}} = {{\App\CPU\Helpers::currency_converter(1)}}
                </span>
            </div>

            <div class="form-row">
                <div class="form-group col-12">

                    <input class="form-control" type="number" id="city" name="point" required>
                </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
          <button type="submit" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
        </div>
    </form>
      </div>
    </div>
  </div>
@endsection

@push('script')

@endpush
