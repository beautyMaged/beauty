@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Contact View'))
@push('css_or_js')
    <link href="{{asset('assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->
        <div class="container">
            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/assets/back-end/img/message.png')}}" alt="">
                    {{\App\CPU\translate('Message_View')}}
                </h2>
            </div>
            <!-- End Page Title -->

            <!-- Content Row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex">
                                <i class="tio-user-big"></i>
                                {{\App\CPU\translate('User_details')}}
                            </h5>
                            <form action="{{route('admin.contact.update',$contact->id)}}" method="post">
                                @csrf
                                <div class="form-group d--none">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4>{{\App\CPU\translate('Feedback')}}</h4>
                                            <textarea class="form-control" name="feedback" placeholder="{{\App\CPU\translate('Please_send_a_Feedback')}}">
                                                {{$contact->feedback}}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-end">
                                    @if($contact->seen==0)
                                        <button type="submit" class="btn btn-success">
                                            <i class="tio-checkmark-circle"></i> {{\App\CPU\translate('check')}}
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-info" disabled>
                                            <i class="tio-checkmark-circle"></i> {{\App\CPU\translate('already_check')}}
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="card-body">

                            <div class="pl-2 d-flex gap-2 align-items-center mb-3">
                                <strong class="">{{$contact->subject}}</strong>
                                @if($contact->seen==1)
                                    <label class="badge badge-soft-info mb-0">{{\App\CPU\translate('Seen')}}</label>
                                @else
                                    <label class="badge badge-soft-info mb-0">{{\App\CPU\translate('Not_Seen_Yet')}}</label>
                                @endif
                            </div>
                            <table class="table table-user-information table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td>{{\App\CPU\translate('name')}}:</td>
                                        <td>{{$contact->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{\App\CPU\translate('mobile_no')}}:</td>
                                        <td>{{$contact->mobile_number}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{\App\CPU\translate('Email')}}:</td>
                                        <td>{{$contact->email}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header justify-content-center">
                            <h5 class="mb-0 text-capitalize">
                                {{\App\CPU\translate('Message_Log')}}
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column gap-2">
                            <div class="mb-3">
                                <h5 class="px-2 py-1 badge-soft-info rounded mb-3 d-flex">{{\App\CPU\translate($contact->name)}}</h5>
                                <div class="flex-start mb-1">
                                    <strong class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">{{\App\CPU\translate('Subject')}}: </strong>
                                    <div><strong>{{$contact->subject}}</strong></div>
                                </div>
                                <div class="flex-start">
                                    <strong class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">{{\App\CPU\translate('Message')}}: </strong>
                                    <div>{{$contact->message}}</div>
                                </div>
                            </div>
                            <div>
                                <h5 class="px-2 py-1 badge-soft-warning rounded mb-3 d-flex">{{\App\CPU\translate('admin')}}</h5>
                                @if($contact['reply']!=null)
                                    @php($data=json_decode($contact['reply'],true))
                                    <div class="flex-start mb-1">
                                        <strong class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">{{\App\CPU\translate('Subject')}}: </strong>
                                        <div><strong>{{$data['subject']}}</strong></div>
                                    </div>
                                    <div class="flex-start">
                                        <strong class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">{{\App\CPU\translate('Message')}}: </strong>
                                        <div>{{$data['body']}}</div>
                                    </div>
                                @else
                                    <label class="badge badge-danger">{{\App\CPU\translate('No_reply')}}.</label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body mt-3 mx-lg-4">
                            <div class="row " style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <div class="col-12">
                                    <center>
                                        <h3>{{\App\CPU\translate('Send_Mail')}}</h3>
                                        <label class="badge-soft-danger px-1">{{\App\CPU\translate('Configure_your_mail_setup_first')}}.</label>
                                    </center>


                                    <form action="{{route('admin.contact.send-mail',$contact->id)}}" method="post">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="title-color">{{\App\CPU\translate('Subject')}}</label>
                                                    <input class="form-control" name="subject" required>
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <label class="title-color">{{\App\CPU\translate('Mail_Body')}}</label>
                                                    <textarea class="form-control h-100" name="mail_body"
                                                              placeholder="{{\App\CPU\translate('Please_send_a_Feedback')}}" required></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end pt-3 mt-5">
                                            <button type="submit" class="btn btn--primary px-4">
                                            {{\App\CPU\translate('send')}}<i class="tio-send ml-2"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush
