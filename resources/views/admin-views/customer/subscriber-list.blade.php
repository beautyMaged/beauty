@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('subscriber_list'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/assets/back-end/img/subscribers.png')}}" width="20" alt="">
            {{\App\CPU\translate('subscriber_list')}}
            <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $subscription_list->total() }}</span>
        </h2>
    </div>
    <!-- End Page Title -->

    <div class="row mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <!-- Search -->
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                placeholder="{{ \App\CPU\translate('Search_by_email')}}"  aria-label="Search orders" value="{{ $search }}">
                            <button type="submit" class="btn btn--primary">{{ \App\CPU\translate('Search')}}</button>
                        </div>
                    </form>
                    <!-- End Search -->
                </div>

                <div class="table-responsive">
                    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ \App\CPU\translate('SL')}}</th>
                            <th scope="col">
                                {{ \App\CPU\translate('email')}}
                            </th>
                            <th>{{ \App\CPU\translate('subscription_date')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscription_list as $key=>$item)
                                <tr>
                                    <td>{{$subscription_list->firstItem()+$key}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>
                                        {{date('d M Y, h:i A',strtotime($item->created_at))}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {{$subscription_list->links()}}
                    </div>
                </div>

                @if(count($subscription_list)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                        <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
