@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Inhouse product sale Report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/back-end/img/inhouse_sale.png')}}" alt="">
                {{\App\CPU\translate('inhouse_sale')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <form class="w-100" action="{{route('admin.report.inhoue-product-sale')}}">
                            @csrf
                            <div class="row gy-2 align-items-center">
                                <div class="col-sm-9">
                                    <div class="d-flex align-items-center gap-10">
                                        <label for="exampleInputEmail1" class="title-color mb-0">{{\App\CPU\translate('Category')}}</label>
                                        <select
                                            class="js-select2-custom form-control"
                                            name="category_id">
                                            <option value="all">{{\App\CPU\translate('All')}}</option>
                                            @foreach($categories as $c)
                                                <option value="{{$c['id']}}" {{$category_id==$c['id']? 'selected': ''}}>
                                                    {{$c['name']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn--primary btn-block">
                                        {{\App\CPU\translate('Filter')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}} </th>
                                <th>
                                    {{\App\CPU\translate('Product Name')}}
                                </th>
                                <th class="text-center">
                                    {{\App\CPU\translate('Total Sale')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data['name']}}</td>
                                    <td class="text-center">{{$data->order_delivered->sum('qty')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {!! $products->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats -->
    </div>
@endsection

@push('script')

@endpush

@push('script_2')

@endpush
