@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Withdraw Request'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                {{\App\CPU\translate('Withdraw_Request')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <h5>
                                    {{ \App\CPU\translate('Withdraw Request Table')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $withdraw_req->total() }}</span>
                                </h5>
                                <form action="http://localhost/6valley/seller/product/list" method="GET">
                                </form>
                            </div>
                            <div class="col-lg-8 mt-3 mt-lg-0 d-flex gap-3 justify-content-lg-end">
                                <button type="button" class="btn btn-outline--primary text-nowrap" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{ \App\CPU\translate('export') }}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a class="dropdown-item" href="{{route('admin.delivery-man.withdraw-list-export')}}">Excel</a></li>
                                    <div class="dropdown-divider"></div>
                                </ul>

                                <select name="delivery_withdraw_status_filter" onchange="status_filter(this.value)" class="custom-select min-w-120 max-w-200">
                                    <option value="all" {{session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'all'?'selected':''}}>{{\App\CPU\translate('All')}}</option>
                                    <option value="approved" {{session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'approved'?'selected':''}}>{{\App\CPU\translate('Approved')}}</option>
                                    <option value="denied" {{session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'denied'?'selected':''}}>{{\App\CPU\translate('Denied')}}</option>
                                    <option value="pending" {{session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'pending'?'selected':''}}>{{\App\CPU\translate('Pending')}}</option>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('amount')}}</th>

                                <th>{{ \App\CPU\translate('Name') }}</th>
                                <th>{{\App\CPU\translate('request_time')}}</th>
                                <th class="text-center">{{\App\CPU\translate('status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdraw_req as $k=>$wr)
                                <tr>
                                    <td scope="row">{{$withdraw_req->firstItem()+$k}}</td>
                                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($wr['amount']))}}</td>

                                    <td>
                                        @if (isset($wr->delivery_men))
                                            <span class="title-color hover-c1">{{ $wr->delivery_men->f_name . ' ' . $wr->delivery_men->l_name }}</span>
                                        @else
                                        <span>{{\App\CPU\translate('not_found')}}</span>
                                        @endif
                                    </td>
                                    <td>{{ date_format( $wr->created_at, 'd-M-Y, h:i:s A') }}</td>
                                    <td class="text-center">
                                        @if($wr->approved==0)
                                            <label class="badge badge-soft-primary">{{\App\CPU\translate('Pending')}}</label>
                                        @elseif($wr->approved==1)
                                            <label class="badge badge-soft-success">{{\App\CPU\translate('Approved')}}</label>
                                        @else
                                            <label class="badge badge-soft-danger">{{\App\CPU\translate('Denied')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            @if (isset($wr->delivery_men))
                                            <a href="{{route('admin.delivery-man.withdraw-view',[$wr['id']])}}"
                                                class="btn btn-outline-info btn-sm square-btn"
                                                title="{{\App\CPU\translate('View')}}">
                                                <i class="tio-invisible"></i>
                                                </a>
                                            @else
                                            <a class="btn btn-outline-info btn-sm square-btn disabled" href="#">
                                                <i class="tio-invisible"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($withdraw_req)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-160"
                                        src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                        alt="Image Description">
                                <p class="mb-0">{{\App\CPU\translate('No_data_to_show')}}</p>
                            </div>
                    @endif
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            <!-- Pagination -->
                            {{$withdraw_req->links()}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script_2')
  <script>
      function status_filter(type) {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.post({
              url: '{{route('admin.delivery-man.status-filter')}}',
              data: {
                  delivery_withdraw_status_filter: type
              },
              beforeSend: function () {
                  $('#loading').show()
              },
              success: function (data) {
                 location.reload();
              },
              complete: function () {
                  $('#loading').hide()
              }
          });
      }
  </script>
@endpush
