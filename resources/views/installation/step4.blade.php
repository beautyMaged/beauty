@extends('layouts.blank')
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-md-12">
                <div class="mar-ver pad-btm text-center">
                    @if(session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{session('error')}}
                        </div>
                    @endif
                    <h1 class="h3">Import Software Database</h1>
                </div>
                <p class="text-muted font-13 text-center">
                    <strong>Database is connected <i class="fa fa-check"></i></strong>. Proceed
                    <strong>Press Import</strong>.
                    This automated process will configure your database.
                </p>
                @if(session()->has('error'))
                    <div class="text-center mar-top pad-top">
                        <a href="{{ route('force-import-sql') }}" class="btn btn-danger">Force
                            Import Database</a>
                    </div>
                @else
                    <div class="text-center mar-top pad-top">
                        <a href="{{ route('import_sql') }}" class="btn btn-info">Import
                            Database</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
