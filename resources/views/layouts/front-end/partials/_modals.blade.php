@php($banner=\App\Model\Banner::inRandomOrder()->where(['published'=>1,'banner_type'=>'Popup Banner'])->first())
@if(isset($banner))
    <div class="modal fade" id="popup-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 __p-1px">
                    <button type="button" class="close __close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{--                <div class="modal-body cursor-pointer __p-3px" onclick="location.href='{{$banner['url']}}'">--}}
                <div class="modal-body cursor-pointer __p-3px">

                    <div class="row">
                        <div class="col-lg-3 m-auto py-3">
                            <img class="d-block w-100"
                                 onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                 src="{{asset('storage/banner')}}/{{$banner['photo']}}"
                                 alt="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center  m-auto pt-3">
                            <h3 class="s_24">{{$banner->main_title}}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center m-auto ">
                            <h3 class="s_24">{{$banner->title}}</h3>
                        </div>
                    </div>
                    @php($ios = \App\CPU\Helpers::get_business_settings('download_app_apple_stroe'))
                    @php($android = \App\CPU\Helpers::get_business_settings('download_app_google_stroe'))

                    <div class="row">

                        @if($ios['status'])

                            <div class="col-lg-5 m-auto py-3">
                                <a class="" href="{{ $ios['link'] }}" role="button">
                                    <img class="" style="height: auto"
                                         src="{{asset("assets/front-end/img/google-store.png")}}"
                                         alt="">
                                </a>
                            </div>
                        @endif

                        @if($android['status'])

                            <div class="col-lg-5 m-auto py-3">
                                <a href="{{ $android['link'] }}" role="button">
                                    <img class="" src="{{asset("assets/front-end/img/ios-store.png")}}"
                                         alt="" style="height: auto">
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
