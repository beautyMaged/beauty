@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('withdraw_method_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{\App\CPU\translate('withdraw_method_list')}}
                </h2>
                <a href="{{route('admin.sellers.withdraw-method.create')}}" class="btn btn--primary">+ {{\App\CPU\translate('Add_method')}}</a>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5>
                                {{ \App\CPU\translate('methods')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1"> {{ $withdrawal_methods->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search_Method_Name')}}" aria-label="Search orders"
                                               value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
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
                                <th>{{\App\CPU\translate('method_name')}}</th>
                                <th>{{ \App\CPU\translate('method_fields') }}</th>
                                <th>{{\App\CPU\translate('active_status')}}</th>
                                <th>{{\App\CPU\translate('default_method')}}</th>
                                <th class="text-center">{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdrawal_methods as $key=>$withdrawal_method)
                                <tr>
                                    <td>{{$withdrawal_methods->firstitem()+$key}}</td>
                                    <td>{{$withdrawal_method['method_name']}}</td>
                                    <td>
                                        @foreach($withdrawal_method['method_fields'] as $key=>$method_field)
                                            <span class="badge badge-success opacity-75 fz-12 border border-white">
                                                <b>{{\App\CPU\translate('Name')}}:</b> {{\App\CPU\translate($method_field['input_name'])}} |
                                                <b>{{\App\CPU\translate('Type')}}:</b> {{ $method_field['input_type'] }} |
                                                <b>{{\App\CPU\translate('Placeholder')}}:</b> {{ $method_field['placeholder'] }} |
                                                <b>{{\App\CPU\translate('Is Required')}}:</b> {{ $method_field['is_required'] ? \App\CPU\translate('yes') : \App\CPU\translate('no') }}
                                            </span><br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="switcher">
                                            <input class="switcher_input status"
                                                   onclick="featured_status('{{$withdrawal_method->id}}')"
                                                   type="checkbox" {{$withdrawal_method->is_active?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="switcher">
                                            <input type="checkbox" class="default-method switcher_input"
                                                   id="{{$withdrawal_method->id}}" {{$withdrawal_method->is_default == 1?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{route('admin.sellers.withdraw-method.edit',[$withdrawal_method->id])}}"
                                               class="btn btn-outline--primary btn-sm square-btn">
                                                <i class="tio-edit"></i>
                                            </a>

                                            @if(!$withdrawal_method->is_default)
                                                <a class="btn btn-outline-danger btn-sm square-btn" href="javascript:"
                                                   title="{{\App\CPU\translate('Delete')}}"
                                                   onclick="form_alert('delete-{{$withdrawal_method->id}}','Want to delete this item ?')">
                                                    <i class="tio-delete"></i>
                                                </a>
                                                <form action="{{route('admin.sellers.withdraw-method.delete',[$withdrawal_method->id])}}"
                                                      method="post" id="delete-{{$withdrawal_method->id}}">
                                                    @csrf @method('delete')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($withdrawal_methods)==0)
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
                            {{$withdrawal_methods->links()}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script_2')
  <script>
      $(document).on('change', '.default-method', function () {
          let id = $(this).attr("id");
          let status = $(this).prop("checked") === true ? 1:0;

          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              }
          });
          $.ajax({
              url: "{{route('admin.sellers.withdraw-method.default-status-update')}}",
              method: 'POST',
              data: {
                  id: id,
                  status: status
              },
              success: function (data) {
                  if(data.success == true) {
                      toastr.success('{{\App\CPU\translate('Default_Method_updated_successfully')}}');
                      setTimeout(function(){
                          location.reload();
                      }, 1000);
                  }
                  else if(data.success == false) {
                      toastr.error('{{\App\CPU\translate('Default_Method_updated_failed.')}}');
                      setTimeout(function(){
                          location.reload();
                      }, 1000);
                  }
              }
          });
      });

      function featured_status(id) {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              }
          });
          $.ajax({
              url: "{{route('admin.sellers.withdraw-method.status-update')}}",
              method: 'POST',
              data: {
                  id: id
              },
              success: function (data) {
                  if(data.success == true) {
                      toastr.success('{{\App\CPU\translate('status_updated_successfully')}}');
                      setTimeout(function(){
                          location.reload();
                      }, 1000);
                  }
                  else if(data.success == false) {
                      toastr.error('{{\App\CPU\translate('status_update_failed.')}}');
                      setTimeout(function(){
                          location.reload();
                      }, 1000);
                  }
              }
          });
      }
  </script>
@endpush
