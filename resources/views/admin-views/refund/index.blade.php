@extends('layouts.back-end.app')

@section('title',\App\CPU\translate('refund_settings'))

@section('content')
<div class="content container-fluid">

    <!-- Page Title -->
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/assets/back-end/img/business-setup.png')}}" alt="">
            {{\App\CPU\translate('Business_Setup')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inlile Menu -->
    @include('admin-views.business-settings.business-setup-inline-menu')
    <!-- End Inlile Menu -->


    <div class="card">
        <div class="card-header">
            <h5 class="text-center"><i class="tio-settings-outlined"></i>
                 {{\App\CPU\translate('refund_request_after_order_within')}}
            </h5>

        </div>
        <div class="card-body">
             @php($refund_day_limit=\App\CPU\Helpers::get_business_settings('refund_day_limit'))

            <form action="{{route('admin.refund-section.refund-update')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="input-label d-flex" for="name">{{\App\CPU\translate('days')}}</label>
                            <input class="form-control col-12" type="number" name="refund_day_limit" value="{{$refund_day_limit}}" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
