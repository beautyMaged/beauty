<div class="row ltr" dir="{{session('direction') == "rtl" ? 'ltr' : 'rtl'}}">

    {{--    <div class="col-xl-3 d-none d-xl-block __top-slider-cate">--}}
    {{--        <div ></div>--}}
    {{--    </div>--}}

    {{--    <div class="col-xl-12 col-md-12 __top-slider-images" style="{{Session::get('direction') === "rtl" ? 'margin-top: 3px;padding-right:10px;' : 'margin-top: 3px; padding-left:10px;'}}">--}}
    {{--        @php($main_banner=\App\Model\Banner::where('banner_type','Main Banner')->where('published',1)->orderBy('id','desc')->get())--}}
    {{--        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">--}}
    {{--            <ol class="carousel-indicators">--}}
    {{--                @foreach($main_banner as $key=>$banner)--}}
    {{--                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}"--}}
    {{--                        class="{{$key==0?'active':''}}">--}}
    {{--                    </li>--}}
    {{--                @endforeach--}}
    {{--            </ol>--}}
    {{--            <div class="carousel-inner">--}}
    {{--                @foreach($main_banner as $key=>$banner)--}}
    {{--                    <div class="carousel-item {{$key==0?'active':''}}">--}}
    {{--                        <a href="{{$banner['url']}}">--}}
    {{--                            <img class="d-block w-100 __slide-img"--}}
    {{--                                 onerror="this.onerror=null;this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"--}}
    {{--                                 src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}"--}}
    {{--                                 alt="">--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
    {{--                @endforeach--}}
    {{--            </div>--}}
    {{--            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"--}}
    {{--               data-slide="prev">--}}
    {{--                <span class="carousel-control-prev-icon" aria-hidden="true" ></span>--}}
    {{--                <span class="sr-only">{{\App\CPU\translate('Previous')}}</span>--}}
    {{--            </a>--}}
    {{--            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"--}}
    {{--               data-slide="next">--}}
    {{--                <span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
    {{--                <span class="sr-only">{{\App\CPU\translate('Next')}}</span>--}}
    {{--            </a>--}}
    {{--        </div>--}}


    {{--    </div>--}}

    @if(Route::currentRouteName() == 'home' )
        @php($home_banner = \App\Model\HomeBannerSetting::first())
        <div class="first_banner_section col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 d-none d-lg-inline-block d-md-inline-block m-auto text-center"
            dir="rtl" style="padding: 0!important; background-image:url('{{asset('uploads/banners_home/'. $home_banner->image_o)}}'); width: 100%; height: 80px;background-size: 100% 100% ">
            <div class="row banner_row" dir="{{session('direction')}}">
                <div class="col-xxl-9 col-lg-9 col-md-9 col-sm-12 col-12">
                    <div class="row">
                        <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-12 col-12 up_slider_banner_title_1">
                            <h4 class="bold mb-1 s_24">
                                {{$home_banner->title_o}}
                            </h4>
                            <span class="bold s_19">
                                {{$home_banner->description_o}}
                            </span>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-12 col-12 up_slider_banner_title_2">
                            <h4 class="bold mb-1 s_24">
                                {{$home_banner->title_t}}
                            </h4>
                            <span class="bold s_19">
                                {{$home_banner->description_t}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 m-auto text-center side_img_div d-none d-lg-inline-block d-md-inline-block" dir="rtl"
            style="padding: 0;height: 250px;overflow: hidden;">
            <img src="{{asset('uploads/banners_home/'. $home_banner->image_t)}}" alt="" style="width: 100%;height: 100%">
        </div>
        <div
            class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12 m-auto text-center slider_div first_home_slider"
            dir="rtl" style="background: #a9a9a994;padding-left: 0;padding-right: 0;">
            @php($main_banner=\App\Model\Banner::where('banner_type','Main Banner')->where('published',1)->orderBy('id','desc')->get())
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators" dir="{{session('direction')}}">
                    @foreach($main_banner as $key => $banner)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}"
                            class="{{$key==0?'active':''}}">
                        </li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach($main_banner as $key=>$banner)
                        <div class="carousel-item position-relative home_carousel_item {{$key==0?'active':''}}"
                             style=" overflow: hidden">
                            <img class="d-block w-100 __slide-img"
                                 onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                 src="{{asset('storage/banner')}}/{{$banner['photo']}}"
                                 alt="">
                            <div class="first_banner_item_details">
                                <h4 class="s_40 bold second_color">
                                    {{$banner->main_title}}
                                </h4>
                                <span class="bold s_27 d-block">
                                    {{$banner->title}}
                                </span>
        {{--                                <span class="bold s_22 d-block" style="padding-left: 211px;">--}}
        {{--                                    {{$banner->description}}--}}
        {{--                                </span>--}}
                            </div>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon fa fa-chevron-left"
                          style="font-size: 37px;color: #ED165F!important" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                   style="color: #ED165F!important" data-slide="next">
                    <span class="carousel-control-next-icon fa fa-chevron-right" style="font-size: 37px"
                          aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>


    {{--        <div class="col-xl-12 col-md-12 __top-slider-images"--}}
    {{--             style="{{Session::get('direction') === "rtl" ? 'margin-top: 3px;padding-right:10px;' : 'margin-top: 3px; padding-left:10px;'}}">--}}
    {{--            @php($main_banner=\App\Model\Banner::where('banner_type','Main Banner')->where('published',1)->orderBy('id','desc')->get())--}}
    {{--            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">--}}
    {{--                <ol class="carousel-indicators">--}}
    {{--                    <li data-target="#carouselExampleIndicators" data-slide-to="1"--}}
    {{--                        class="active">--}}
    {{--                    </li>--}}
    {{--                    <li data-target="#carouselExampleIndicators" data-slide-to="2"--}}
    {{--                        class="">--}}
    {{--                    </li>--}}
    {{--                    <li data-target="#carouselExampleIndicators" data-slide-to="3"--}}
    {{--                        class="">--}}
    {{--                    </li>--}}
    {{--                </ol>--}}
    {{--                <div class="carousel-inner">--}}
    {{--                    <div class="carousel-item active">--}}
    {{--                        <a href="#">--}}
    {{--                            <img class="d-block w-100 __slide-img"--}}
    {{--                                 onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"--}}
    {{--                                 src="{{asset('assets/front-end/img/banner-1.png')}}"--}}
    {{--                                 alt="">--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
    {{--                    <div class="carousel-item">--}}
    {{--                        <a href="#">--}}
    {{--                            <img class="d-block w-100 __slide-img"--}}
    {{--                                 onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"--}}
    {{--                                 src="{{asset('assets/front-end/img/banner-1.png')}}"--}}
    {{--                                 alt="">--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
    {{--                    <div class="carousel-item">--}}
    {{--                        <a href="#">--}}
    {{--                            <img class="d-block w-100 __slide-img"--}}
    {{--                                 onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"--}}
    {{--                                 src="{{asset('assets/front-end/img/banner-1.png')}}"--}}
    {{--                                 alt="">--}}
    {{--                        </a>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"--}}
    {{--                   data-slide="prev">--}}
    {{--                    <span class="carousel-control-prev-icon" aria-hidden="true"--}}
    {{--                          style="background-image: url('{{asset('assets/front-end/img/chevron-left.png')}}')"></span>--}}
    {{--                    <span class="sr-only">{{\App\CPU\translate('Previous')}}</span>--}}
    {{--                </a>--}}
    {{--                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"--}}
    {{--                   data-slide="next">--}}
    {{--                    <span class="carousel-control-next-icon" aria-hidden="true"--}}
    {{--                          style="background-image: url('{{asset('assets/front-end/img/chevron-right.png')}}')"></span>--}}
    {{--                    <span class="sr-only">{{\App\CPU\translate('Next')}}</span>--}}
    {{--                </a>--}}
    {{--            </div>--}}


    {{--        </div>--}}

@endif
<!-- Banner group-->
</div>


<script>
    // $(function () {
    //     $('.list-group-item').on('click', function () {
    //         $('.glyphicon', this)
    //             .toggleClass('glyphicon-chevron-right')
    //             .toggleClass('glyphicon-chevron-down');
    //     });
    //
    // });

    // $(document).ready(function () {
    //     let side_img_div = $('.side_img_div');
    //     alert(side_img_div.height());
    //     // let slider_div = $('.slider_div');
    //     // slider_div.height(side_img_div.height());
    // });
</script>
