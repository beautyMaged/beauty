@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Add Shipping'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/shipping_method.png')}}" alt="">
                {{\App\CPU\translate('shipping_method')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header text-capitalize">
                        <h5 class="mb-0">{{\App\CPU\translate('choose_shipping_method')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-capitalize" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <select class="form-control text-capitalize" name="shippingCategory" onchange="seller_shipping_type(this.value);">
                                    <option value="0" selected disabled>---{{\App\CPU\translate('select')}}---</option>
                                    <option value="order_wise" {{$shippingType=='order_wise'?'selected':'' }} >{{\App\CPU\translate('order_wise')}} </option>
                                    <option  value="category_wise" {{$shippingType=='category_wise'?'selected':'' }} >{{\App\CPU\translate('category_wise')}}</option>
                                    <option  value="product_wise" {{$shippingType=='product_wise'?'selected':'' }}>{{\App\CPU\translate('product_wise')}}</option>
                                </select>
                            </div>
                            <div class="col-12 mt-2" id="product_wise_note" style="display: none">
                                <p class="m-2" class="text-danger">{{\App\CPU\translate('note')}}: {{\App\CPU\translate("Please_make_sure_all_the product's_delivery_charges_are_up_to_date.")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div id="order_wise_shipping">
            <div class="card mt-2">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize">{{\App\CPU\translate('add_order_wise_shipping')}} </h5>
                </div>
                <div class="card-body">
                    <form action="{{route('seller.business-settings.shipping-method.add')}}" method="post"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="title" class="title-color">{{\App\CPU\translate('title')}}</label>
                                    <input type="text" name="title" class="form-control" placeholder="Ex: Name of Shipping Category">
                                </div>

                                <div class="col-md-3">
                                    <label for="duration" class="title-color">{{\App\CPU\translate('duration')}}</label>
                                    <input type="text" name="duration" class="form-control"
                                            placeholder="{{\App\CPU\translate('Ex')}} : 4-6 {{\App\CPU\translate('days')}}">
                                </div>

                                <div class="col-md-3">
                                    <label for="cost" class="title-color">{{\App\CPU\translate('cost')}}</label>
                                    <input type="number" min="0" max="1000000" step="0.01" name="cost" class="form-control" placeholder="{{\App\CPU\translate('Ex')}} : 10 $">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end" style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0">
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize">
                        {{\App\CPU\translate('order_wise_shipping_method')}}
                        <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $shipping_methods->total() }}</span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('title')}}</th>
                                <th>{{\App\CPU\translate('duration')}}</th>
                                <th>{{\App\CPU\translate('cost')}}</th>
                                <th class="text-center">{{\App\CPU\translate('status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($shipping_methods as $k=>$method)
                            <tr>
                                <th>{{$shipping_methods->firstItem()+$k}}</th>
                                <td>{{$method['title']}}</td>
                                <td>
                                    {{$method['duration']}}
                                </td>
                                <td>
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($method['cost']))}}
                                </td>

                                <td>
                                    <label class="switcher mx-auto">
                                        <input type="checkbox" class="status switcher_input"
                                                id="{{$method['id']}}" {{$method->status == 1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a  class="btn btn-outline--primary btn-sm square-btn"
                                            title="{{\App\CPU\translate('Edit')}}"
                                            href="{{route('seller.business-settings.shipping-method.edit',[$method['id']])}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a  class="btn btn-outline-danger btn-sm delete"
                                            title="{{\App\CPU\translate('Delete')}}"
                                            id="{{ $method['id'] }}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {!! $shipping_methods->links() !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-2" id="update_category_shipping_cost">
            <div class="card-header text-capitalize">
                <h5 class="mb-0">{{\App\CPU\translate('update_category_shipping_cost')}}</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table" cellspacing="0"
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('category_name')}}</th>
                            <th>{{\App\CPU\translate('cost_per_product')}}</th>
                            <th class="text-center">{{\App\CPU\translate('multiply_with_QTY')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{route('seller.business-settings.category-shipping-cost.store')}}" method="POST">
                            @csrf
                            @foreach ($all_category_shipping_cost as $key=>$item)
                                <tr>
                                    <td>
                                        {{$key+1}}
                                    </td>
                                    <td>
                                        {{$item->category!=null?$item->category->name:\App\CPU\translate('not_found')}}
                                    </td>
                                    <td>
                                        <input type="hidden" class="form-control w-auto" name="ids[]" value="{{$item->id}}">
                                        <input type="number" class="form-control w-auto" min="0" step="0.01" name="cost[]" value="{{\App\CPU\BackEndHelper::usd_to_currency($item->cost)}}">
                                    </td>
                                    <td>
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" name="multiplyQTY[]" class="switcher_input"
                                                id="" value="{{$item->id}}" {{$item->multiply_qty == 1?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn--primary ">{{\App\CPU\translate('Update')}}</button>
                                    </div>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('script')
<script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
            let shipping_type = '{{$shippingType}}';

            if(shipping_type==='category_wise')
            {
                $('#product_wise_note').hide();
                $('#order_wise_shipping').hide();
                $('#update_category_shipping_cost').show();

            }else if(shipping_type==='order_wise'){
                $('#product_wise_note').hide();
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').show();
            }else{

                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').hide();
                $('#product_wise_note').show();
            }
        });
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('seller.business-settings.shipping-method.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('order wise shipping method Status updated successfully')}}');
                }
            });
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure delete this ?')}}',
                text: "{{\App\CPU\translate('You wont be able to revert this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes')}}, {{\App\CPU\translate('delete it')}}'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.business-settings.shipping-method.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Shipping Method deleted successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
    <script>
        function seller_shipping_type(val)
        {
            console.log("val");
            if(val==='category_wise')
            {
                $('#product_wise_note').hide();
                $('#order_wise_shipping').hide();
                $('#update_category_shipping_cost').show();
            }else if(val==='order_wise'){
                $('#product_wise_note').hide();
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').show();
            }else{
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').hide();
                $('#product_wise_note').show();
            }

            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('seller.business-settings.shipping-type.store')}}",
                    method: 'POST',
                    data: {
                        shippingType: val
                    },
                    success: function (data) {
                        toastr.success('{{\App\CPU\translate('shipping_method_updated_successfully!!')}}');
                    }
                });
        }
    </script>
@endpush
