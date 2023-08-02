@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Support Ticket'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{asset('assets/back-end/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/back-end/img/support_ticket.png')}}" alt="">
                {{\App\CPU\translate('support_ticket')}}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $tickets->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="">
                    <div class="px-3 py-4 mb-3 border-bottom">
                        <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
                            <div class="">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search Ticket by Subject or status...')}}"
                                               aria-label="Search orders" value="{{ $search }}">
                                        <button type="submit"
                                                class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="">
                                <div class="d-flex flex-wrap flex-sm-nowrap gap-3 justify-content-end">
                                    @php($priority=request()->has('priority')?request()->input('priority'):'')
                                    <select class="form-control border-color-c1 w-160"
                                            onchange="filter_tickets('priority',this.value)">
                                        <option value="all">{{\App\CPU\translate('All_Priority')}}</option>
                                        <option value="low" {{$priority=='low'?'selected':''}}>{{\App\CPU\translate('Low')}}</option>
                                        <option value="medium" {{$priority=='medium'?'selected':''}}>{{\App\CPU\translate('Medium')}}</option>
                                        <option value="high" {{$priority=='high'?'selected':''}}>{{\App\CPU\translate('High')}}</option>
                                        <option value="urgent" {{$priority=='urgent'?'selected':''}}>{{\App\CPU\translate('Urgent')}}</option>
                                    </select>

                                    @php($status=request()->has('status')?request()->input('status'):'')
                                    <select class="form-control border-color-c1 w-160"
                                            onchange="filter_tickets('status',this.value)">
                                        <option value="all">{{\App\CPU\translate('All_Status')}}</option>
                                        <option value="open" {{$status=='open'?'selected':''}}>{{\App\CPU\translate('Open')}}</option>
                                        <option value="close" {{$status=='close'?'selected':''}}>{{\App\CPU\translate('Close')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach($tickets as $key =>$ticket)
                        <div class="border-bottom mb-3 pb-3">
                            <div class="card">
                                <div
                                    class="card-body align-items-center d-flex flex-wrap justify-content-between gap-3 border-bottom">
                                    <div class="media gap-3">
                                        <img class="avatar avatar-lg"
                                             src="{{asset('storage/profile')}}/{{$ticket->customer->image??""}}"
                                             alt="">
                                        <div class="media-body">
                                            <h6 class="mb-0 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">{{$ticket->customer->f_name??""}} {{$ticket->customer->l_name??""}}</h6>
                                            <div class="mb-2 fz-12 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">{{$ticket->customer->email??""}}</div>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <span
                                                    class="badge-soft-danger fz-12 font-weight-bold px-2 radius-50">{{\App\CPU\translate(str_replace('_',' ',$ticket->priority))}}</span>
                                                <span
                                                    class="badge-soft-info fz-12 font-weight-bold px-2 radius-50">{{\App\CPU\translate(str_replace('_',' ',$ticket->status))}}</span>
                                                <h6 class="mb-0">{{\App\CPU\translate(str_replace('_',' ',$ticket->type))}}</h6>
                                                <div class="text-nowrap {{Session::get('direction') === "rtl" ? 'pr-9' : 'pl-9'}}">
                                                    {{date('d/M/Y H:i a',strtotime($ticket->created_at))}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="switcher">
                                        <input class="switcher_input status" type="checkbox"
                                               {{$ticket->status=='open'?'checked':''}} id="{{$ticket->id}}">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                                <div
                                    class="card-body align-items-end d-flex flex-wrap flex-md-nowrap justify-content-between gap-4">
                                    <div>
                                        {{$ticket->description}}
                                    </div>
                                    <div class="text-nowrap">
                                        <a class="btn btn--primary"
                                           href="{{route('admin.support-ticket.singleTicket',$ticket['id'])}}">
                                            <i class="tio-open-in-new"></i> {{\App\CPU\translate('view')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {{$tickets->links()}}
                    </div>
                </div>

                @if(count($tickets)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160"
                             src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg"
                             alt="Image Description">
                        <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                    </div>
                @endif
            </div>
        </div>
    @endsection

    @push('script')
        <!-- Page level plugins -->
            <script src="{{asset('assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
            <script
                src="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

            <script>
                // Call the dataTables jQuery plugin
                $(document).ready(function () {
                    $('#dataTable').DataTable();
                });
            </script>

            <!-- Page level custom scripts -->
            <script src="{{asset('assets/back-end/js/croppie.js')}}"></script>
            <script>
                $(document).on('change', '.status', function () {
                    var id = $(this).attr("id");
                    if ($(this).prop("checked") === true) {
                        var status = 'open';
                    } else if ($(this).prop("checked") === false) {
                        var status = 'close';
                    }

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.support-ticket.status')}}",
                        method: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function (data) {
                            console.log(data);
                            toastr.success('Ticket closed successfully');
                        }
                    });
                });

                function filter_tickets(param, value) {
                    let text = window.location;
                    let redirect_to = '';
                    let polished = removeURLParameter(text.toString(), param);
                    if (polished.includes('?')) {
                        redirect_to = polished + '&' + param + '=' + value;
                    } else {
                        redirect_to = polished + '?' + param + '=' + value;
                    }

                    location.href = redirect_to;
                }

                function removeURLParameter(url, parameter) {
                    var urlparts = url.split('?');
                    if (urlparts.length >= 2) {
                        var prefix = encodeURIComponent(parameter) + '=';
                        var pars = urlparts[1].split(/[&;]/g);
                        for (var i = pars.length; i-- > 0;) {
                            if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                                pars.splice(i, 1);
                            }
                        }
                        return urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
                    }
                    return url;
                }
            </script>
    @endpush
