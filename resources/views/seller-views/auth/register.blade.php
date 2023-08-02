@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Seller Apply'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
<link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush


@section('content')

<div class="container py-5 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

    <h3 class="mb-3 text-center"> {{\App\CPU\translate('Shop')}} {{\App\CPU\translate('Application')}}</h3>
    <form class="__shop-apply" action="{{route('shop.apply')}}" id="form-id" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card __card mb-3">
            <div class="card-header">
                <h5 class="card-title m-0">
                    <svg width="20" height="20" x="0" y="0" viewBox="0 0 482.9 482.9" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g>
                        <g>
                            <g>
                                <path d="M239.7,260.2c0.5,0,1,0,1.6,0c0.2,0,0.4,0,0.6,0c0.3,0,0.7,0,1,0c29.3-0.5,53-10.8,70.5-30.5
                                    c38.5-43.4,32.1-117.8,31.4-124.9c-2.5-53.3-27.7-78.8-48.5-90.7C280.8,5.2,262.7,0.4,242.5,0h-0.7c-0.1,0-0.3,0-0.4,0h-0.6
                                    c-11.1,0-32.9,1.8-53.8,13.7c-21,11.9-46.6,37.4-49.1,91.1c-0.7,7.1-7.1,81.5,31.4,124.9C186.7,249.4,210.4,259.7,239.7,260.2z
                                    M164.6,107.3c0-0.3,0.1-0.6,0.1-0.8c3.3-71.7,54.2-79.4,76-79.4h0.4c0.2,0,0.5,0,0.8,0c27,0.6,72.9,11.6,76,79.4
                                    c0,0.3,0,0.6,0.1,0.8c0.1,0.7,7.1,68.7-24.7,104.5c-12.6,14.2-29.4,21.2-51.5,21.4c-0.2,0-0.3,0-0.5,0l0,0c-0.2,0-0.3,0-0.5,0
                                    c-22-0.2-38.9-7.2-51.4-21.4C157.7,176.2,164.5,107.9,164.6,107.3z" fill="#000000" data-original="#000000" class=""></path>
                                <path d="M446.8,383.6c0-0.1,0-0.2,0-0.3c0-0.8-0.1-1.6-0.1-2.5c-0.6-19.8-1.9-66.1-45.3-80.9c-0.3-0.1-0.7-0.2-1-0.3
                                    c-45.1-11.5-82.6-37.5-83-37.8c-6.1-4.3-14.5-2.8-18.8,3.3c-4.3,6.1-2.8,14.5,3.3,18.8c1.7,1.2,41.5,28.9,91.3,41.7
                                    c23.3,8.3,25.9,33.2,26.6,56c0,0.9,0,1.7,0.1,2.5c0.1,9-0.5,22.9-2.1,30.9c-16.2,9.2-79.7,41-176.3,41
                                    c-96.2,0-160.1-31.9-176.4-41.1c-1.6-8-2.3-21.9-2.1-30.9c0-0.8,0.1-1.6,0.1-2.5c0.7-22.8,3.3-47.7,26.6-56
                                    c49.8-12.8,89.6-40.6,91.3-41.7c6.1-4.3,7.6-12.7,3.3-18.8c-4.3-6.1-12.7-7.6-18.8-3.3c-0.4,0.3-37.7,26.3-83,37.8
                                    c-0.4,0.1-0.7,0.2-1,0.3c-43.4,14.9-44.7,61.2-45.3,80.9c0,0.9,0,1.7-0.1,2.5c0,0.1,0,0.2,0,0.3c-0.1,5.2-0.2,31.9,5.1,45.3
                                    c1,2.6,2.8,4.8,5.2,6.3c3,2,74.9,47.8,195.2,47.8s192.2-45.9,195.2-47.8c2.3-1.5,4.2-3.7,5.2-6.3
                                    C447,415.5,446.9,388.8,446.8,383.6z" fill="#000000" data-original="#000000" class=""></path>
                            </g>
                        </g>
                    </svg>
                    {{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Info')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="f_name" value="{{old('f_name')}}" placeholder="{{\App\CPU\translate('first_name')}}" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="l_name" value="{{old('l_name')}}" placeholder="{{\App\CPU\translate('last_name')}}" required>
                    </div>
                    <div class="col-sm-6 mt-4">
                        <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" value="{{old('email')}}" placeholder="{{\App\CPU\translate('email_address')}}" required>
                    </div>
                    <div class="col-sm-6"><small class="text-danger">( * {{\App\CPU\translate('country_code_is_must')}} {{\App\CPU\translate('like_for_BD_880')}} )</small>
                        <input type="number" class="form-control form-control-user" id="exampleInputPhone" name="phone" value="{{old('phone')}}" placeholder="{{\App\CPU\translate('phone_number')}}" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="password" class="form-control form-control-user" minlength="6" id="exampleInputPassword" name="password" placeholder="{{\App\CPU\translate('password')}}" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="password" class="form-control form-control-user" minlength="6" id="exampleRepeatPassword" placeholder="{{\App\CPU\translate('repeat_password')}}" required>
                        <div class="pass invalid-feedback">{{\App\CPU\translate('Repeat')}}  {{\App\CPU\translate('password')}} {{\App\CPU\translate('not match')}} .</div>
                    </div>
                    <div class="col-sm-12">
                        <center>
                            <img class="__img-125px object-cover" id="viewer"
                                src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                        </center>
                        <div class="custom-file mt-3">
                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('image')}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card __card">
            <div class="card-header">
                <h5 class="card-title m-0">
                    <svg width="22" height="22" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g><path id="_x38_" d="m94.581 96.596c0-1.106.907-1.984 2.013-1.984s1.984.878 1.984 1.984v29.392c0 1.105-.879 2.013-1.984 2.013h-85.178c-1.105 0-1.984-.907-1.984-2.013v-50.537c0-1.106.878-1.984 1.984-1.984s2.013.878 2.013 1.984v48.552h81.152z" fill="#000000" data-original="#000000" class=""></path><path id="_x37_" d="m29.104 120.8c0 1.105-.907 2.013-2.013 2.013s-1.983-.907-1.983-2.013v-25.537c0-3.798 1.53-7.256 4.053-9.75 2.495-2.494 5.953-4.054 9.751-4.054s7.256 1.56 9.75 4.054c2.495 2.494 4.054 5.952 4.054 9.75v25.537c0 1.105-.907 2.013-2.012 2.013-1.106 0-1.985-.907-1.985-2.013v-25.537c0-2.692-1.105-5.131-2.891-6.915-1.786-1.786-4.224-2.892-6.917-2.892s-5.159 1.105-6.944 2.892c-1.758 1.784-2.863 4.223-2.863 6.915z" fill="#000000" data-original="#000000" class=""></path><path id="_x36_" d="m65.584 104.843h18.367v-13.974h-18.367zm20.38 3.997h-22.364c-1.105 0-2.013-.879-2.013-1.984v-17.999c0-1.105.907-1.984 2.013-1.984h22.364c1.105 0 2.013.879 2.013 1.984v17.998c0 1.106-.908 1.985-2.013 1.985z" fill="#000000" data-original="#000000" class=""></path><path id="_x35_" clip-rule="evenodd" d="m42.256 110.058c1.077 0 1.984-.906 1.984-1.983 0-1.105-.907-2.013-1.984-2.013-1.105 0-2.013.907-2.013 2.013 0 1.076.907 1.983 2.013 1.983z" fill-rule="evenodd" fill="#000000" data-original="#000000" class=""></path><path id="_x34_" d="m44.58 61.959v-.114l1.333-24.744h-7.683l-4.535 24.971c.028 1.587.624 3.005 1.616 4.054.963 1.021 2.324 1.644 3.826 1.644 1.475 0 2.834-.623 3.798-1.644 1.021-1.077 1.616-2.551 1.616-4.167zm5.301-24.857-1.304 24.857c0 1.616.624 3.09 1.616 4.167.964 1.021 2.324 1.644 3.826 1.644 1.474 0 2.834-.623 3.798-1.644 1.021-1.077 1.616-2.551 1.616-4.167h.028l-.681-12.471c-.057-1.105.794-2.041 1.9-2.098 1.105-.057 2.041.794 2.097 1.899l.652 12.556v.114h.028c0 1.616.624 3.09 1.616 4.167.963 1.021 2.324 1.644 3.826 1.644 1.104 0 1.983.907 1.983 2.012s-.879 2.013-1.983 2.013c-2.636 0-5.018-1.134-6.718-2.919-.255-.283-.51-.566-.737-.879-.226.313-.481.596-.736.879-1.701 1.785-4.083 2.919-6.69 2.919-2.636 0-5.017-1.134-6.718-2.919-.255-.283-.51-.566-.737-.879-.227.313-.481.596-.736.879-1.701 1.785-4.082 2.919-6.69 2.919-2.636 0-5.017-1.134-6.718-2.919-.255-.283-.51-.566-.737-.879-.227.313-.482.596-.737.879-1.701 1.785-4.082 2.919-6.689 2.919-2.636 0-5.018-1.134-6.718-2.919-.255-.283-.51-.566-.737-.879-.227.313-.482.596-.737.879-1.7 1.785-4.081 2.919-6.689 2.919-2.239 0-4.28-.822-5.896-2.154-1.616-1.331-2.807-3.23-3.289-5.413-.169-.708-.226-1.389-.169-2.069.056-.68.227-1.36.51-2.041l8.277-20.181c.85-2.098 2.239-3.798 3.94-4.988 1.729-1.191 3.77-1.843 5.981-1.843h36.565c1.105 0 2.012.907 2.012 2.013 0 1.077-.907 1.984-2.012 1.984h-5.413zm-15.675 0h-8.107l-7.284 25.084c.057 1.531.652 2.92 1.616 3.94s2.324 1.644 3.827 1.644c1.474 0 2.834-.623 3.798-1.644 1.021-1.077 1.616-2.551 1.616-4.167h.028c0-.114 0-.255.028-.369zm-12.245 0h-3.231c-1.389 0-2.665.396-3.713 1.134-1.077.765-1.956 1.842-2.522 3.231l-8.278 20.152c-.113.283-.198.567-.227.851 0 .283 0 .567.085.878.283 1.304.992 2.438 1.956 3.231.935.766 2.069 1.19 3.345 1.19 1.474 0 2.834-.623 3.798-1.644 1.021-1.077 1.616-2.551 1.616-4.167h.028c0-.199.028-.369.057-.567z" fill="#000000" data-original="#000000" class=""></path><path id="_x33_" d="m60.624 115.585c-1.105 0-2.013-.878-2.013-1.983s.908-2.013 2.013-2.013h28.316c1.104 0 1.983.907 1.983 2.013s-.879 1.983-1.983 1.983z" fill="#000000" data-original="#000000" class=""></path><path id="_x32_" d="m124.003 46.767-25.736 43.536c-.567.963-1.786 1.275-2.722.708-.312-.17-.566-.425-.736-.736l-25.71-43.508c-.028-.057-.057-.113-.085-.142-1.247-2.268-2.211-4.733-2.891-7.284-.652-2.551-.992-5.187-.992-7.908 0-8.673 3.515-16.524 9.184-22.221 5.697-5.698 13.548-9.212 22.25-9.212 8.673 0 16.525 3.514 22.223 9.211s9.212 13.549 9.212 22.222c0 2.721-.368 5.357-1.021 7.908-.681 2.607-1.673 5.073-2.948 7.369zm-27.438-34.41c5.271 0 10.034 2.126 13.492 5.583 3.458 3.458 5.584 8.22 5.584 13.492s-2.126 10.034-5.584 13.492-8.221 5.612-13.492 5.612c-5.273 0-10.063-2.154-13.521-5.612-3.43-3.458-5.583-8.22-5.583-13.492s2.153-10.034 5.583-13.492c3.459-3.457 8.248-5.583 13.521-5.583zm10.658 8.418c-2.721-2.749-6.491-4.422-10.657-4.422-4.167 0-7.937 1.673-10.687 4.422-2.721 2.721-4.422 6.491-4.422 10.657 0 4.167 1.701 7.937 4.422 10.686 2.75 2.721 6.52 4.393 10.687 4.393 4.166 0 7.937-1.672 10.657-4.393 2.722-2.749 4.423-6.519 4.423-10.686 0-4.166-1.702-7.935-4.423-10.657zm-10.658 64.596 24.008-40.645c1.105-1.984 1.956-4.138 2.551-6.406.567-2.183.879-4.479.879-6.888 0-7.567-3.09-14.427-8.049-19.387-4.962-4.96-11.821-8.049-19.389-8.049-7.597 0-14.456 3.089-19.416 8.049-4.962 4.96-8.022 11.82-8.022 19.387 0 2.409.283 4.705.85 6.888.596 2.239 1.446 4.365 2.523 6.349l.028.057z" fill="#000000" data-original="#000000" class=""></path><path id="_x31_" d="m96.565 22.278c2.495 0 4.79 1.049 6.462 2.693 1.645 1.644 2.665 3.939 2.665 6.462s-1.021 4.818-2.665 6.462c-1.672 1.672-3.938 2.693-6.462 2.693-2.522 0-4.819-1.021-6.492-2.693-.028-.028-.057-.085-.113-.113-1.586-1.644-2.55-3.883-2.55-6.349 0-2.522 1.021-4.818 2.663-6.462.057-.028.085-.085.142-.114 1.644-1.587 3.885-2.579 6.35-2.579zm3.628 5.498c-.935-.907-2.21-1.474-3.628-1.474-1.389 0-2.636.539-3.571 1.417-.028.028-.057.057-.085.085-.937.936-1.504 2.211-1.504 3.628 0 1.389.539 2.636 1.419 3.572.028.028.057.057.085.085.936.907 2.211 1.502 3.656 1.502 1.418 0 2.693-.595 3.628-1.502.936-.936 1.502-2.211 1.502-3.657 0-1.417-.566-2.692-1.502-3.628z" fill="#000000" data-original="#000000" class=""></path></g></g></svg>
                    {{\App\CPU\translate('Shop')}} {{\App\CPU\translate('Info')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 ">
                        <input type="text" class="form-control form-control-user" id="shop_name" name="shop_name" placeholder="{{\App\CPU\translate('shop_name')}}" value="{{old('shop_name')}}"required>
                    </div>
                    <div class="col-sm-6">
                        <textarea name="shop_address" class="form-control" id="shop_address"rows="1" placeholder="{{\App\CPU\translate('shop_address')}}">{{old('shop_address')}}</textarea>
                    </div>
                    <div class="col-sm-6">
                        <div class="pb-3">
                            <center>
                                <img class="__img-125px object-cover" id="viewerLogo"
                                    src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                            </center>
                        </div>

                        <div class="form-group mb-0">
                            <div class="custom-file">
                                <input type="file" name="logo" id="LogoUpload" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="LogoUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('logo')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pb-3">
                            <center>
                                <img class="__img-125px object-cover" id="viewerBanner"
                                        src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                            </center>
                        </div>

                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" name="banner" id="BannerUpload" class="custom-file-input overflow-hidden __p-2p" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="BannerUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Banner')}}</label>
                            </div>
                        </div>
                    </div>
                    {{-- recaptcha --}}
                    @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha'))
                    @if(isset($recaptcha) && $recaptcha['status'] == 1)
                        <div id="recaptcha_element" class="w-100" data-type="image"></div>
                        <br/>
                    @else
                    <div class="col-12">
                        <div class="row py-2">
                            <div class="col-6 pr-0">
                                <input type="text" class="form-control __h-40" name="default_captcha_value" value=""
                                       placeholder="{{\App\CPU\translate('Enter captcha value')}}" class="border-0" autocomplete="off">
                            </div>
                            <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                <a onclick="javascript:re_captcha();"  class="d-flex align-items-center align-items-center">
                                    <img src="{{ URL('/seller/auth/code/captcha/1') }}" class="rounded __h-40" id="default_recaptcha_id">
                                    <i class="tio-refresh position-relative cursor-pointer p-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-12">
                        <div class="form-group mb-0 d-flex flex-wrap justify-content-between">
                            <label class="form-group mb-1 d-flex align-items-center">
                                <strong>
                                    <input type="checkbox" class="mr-1" name="remember" id="inputCheckd">
                                </strong>
                                <span class="mb-4px d-block w-0 flex-grow pl-1">
                                    <span>{{\App\CPU\translate('i_agree_to_Your_terms')}}</span>
                                    <a class="font-size-sm" target="_blank" href="{{route('terms')}}">
                                        {{\App\CPU\translate('terms_and_condition')}}
                                    </a>
                                </span>
                            </label>
                        </div>
                        <input type="hidden" name="from_submit" value="seller">
                        <button type="submit" class="btn btn--primary btn-user btn-block" id="apply" disabled>{{\App\CPU\translate('Apply')}} {{\App\CPU\translate('Shop')}} </button>
                        <div class="text-center">
                            <a class="small"  href="{{route('seller.auth.login')}}">{{\App\CPU\translate('already_have_an_account?_login.')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('script')
@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            console.log('okay')
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
            }
        });
    </script>
@else
    <script type="text/javascript">
        function re_captcha() {
            $url = "{{ URL('/seller/auth/code/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif
{{-- recaptcha scripts end --}}

<script>
    $('#inputCheckd').change(function () {
            // console.log('jell');
            if ($(this).is(':checked')) {
                $('#apply').removeAttr('disabled');
            } else {
                $('#apply').attr('disabled', 'disabled');
            }

        });

    $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup',function () {
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass==passRepeat){
            $('.pass').hide();
        }
        else{
            $('.pass').show();
        }
    });
    $('#apply').on('click',function () {

        var image = $("#image-set").val();
        if (image=="")
        {
            $('.image').show();
            return false;
        }
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass!=passRepeat){
            $('.pass').show();
            return false;
        }


    });
    function Validate(file) {
        var x;
        var le = file.length;
        var poin = file.lastIndexOf(".");
        var accu1 = file.substring(poin, le);
        var accu = accu1.toLowerCase();
        if ((accu != '.png') && (accu != '.jpg') && (accu != '.jpeg')) {
            x = 1;
            return x;
        } else {
            x = 0;
            return x;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileUpload").change(function () {
        readURL(this);
    });

    function readlogoURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewerLogo').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readBannerURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewerBanner').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#LogoUpload").change(function () {
        readlogoURL(this);
    });
    $("#BannerUpload").change(function () {
        readBannerURL(this);
    });
</script>
@endpush
