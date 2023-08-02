@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('shipping_method'))

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
            {{\App\CPU\translate('Business_Setup')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inlile Menu -->
    @include('admin-views.business-settings.business-setup-inline-menu')
    <!-- End Inlile Menu -->

    <div class="row gy-3" >
        <div class="col-md-6" >
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="text-capitalize mb-0">
                        <!-- <i class="tio-settings-outlined"></i> -->
                         {{\App\CPU\translate('shipping_responsibility')}}
                    </h5>
                </div>
                @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
                <div class="card-body">
                    <div class="d-flex gap-10 align-items-center mb-2">
                        <input onclick="shipping_responsibility(this.value);" type="radio" name="shipping_res" value="inhouse_shipping" id="inhouse_shipping" {{ $shippingMethod=='inhouse_shipping'?'checked':'' }}>
                        <label class="title-color mb-0" for="inhouse_shipping">
                            {{\App\CPU\translate('inhouse_shipping')}}
                        </label>
                    </div>
                    <div class="d-flex gap-10 align-items-center">
                        <input onclick="shipping_responsibility(this.value);" type="radio" name="shipping_res" value="sellerwise_shipping" id="sellerwise_shipping" {{ $shippingMethod=='sellerwise_shipping'?'checked':'' }}>
                        <label class="title-color mb-0" for="sellerwise_shipping">
                            {{\App\CPU\translate('seller_wise_shipping')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @php($admin_shipping = \App\Model\ShippingType::where('seller_id',0)->first())
        @php($shippingType =isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise')
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="text-capitalize mb-0">{{\App\CPU\translate('choose_shipping_method')}}</h5>
                </div>
                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="mb-2">
                        <label class="title-color" id="for_inhouse_deliver" >{{\App\CPU\translate('for_inhouse_deliver')}}</label>
                        <select class="form-control text-capitalize w-100" name="shippingCategory" onchange="shipping_type(this.value);">
                            <option value="0" selected disabled>---{{\App\CPU\translate('select')}}---</option>
                            <option value="order_wise" {{$shippingType=='order_wise'?'selected':'' }} >{{\App\CPU\translate('order_wise')}} </option>
                            <option  value="category_wise" {{$shippingType=='category_wise'?'selected':'' }} >{{\App\CPU\translate('category_wise')}}</option>
                            <option  value="product_wise" {{$shippingType=='product_wise'?'selected':'' }}>{{\App\CPU\translate('product_wise')}}</option>
                        </select>
                    </div>
                    <div id="product_wise_note">
                        <p class="text-danger">{{\App\CPU\translate('note')}}: {{\App\CPU\translate("Please_make_sure_all_the product's_delivery_charges_are_up_to_date.")}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12" id="update_category_shipping_cost">
            @php($categories = App\Model\Category::where(['position' => 0])->get())
            <div class="card h-100">
                <div class="px-3 pt-4">
                    <h4 class="mb-0 text-capitalize">{{\App\CPU\translate('update_category_shipping_cost')}}</h4>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" cellspacing="0"
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
                                <form action="{{route('admin.business-settings.category-shipping-cost.store')}}" method="POST">
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
                                                <label class="mx-auto switcher">
                                                    <input type="checkbox" class="status switcher_input" name="multiplyQTY[]"
                                                        id="" value="{{$item->id}}" {{$item->multiply_qty == 1?'checked':''}}>
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4">
                                            <div class="d-flex flex-wrap justify-content-end gap-10">
                                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Update')}}</button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12" id="order_wise_shipping">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0 text-capitalize">{{\App\CPU\translate('add_order_wise_shipping')}}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.business-settings.shipping-method.add')}}"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color" for="title">{{\App\CPU\translate('title')}}</label>
                                            <input type="text" name="title" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color" for="duration">{{\App\CPU\translate('duration')}}</label>
                                            <input type="text" name="duration" class="form-control"
                                                placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('4 to 6 days')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color" for="cost">{{\App\CPU\translate('cost')}}</label>
                                            <input type="number" min="0" step="0.01" max="1000000" name="cost" class="form-control"
                                                placeholder="{{\App\CPU\translate('Ex')}} :">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-end gap-10">
                                    <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="px-3 py-4">
                            <div class="row justify-content-between align-items-center flex-grow-1">
                                <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                        {{\App\CPU\translate('order_wise_shipping_method')}}
                                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $shipping_methods->count() }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive pb-3">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table" cellspacing="0"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{\App\CPU\translate('sl')}}</th>
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
                                        <th>{{$k+1}}</th>
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
                                            <div class="d-flex flex-wrap justify-content-center gap-10">
                                                <a  class="btn btn-outline--primary btn-sm edit"
                                                title="{{ \App\CPU\translate('Edit')}}"
                                                href="{{route('admin.business-settings.shipping-method.edit',[$method['id']])}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a  title="{{\App\CPU\translate('delete')}}"
                                                    class="btn btn-outline-danger btn-sm delete" id="{{ $method['id'] }}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $( document ).ready(function() {
        let shipping_responsibility ='{{$shippingMethod}}';
        console.log(shipping_responsibility);
        if(shipping_responsibility === 'sellerwise_shipping')
        {
            $("#for_inhouse_deliver").show();
        }else{
            $("#for_inhouse_deliver").hide();
        }
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
</script>
<script>
    function shipping_responsibility(val){
        if(val=== 'inhouse_shipping'){
            $( "#sellerwise_shipping" ).prop( "checked", false );
            $("#for_inhouse_deliver").hide();
        }else{
            $( "#inhouse_shipping" ).prop( "checked", false );
            $("#for_inhouse_deliver").show();
        }
        console.log(val);
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.shipping-method.shipping-store')}}",
                method: 'POST',
                data: {
                    shippingMethod: val
                },
                success: function (data) {


                        //window.location.reload();
                        toastr.success("{{\App\CPU\translate('shipping_responsibility_updated_successfully!!')}}");

                }
            });
    }
</script>
<script>
    function shipping_type(val)
    {
        console.log(val);
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
                url: "{{route('admin.business-settings.shipping-type.store')}}",
                method: 'POST',
                data: {
                    shippingType: val
                },
                success: function (data) {
                    toastr.success("{{\App\CPU\translate('shipping_method_updated_successfully!!')}}");
                }
            });
    }
</script>
<script>
    // Call the dataTables jQuery plugin
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
            url: "{{route('admin.business-settings.shipping-method.status-update')}}",
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
            title: '{{\App\CPU\translate('Are you sure delete this')}} ?',
            text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{\App\CPU\translate('Yes')}}, {{\App\CPU\translate('delete it')}}!',
            type: 'warning',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.business-settings.shipping-method.delete')}}",
                    method: 'POST',
                    data: {id: id},
                    success: function () {
                        toastr.success('{{\App\CPU\translate('Order Wise Shipping Method deleted successfully')}}');
                        location.reload();
                    }
                });
            }
        })
    });
</script>
@endpush
