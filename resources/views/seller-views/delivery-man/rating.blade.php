@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Delivery-Man Review'))

@section('content')
    <div class="content container-fluid"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-10 mb-3">
            <!-- Page Title -->
            <div class="">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{asset('/public/assets/back-end/img/deliveryman.png')}}" width="20" alt="">
                    {{$delivery_man['f_name']. ' '. $delivery_man['l_name']}}
                </h2>
            </div>
            <!-- End Page Title -->

            <div class="d-flex justify-content-end flex-wrap gap-10">
                <a href="{{url()->previous()}}" class="btn btn--primary">
                    <i class="tio-back-ui"></i> {{\App\CPU\translate('Back')}}
                </a>
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <!-- Body -->
            <div class="card-body my-3">
                <div class="row align-items-md-center gx-md-5">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img
                                class="avatar avatar-xxl avatar-4by3 {{Session::get('direction') === "rtl" ? 'ml-4' : 'mr-4'}}"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                src="{{asset('storage/app/public/delivery-man')}}/{{$delivery_man['image']}}"
                                alt="Image Description">
                            <div class="d-block">
                                <h4 class="display-2 text-dark mb-0">
                                    {{number_format($average_setting, 2, '.', ' ')}}
                                </h4>
                                <p> {{\App\CPU\translate('of')}} {{$reviews->count()?? 0}} {{\App\CPU\translate('reviews')}}
                                    <span
                                        class="badge badge-soft-dark badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0">
                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('5 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$five}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('4 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$four}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('3 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$three}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('2 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$two}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('1 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$one}}</span>
                            </li>
                            <!-- End Review Ratings -->
                        </ul>
                    </div>

                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->

        <div class="card card-body mt-3">
            <div class="row border-bottom pb-3 align-items-center mb-20">
                <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0"></div>
                <div class="col-sm-8 col-md-6 col-lg-4">
                    <!-- Search -->
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{ \App\CPU\translate('Search by Order ID') }}"
                                   aria-label="Search orders" value="{{ $search }}" required>
                            <button type="submit" class="btn btn--primary">{{ \App\CPU\translate('search') }}</button>
                        </div>
                    </form>
                    <!-- End Search -->
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET">
                <div class="row gy-3 align-items-end">

                    <div class="col-md-3">
                        <div>
                            <label for="from" class="title-color d-flex">{{ \App\CPU\translate('from') }}</label>
                            <input type="date" name="from_date" id="from_date" value="{{ $from_date }}"
                                   class="form-control"
                                   title="{{ \App\CPU\translate('from') }} {{ \App\CPU\translate('date') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div>
                            <label for="to_date" class="title-color d-flex">{{ \App\CPU\translate('to') }}</label>
                            <input type="date" name="to_date" id="to_date" value="{{ $to_date }}"
                                   class="form-control"
                                   title="{{ ucfirst(\App\CPU\translate('to')) }} {{ \App\CPU\translate('date') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div>
                            <select class="form-control" name="rating">
                                <option value="" selected>
                                    --{{ \App\CPU\translate('Select_Rating') }}--</option>
                                <option value="1" {{ $rating==1 ? 'selected': '' }}>{{ \App\CPU\translate('1') }}</option>
                                <option value="2" {{ $rating==2 ? 'selected': '' }}>{{ \App\CPU\translate('2') }}</option>
                                <option value="3" {{ $rating==3 ? 'selected': '' }}>{{ \App\CPU\translate('3') }}</option>
                                <option value="4" {{ $rating==4 ? 'selected': '' }}>{{ \App\CPU\translate('4') }}</option>
                                <option value="5" {{ $rating==5 ? 'selected': '' }}>{{ \App\CPU\translate('5') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button id="filter" type="submit" class="btn btn--primary btn-block filter">
                                <i class="tio-filter-list nav-icon"></i>
                                {{ \App\CPU\translate('filter') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Card -->
        <div class="card mt-3">
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{\App\CPU\translate('SL')}}</th>
                        <th>{{\App\CPU\translate('Order_ID')}}</th>
                        <th>{{\App\CPU\translate('Reviewer')}}</th>
                        <th>{{\App\CPU\translate('Review')}}</th>
                        <th>{{\App\CPU\translate('Date')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($reviews as $key=>$review)
                        <tr>
                            <td>
                                {{$reviews->firstItem()+$key}}
                            </td>
                            <td>
                                <a class="title-color hover-c1" href="{{$review->order_id ? route('seller.orders.details',$review->order_id) : ''}}">{{ $review->order_id }}</a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-circle">
                                        <img
                                            class="avatar-img"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                                            alt="Image Description">
                                    </div>
                                    <div class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                        <span
                                            class="d-block font-size-sm text-body">{{$review->customer->email??""}}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-wrap">
                                    <div class="d-flex mb-2">
                                        <label class="badge badge-soft-info">
                                            <span>{{$review->rating}} <i class="tio-star"></i> </span>
                                        </label>
                                    </div>
                                    <p>{{$review['comment']}}</p>
                                </div>
                            </td>
                            <td>
                                {{date('d M Y H:i:s',strtotime($review['updated_at']))}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160"
                                         src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                         alt="Image Description">
                                    <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')


@endpush
