@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('commissions'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid main-card {{Session::get('direction')}}">

    <!-- Page Heading -->
    <div class="content container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-sm">
                <h1 class="page-header-title">{{\App\CPU\translate('commissions')}}
                </h1>

            </div>
        </div>

        <div class="row __mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{\App\CPU\translate('Commission Table')}}</h5>
                        <form action="{{ route('admin.sellers.seller-commission-list') }}" method="GET" class="form-inline">
                            <div class="form-group mb-2 d-flex align-items-center">
                                <label for="shop-select" class="mr-3">{{\App\CPU\translate('Select Shop')}}:</label>
                                <select id="shop-select" name="shop_name" class="form-control custom-select mr-3 ml-3">
                                    @foreach($shops as $shop)
                                        <option value="{{$shop->name}}" {{$shop_name == $shop->name ? 'selected' : ''}}>{{$shop->name}}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Fetch Commissions')}}</button>
                            </div>
                        </form>



                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{\App\CPU\translate('Commission ID')}}</th>
                                    <th>{{\App\CPU\translate('Commission Value')}}</th>
                                    <th>{{\App\CPU\translate('Commission Status')}}</th>
                                    <th>{{\App\CPU\translate('Creation Date')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($commissions as $commission)
                                    <tr>
                                        <td>{{ $commission->commission_id }}</td>
                                        <td>{{ $commission->commission_value }}</td>
                                        <td>{{ $commission->commission_status }}</td>
{{--                                        <td>{{ $commission->created_at->format('Y-m-d') }}</td>--}}
                                        <td>{{ \Carbon\Carbon::parse($commission->created_at)->format('Y-m-d') }}</td>
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
@endsection

@push('script')

@endpush
