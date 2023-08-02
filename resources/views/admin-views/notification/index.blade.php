@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Add new notification'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/push_notification.png')}}" alt="">
                {{\App\CPU\translate('push_notification')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.notification.store')}}" method="post"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color text-capitalize"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('Title')}} </label>
                                        <input type="text" name="title" class="form-control"
                                               placeholder="{{\App\CPU\translate('New notification')}}"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color text-capitalize"
                                               for="exampleFormControlInput1">{{\App\CPU\translate('Description')}} </label>
                                        <textarea name="description" class="form-control" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <center>
                                            <img class="upload-img-view mb-4" id="viewer"
                                                 onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                 src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}"
                                                 alt="image"/>
                                        </center>
                                        <label
                                            class="title-color text-capitalize">{{\App\CPU\translate('Image')}} </label>
                                        <span class="text-info">({{\App\CPU\translate('Ratio_1:1')}})</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label"
                                                   for="customFileEg1">{{\App\CPU\translate('Choose file')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{\App\CPU\translate('reset')}} </button>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Send')}}  {{\App\CPU\translate('Notification')}}  </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                    {{ \App\CPU\translate('Push_Notification_Table')}}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $notifications->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search by Title')}}"
                                               aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit"
                                                class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}} </th>
                                <th>{{\App\CPU\translate('Title')}} </th>
                                <th>{{\App\CPU\translate('Description')}} </th>
                                <th>{{\App\CPU\translate('Image')}} </th>
                                <th>{{\App\CPU\translate('Notification_Count')}} </th>
                                <th>{{\App\CPU\translate('Status')}} </th>
                                <th>{{\App\CPU\translate('Resend')}} </th>
                                <th class="text-center">{{\App\CPU\translate('Action')}} </th>
                            </tr>

                            </thead>

                            <tbody>
                            @foreach($notifications as $key=>$notification)
                                <tr>
                                    <td>{{$notifications->firstItem()+ $key}}</td>
                                    <td>
                                        <span class="d-block">
                                            {{\Illuminate\Support\Str::limit($notification['title'],30)}}
                                        </span>
                                    </td>
                                    <td>
                                        {{\Illuminate\Support\Str::limit($notification['description'],40)}}
                                    </td>
                                    <td>
                                        <img class="min-w-75" width="75" height="75"
                                             onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                             src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}">
                                    </td>
                                    <td id="count-{{$notification->id}}">{{ $notification['notification_count'] }}</td>
                                    <td>
                                        <label class="switcher">
                                            <input type="checkbox" class="status switcher_input"
                                                   id="{{$notification['id']}}" {{$notification->status == 1?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-outline-success square-btn btn-sm"
                                           onclick="resendNotification(this)" data-id="{{ $notification->id }}">
                                            <i class="tio-refresh"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline--primary btn-sm edit square-btn"
                                               title="{{\App\CPU\translate('Edit')}}"
                                               href="{{route('admin.notification.edit',[$notification['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm delete"
                                               title="{{\App\CPU\translate('Delete')}}"
                                               href="javascript:"
                                               id="{{$notification['id']}}')">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <table class="mt-4">
                            <tfoot>
                            {!! $notifications->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.notification.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    location.reload();
                }
            });
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure delete this')}} ?',
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
                        url: "{{route('admin.notification.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{\App\CPU\translate('notification deleted successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        function resendNotification(t) {
            let id = $(t).data('id');

            Swal.fire({
                title: '{{\App\CPU\translate("Are_you_sure?")}}',
                text: '{{\App\CPU\translate('Resend_notification')}}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#161853',
                cancelButtonText: '{{\App\CPU\translate("No")}}',
                confirmButtonText: '{{\App\CPU\translate("Yes")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route("admin.notification.resend-notification") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (res) {
                            let toasterMessage = res.success ? toastr.success : toastr.info;

                            toasterMessage(res.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('#count-' + id).text(parseInt($('#count-' + id).text()) + 1);
                        },
                        complete: function () {
                            $('#loading').hide();
                        }
                    });
                }
            })
        }
    </script>
@endpush
