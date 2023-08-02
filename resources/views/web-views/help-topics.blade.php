@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('FAQ'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="FAQ of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="FAQ of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <style>
        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{$web_config['primary_color']}}
            }
        }

    </style>
@endpush

@section('content')
<div class="__inline-60">
    <!-- Page Title-->
    <div class="container rtl">
        <div class="row">
            <div class="col-md-12 sidebar_heading text-center mb-2">
                <h1 class="h3  mb-0 folot-left headerTitle">{{\App\CPU\translate('frequently_asked_question')}}</h1>
            </div>
        </div>
        <hr>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl">
        <div class="row">   <!-- Sidebar-->
            <div class="col-lg-2"></div>
            <section class="col-lg-10 mt-3">
                <section class="container pt-4 pb-5 ">
                    <div class="row pt-4">
                        <div class="col-sm-6">
                            <ul class="list-unstyled">
                                @php $length=count($helps); @endphp
                                @php if($length%2!=0){$first=($length+1)/2;}else{$first=$length/2;}@endphp
                                @for($i=0;$i<$first;$i++)
                                    <div id="accordion">
                                        <div class="row mb-0 text-black">
                                            <div class="col-1 mt-3">
                                                <i class="czi-book text-muted mr-2"></i>
                                            </div>
                                            <div class="col-11">
                                                <button class="btnF btn-link collapsed" data-toggle="collapse"
                                                        data-target="#collapseTwo{{ $helps[$i]['id'] }}"
                                                        aria-expanded="false" aria-controls="collapseTwo"
                                                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                                    <li class="d-flex align-items-center border-bottom pb-3 mb-3">{{ $helps[$i]['question'] }}</li>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="collapseTwo{{ $helps[$i]['id'] }}" class="collapse"
                                             aria-labelledby="headingTwo" data-parent="#accordion">
                                            <div class="card-body">
                                                {{ $helps[$i]['answer'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </ul>
                        </div>
                        <div class="col-sm-6">

                            <ul class="list-unstyled">
                                @for($i=$first;$i<$length;$i++)
                                    <div id="accordion">
                                        <div class="row mb-0 text-black">
                                            <div class="col-1 mt-3">
                                                <i class="czi-book text-muted mr-2"></i>
                                            </div>
                                            <div class="col-11">
                                                <button class="btnF btn-link collapsed" data-toggle="collapse"
                                                        data-target="#collapseTwo{{ $helps[$i]['id'] }}"
                                                        aria-expanded="false" aria-controls="collapseTwo"
                                                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                                    <li class="d-flex align-items-center border-bottom pb-3 mb-3">{{ $helps[$i]['question'] }}</li>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="collapseTwo{{ $helps[$i]['id'] }}" class="collapse"
                                             aria-labelledby="headingTwo" data-parent="#accordion">
                                            <div class="card-body">
                                                {{ $helps[$i]['answer'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                            </ul>
                        </div>

                    </div>
                </section>
            </section>
        </div>
    </div>
</div>
@endsection


