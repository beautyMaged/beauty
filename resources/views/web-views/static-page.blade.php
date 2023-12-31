@extends('layouts.front-end.app')

@section('title')
    {{$page['title']}}
@endsection

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Terms & conditions of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Terms & conditions of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

@endpush

@section('content')
    <div class="container py-5 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row mb-4">
            <div class="col-lg-12 col-md-12 col-12">
                <img src="{{asset('storage/static-pages/'. $page->image)}}" alt="">
            </div>
        </div>
        <h2 class="text-center mb-3 headerTitle">{{$page['title']}}</h2>
        <div class="card __card">
            <div class="card-body">
                {!! $page['description'] !!}
            </div>
        </div>
    </div>
@endsection
