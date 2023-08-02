@extends('layouts.front-end.app')

@section('title', \App\CPU\translate('Cart'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/shop-cart.css"/>
{{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}

@endpush

@section('content')
    <div class="container pb-5 mb-2 mt-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="cart-summary" dir="rtl">
        @include('layouts.front-end.partials.cart_details')
    </div>
@endsection

@push('script')

    <script>
        $(document).ready(function () {
            $('#city').select2();
            $('#area').select2();
        });

        cartQuantityInitialize();
    </script>


    <script type="text/javascript">
        document.getElementById("contact_person_name").value = getSavedValue("contact_person_name");    // set the value to this input
        document.getElementById("person_email").value = getSavedValue("person_email");    // set the value to this input
        document.getElementById("order_phone").value = getSavedValue("order_phone");   // set the value to this input
        /* Here you can add more inputs to set value. if it's saved */

        //Save the value function - save it to localStorage as (ID, VALUE)
        function saveValue(e){
            var id = e.id;  // get the sender's id to save it .
            var val = e.value; // get the value.
            localStorage.setItem(id, val);// Every time user writing something, the localStorage's value will override .
        }

        //get the saved value function - return the value of "v" from localStorage.
        function getSavedValue  (v){
            if (!localStorage.getItem(v)) {
                return "";// You can change this to your defualt value.
            }
            return localStorage.getItem(v);
        }
    </script>

@endpush
