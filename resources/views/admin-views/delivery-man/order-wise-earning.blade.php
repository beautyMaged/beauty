@extends('layouts.back-end.app')

@section('title', $delivery_man->f_name. ' '. $delivery_man->l_name. ' ' .\App\CPU\translate('Earning_Statement'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{\App\CPU\translate('earning_statement')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.delivery-man.pages-inline-menu')

        <div class="card mb-3">
            <div class="card-body">

                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">

                        </h4>
                    </div>
                </div>

                <div class="row g-2">

                </div>
            </div>
        </div>
    </div>


@endsection

@push('script')

@endpush
