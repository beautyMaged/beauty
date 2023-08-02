@extends('layouts.blank')
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                <div class="pad-btm text-center">
                    <h1 class="h3">All Done, Great Job.</h1>
                    <p>Your software is ready to run.</p>
                    <div class="row">
                        <div class="col-sm-12 col-sm-offset-2">
                            <div class="panel bord-all mar-top panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        Configure the following setting from business settings to run the system
                                        properly.
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <ul class="list-group mar-no mar-top bord-no">
                                        <li class="list-group-item">MAIL Setting</li>
                                        <li class="list-group-item">Payment Method Configuration</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center pt-3">
                    <a href="{{ env('APP_URL') }}" target="_blank" class="btn btn-primary">Website Frontend</a>
                    <a href="{{ env('APP_URL') }}/admin/auth/login" target="_blank" class="btn btn-success">Admin Panel</a>
                </div>
            </div>
        </div>
    </div>
@endsection
