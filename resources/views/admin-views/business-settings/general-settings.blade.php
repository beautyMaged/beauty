@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('General Setting'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('General settings')}}</li>
        </ol>
    </nav>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h4 class="mb-0 text-black-50">{{\App\CPU\translate('General Business Settings')}}</h4>
    </div>

    <div class="row __mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between pl-4 pr-4">
                        <div>
                            <h5>{{\App\CPU\translate('Language Table')}}</h5>
                        </div>
                    </div>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="display table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#{{\App\CPU\translate('SL')}}</th>
                                    <th scope="col">{{\App\CPU\translate('ID')}}</th>
                                    <th scope="col">{{\App\CPU\translate('Name')}}</th>
                                    <th scope="col">{{\App\CPU\translate('Code')}}</th>
                                    <th scope="col">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($language=App\Model\BusinessSetting::where('type','language')->first())
                                @foreach(json_decode($language['value'],true) as $key =>$data)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$data['id']}}</td>
                                        <td>{{$data['name']}}</td>
                                        <td>{{$data['code']}}</td>
                                        <td class="__w-100px">
                                            <label class="switch">
                                                <input type="checkbox" onclick="updateStatus('{{route('admin.business-settings.update-language')}}','{{$data['id']}}')"
                                                       class="status" {{$data['status']==1?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        function updateStatus(route,id) {
            $.get({
                url: route,
                data: {
                    id: id,
                },
                success: function (data) {
                   /* console.log(data)*/
                }
            });
        }
    </script>
@endpush
