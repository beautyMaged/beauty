@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('FAQ'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/Pages.png')}}" width="20" alt="">
                {{\App\CPU\translate('pages')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
    @include('admin-views.business-settings.pages-inline-menu')
    <!-- End Inlile Menu -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('help_topic')}} {{\App\CPU\translate('Table')}} </h5>
                        <button class="btn btn--primary btn-icon-split for-addFaq" data-toggle="modal"
                                data-target="#addModal">
                            <i class="tio-add"></i>
                            <span class="text">{{\App\CPU\translate('Add')}} {{\App\CPU\translate('faq')}}  </span>
                        </button>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100"
                                id="dataTable" cellspacing="0"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{\App\CPU\translate('SL')}}</th>
                                    <th>{{\App\CPU\translate('Question')}}</th>
                                    <th class="min-w-200">{{\App\CPU\translate('Answer')}}</th>
                                    <th>{{\App\CPU\translate('Ranking')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Status')}} </th>
                                    <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($helps as $k=>$help)
                                    <tr id="data-{{$help->id}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{$help['question']}}</td>
                                        <td>{{$help['answer']}}</td>
                                        <td>{{$help['ranking']}}</td>

                                        <td>
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input status_id"
                                                       data-id="{{ $help->id }}" {{$help->status == 1?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-10">
                                                <a class="btn btn-outline--primary btn-sm edit"
                                                   data-toggle="modal" data-target="#editModal"
                                                   title="{{ \App\CPU\translate('Edit')}}"
                                                   data-id="{{ $help->id }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm delete"
                                                   title="{{ \App\CPU\translate('Delete')}}"
                                                   id="{{$help['id']}}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- add modal --}}
        <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{\App\CPU\translate('Add Help Topic')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                                aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.helpTopic.add-new') }}" method="post" id="addForm">
                        @csrf
                        <div class="modal-body"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                            <div class="form-group">
                                <label>{{\App\CPU\translate('Question')}}</label>
                                <input type="text" class="form-control" name="question"
                                       placeholder="{{\App\CPU\translate('Type Question')}}">
                            </div>


                            <div class="form-group">
                                <label>{{\App\CPU\translate('Answer')}}</label>
                                <textarea class="form-control" name="answer" cols="5"
                                          rows="5" placeholder="{{\App\CPU\translate('Type Answer')}}"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="control-label">{{\App\CPU\translate('Status')}}</div>
                                        <label class="mt-2">
                                            <input type="checkbox" name="status" id="e_status" value="1"
                                                   class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span
                                                class="custom-switch-description">{{\App\CPU\translate('Active')}}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="ranking">{{\App\CPU\translate('Ranking')}}</label>
                                    <input type="number" name="ranking" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                            <button class="btn btn--primary">{{\App\CPU\translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}

    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{\App\CPU\translate('Edit Modal Help Topic')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="editForm"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    @csrf
                    {{-- @method('put') --}}
                    <div class="modal-body">

                        <div class="form-group">
                            <label>{{\App\CPU\translate('Question')}}</label>
                            <input type="text" class="form-control" name="question"
                                   placeholder="{{\App\CPU\translate('Type Question')}}"
                                   id="e_question" class="e_name">
                        </div>


                        <div class="form-group">
                            <label>{{\App\CPU\translate('Answer')}}</label>
                            <textarea class="form-control" name="answer" cols="5"
                                      rows="5" placeholder="{{\App\CPU\translate('Type Answer')}}"
                                      id="e_answer"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="ranking">{{\App\CPU\translate('Ranking')}}</label>
                                <input type="number" name="ranking" class="form-control" id="e_ranking" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                        <button class="btn btn--primary">{{\App\CPU\translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
{{--    <script src="{{asset('assets/back-end')}}/js/demo/datatables-demo.js"></script>--}}

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
        $(document).on('click', ".status_id", function () {
            let id = $(this).attr('data-id');

            $.ajax({
                url: "status/" + id,
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    toastr.success(res.success);
                }

            });

        });
        $(document).on('click', '.edit', function () {
            let id = $(this).attr("data-id");
            console.log(id);
            $.ajax({
                url: "edit/" + id,
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                dataType: "json",
                success: function (data) {
                    // console.log(data);
                    $("#e_question").val(data.question);
                    $("#e_answer").val(data.answer);
                    $("#e_ranking").val(data.ranking);


                    $("#editForm").attr("action", "update/" + data.id);


                }
            });
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure delete this FAQ')}}?',
                text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes')}}, {{\App\CPU\translate('delete it')}}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.helpTopic.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{\App\CPU\translate('FAQ deleted successfully')}}');
                            $('#data-' + id).hide();
                        }
                    });
                }
            })
        });
    </script>
@endpush
