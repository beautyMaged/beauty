@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('add_new_seller'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid main-card {{Session::get('direction')}}">

    <!-- Page Title -->
    <div class="mb-4">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/assets/back-end/img/add-new-seller.png')}}" class="mb-1" alt="">
            {{\App\CPU\translate('add_new_seller')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <form class="user" action="{{route('shop.apply')}}" method="post" enctype="multipart/form-data">
    @csrf
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('seller_information')}}
                </h5>
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="form-group">
                            <label for="exampleFirstName" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('first_name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleFirstName" name="f_name" value="{{old('f_name')}}" placeholder="{{\App\CPU\translate('Ex')}}: Jhone" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleLastName" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('last_name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleLastName" name="l_name" value="{{old('l_name')}}" placeholder="{{\App\CPU\translate('Ex')}}: Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('phone')}}</label>
                            <input type="number" class="form-control form-control-user" id="exampleInputPhone" name="phone" value="{{old('phone')}}" placeholder="{{\App\CPU\translate('Ex')}}: +09587498" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <center>
                                <img class="upload-img-view" id="viewer"
                                    src="{{asset('assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                            </center>
                        </div>

                        <div class="form-group">
                            <div class="title-color mb-2 d-flex gap-1 align-items-center">{{\App\CPU\translate('Seller_Image')}} <span class="text-info">({{\App\CPU\translate('ratio')}} {{\App\CPU\translate('1')}}:{{\App\CPU\translate('1')}})</span></div>
                            <div class="custom-file text-left">
                                <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                    accept="image/*">
                                <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('image')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('account_information')}}
                </h5>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('email')}}</label>
{{--                        <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" value="{{old('email')}}" placeholder="{{\App\CPU\translate('Ex')}}: Jhone@company.com" required>--}}
                        <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" value="{{old('email')}}" placeholder="{{\App\CPU\translate('Ex')}}: Jhone@company.com" required>
                    </div>
                    <div class="col-lg-4  form-group">
                        <label for="platform">Select Platform</label>
                        <select class="form-control" id="platform" name="platform" value="{{old('platform')}}">
                            <option>shopify</option>
                            <option>salla</option>
                            <option>zid</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    {{-- <div class="col-lg-4  form-group">
                        <label for="api_access_token">Host</label>
                        <input type="text" class="form-control" id="host" name="host" value="{{old('host')}}" placeholder="host_name" required>
                    </div> --}}
                    {{-- <div class="col-lg-4  form-group">
                        <label for="api_access_token">API Access_Token</label>
                        <input type="text" class="form-control" id="api_access_token" name="api_access_token" value="{{old('api_access_token')}}">
                    </div> --}}
                    {{-- <div class="col-lg-4  form-group">
                        <label for="api_key">API Key</label>
                        <input type="text" class="form-control" id="api_key" name="api_key" value="{{old('api_key')}}">
                    </div> --}}
                    {{-- <div class="col-lg-4  form-group">
                        <label for="api_secret">API Secret</label>
                        <input type="text" class="form-control" id="api_secret" name="api_secret" value="{{old('api_secret')}}">
                    </div> --}}
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputPassword" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('password')}}</label>
{{--                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleInputPassword" name="password" placeholder="{{\App\CPU\translate('Ex: 8+ Character')}}" required>--}}
                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleInputPassword" name="password" placeholder="{{\App\CPU\translate('Ex: 8+ Character')}}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleRepeatPassword" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('confirm_password')}}</label>
{{--                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleRepeatPassword" placeholder="{{\App\CPU\translate('Ex: 8+ Character')}}" required>--}}
                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleRepeatPassword" placeholder="{{\App\CPU\translate('Ex: 8+ Character')}}" required>
                        <div class="pass invalid-feedback">{{\App\CPU\translate('Repeat')}}  {{\App\CPU\translate('password')}} {{\App\CPU\translate('not match')}} .</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('shop_information')}}
                </h5>

                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label for="shop_name" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('shop_name')}}</label>
                        <input type="text" class="form-control form-control-user" id="shop_name" name="shop_name" placeholder="{{\App\CPU\translate('Ex')}}: Jhon" value="{{old('shop_name')}}"required>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="shop_address" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('shop_address')}}</label>
                        <textarea name="shop_address" class="form-control" id="shop_address"rows="1" placeholder="{{\App\CPU\translate('Ex')}}: Doe">{{old('shop_address')}}</textarea>
                    </div>
                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view" id="viewerLogo"
                                src="{{asset('assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{\App\CPU\translate('shop_logo')}}
                                <span class="text-info">({{\App\CPU\translate('ratio')}} {{\App\CPU\translate('1')}}:{{\App\CPU\translate('1')}})</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="logo" id="LogoUpload" class="custom-file-input"
                                    accept="image/*">
                                <label class="custom-file-label" for="LogoUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('logo')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view upload-img-view__banner" id="viewerBanner"
                                    src="{{asset('assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{\App\CPU\translate('shop_banner')}}
                                <span class="text-info">({{\App\CPU\translate('ratio')}} {{\App\CPU\translate('3')}}:{{\App\CPU\translate('1')}})</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="banner" id="BannerUpload" class="custom-file-input"
                                        accept="image/*">
                                <label class="custom-file-label" for="BannerUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Banner')}}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-10">
                    <input type="hidden" name="from_submit" value="admin">
                    <button type="reset" onclick="resetBtn()" class="btn btn-secondary">{{\App\CPU\translate('reset')}} </button>
                    <button type="submit" class="btn btn--primary btn-user" id="apply">{{\App\CPU\translate('submit')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


<script>
    function resetBtn(){
        let placeholderImg = $("#placeholderImg").data('img');
        $('#viewer').attr('src', placeholderImg);
        $('#viewerBanner').attr('src', placeholderImg);
        $('#viewerLogo').attr('src', placeholderImg);
        $('.spartan_remove_row').click();
    }

    function openInfoWeb()
    {
        var x = document.getElementById("website_info");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
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
