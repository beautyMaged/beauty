@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('shiprocket_login'))

@section('content')
    <div class="content container-fluid">
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-md-5 mb-3 mb-lg-2 center mt-4">
                <form action="" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="m-auto">
                                <h3>
                                    <i class="tio-user"></i>
                                    {{\App\CPU\translate('login_to_your_shiprocket_account')}}
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">{{\App\CPU\translate('email')}}</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" id="staticEmail" placeholder="Ex : doe@email.com">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">{{\App\CPU\translate('password')}}</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" class="form-control" id="inputPassword">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="m-auto">
                                <button type="submit" class="btn btn--primary btn-block">
                                    <i class="tio-key"></i>
                                    {{\App\CPU\translate('login')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
