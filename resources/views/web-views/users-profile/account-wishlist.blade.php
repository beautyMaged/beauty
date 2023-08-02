@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Wishlists'))

@section('content')
    <!-- Page Content-->
    <div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <h3 class="headerTitle my-3 text-center">{{\App\CPU\translate('wishlist')}}</h3>

        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 col-md-9" id="set-wish-list">
                <!-- Item-->

                @include('web-views.partials._wish-list-data',['wishlists'=>$wishlists, 'brand_setting'=>$brand_setting])
            </section>
        </div>
    </div>
@endsection
