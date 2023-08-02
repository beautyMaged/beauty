@extends('layouts.back-end.app-seller')

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
                {{\App\CPU\translate('Withdraw')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header flex-wrap gap-2">
                        <h5 class="mb-0 text-capitalize">{{ \App\CPU\translate('Withdraw Request Table')}}
                            <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $withdraw_requests->total() }}</span>
                        </h5>
                        <select name="withdraw_status_filter" onchange="status_filter(this.value)" class="custom-select max-w-200">
                            <option value="all" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all'?'selected':''}}>{{\App\CPU\translate('All')}}</option>
                            <option value="approved" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved'?'selected':''}}>{{\App\CPU\translate('Approved')}}</option>
                            <option value="denied" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied'?'selected':''}}>{{\App\CPU\translate('Denied')}}</option>
                            <option value="pending" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending'?'selected':''}}>{{\App\CPU\translate('Pending')}}</option>
                        </select>
                    </div>

                    <td class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{\App\CPU\translate('SL')}}</th>
                                    <th>{{\App\CPU\translate('amount')}}</th>
                                    <th>{{\App\CPU\translate('request_time')}}</th>
                                    <th>{{\App\CPU\translate('status')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($withdraw_requests->count() > 0)
                                @foreach($withdraw_requests as $key=>$withdraw_request)
                                    <tr>
                                        <td>{{$withdraw_requests->firstitem()+$key}}</td>
                                        <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($withdraw_request['amount']))}}</td>
                                        <td>{{date("F jS, Y", strtotime($withdraw_request->created_at))}}</td>
                                        <td>
                                            @if($withdraw_request->approved==0)
                                                <label class="badge badge-soft--primary">{{\App\CPU\translate('Pending')}}</label>
                                            @elseif($withdraw_request->approved==1)
                                                <label class="badge badge-soft-success">{{\App\CPU\translate('Approved')}}</label>
                                            @else
                                                <label class="badge badge-soft-danger">{{\App\CPU\translate('Denied')}}</label>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($withdraw_request->approved==0)
                                                <button id="{{route('seller.business-settings.withdraw.cancel', [$withdraw_request['id']])}}"
                                                        onclick="close_request('{{ route('seller.business-settings.withdraw.cancel', [$withdraw_request['id']]) }}')"
                                                    class="btn btn--primary btn-sm">
                                                    {{\App\CPU\translate('close')}}
                                                </button>
                                            @else
                                                <span class="btn btn--primary btn-sm disabled">
                                                    {{\App\CPU\translate('close')}}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="5" class="text-center">
                                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                    <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                                </td>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$withdraw_requests->links()}}
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
              url: '{{route('seller.business-settings.withdraw.status-filter')}}',
              data: {
                  withdraw_status_filter: type
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

  <script>
      function close_request(route_name) {
          swal({
              title: "{{\App\CPU\translate('Are you sure?')}}",
              text: "{{\App\CPU\translate('Once deleted, you will not be able to recover this')}}",
              icon: "{{\App\CPU\translate('warning')}}",
              buttons: true,
              dangerMode: true,
              confirmButtonText: "{{\App\CPU\translate('OK')}}",
          })
              .then((willDelete) => {
                  if (willDelete.value) {
                      window.location.href = (route_name);
                  }
              });
      }
  </script>
@endpush
