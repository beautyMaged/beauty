@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Support Ticket'))

@section('content')
    <!-- Page Title-->
    <div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <h2 class="m-0 headerTitle text-center py-3">{{\App\CPU\translate('Support Ticket Answer')}}</h2>
    </div>
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-8">
                <div class="card __card mb-4">
                    <div class="table-responsive">
                        <table class="table __table mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{\App\CPU\translate('Date Submitted')}}</th>
                                    <th>{{\App\CPU\translate('Last Updated')}}</th>
                                    <th>{{\App\CPU\translate('Type')}}</th>
                                    <th>{{\App\CPU\translate('Priority')}}</th>
                                    <th>{{\App\CPU\translate('Status')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$ticket['created_at'])->format('Y-m-d')}}
                                    </td>
                                    <td>
                                        {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$ticket['updated_at'])->format('Y-m-d')}}
                                    </td>
                                    <td>
                                        {{$ticket['type']}}
                                    </td>
                                    <td>
                                        {{$ticket['priority']}}
                                    </td>
                                    <td>
                                        @if($ticket['status']=='open')
                                            <span class="badge btn btn-secondary">{{$ticket['status']}}</span>
                                        @else
                                            <span class="badge btn btn-secondary">{{$ticket['status']}}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Comment-->
                <div class="card __card mb-4">
                    <div class="__media-wrapper card-body">
                        <div class="media __incoming-msg">
                            <img class="rounded-circle __img-40" style="text-align: {{Session::get('direction') === "rtl" ? 'left' : 'right'}};"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                src="{{asset('storage/app/public/profile')}}/{{auth('customer')->user()->image}}"
                                alt="{{auth('customer')->user()->f_name}}"/>
                            <div class="media-body">
                                <h6 class="font-size-md mb-2">{{auth('customer')->user()->f_name}}</h6>
                                <p class="font-size-md mb-1">{{$ticket['description']}}</p>
                                <span class="font-size-ms text-muted">
                                        <i class="czi-time align-middle {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                                    {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$ticket['created_at'])->format('Y-m-d h:i A')}}
                                </span>
                            </div>
                        </div>
                        @foreach($ticket->conversations as $conversation)

                            @if($conversation['customer_message'] == null )
                                <div class="media __outgoing-msg">
                                    <div class="media-body">
                                        @php($admin=\App\Model\Admin::where('id',$conversation['admin_id'])->first())
                                        <h6 class="font-size-md mb-2">{{$admin['name']}}</h6>
                                        <p class="font-size-md mb-1">{{$conversation['admin_message']}}</p>
                                        <span
                                            class="font-size-ms text-muted"> {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$conversation['updated_at'])->format('Y-m-d h:i A')}}</span>
                                    </div>
                                </div>
                            @endif
                            @if($conversation['admin_message'] == null)
                                <div class="media __incoming-msg">
                                    <img class="rounded-circle" height="40" width="40"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/profile')}}/{{auth('customer')->user()->image}}"
                                        alt="{{auth('customer')->user()->f_name}}"/>
                                    <div class="media-body">
                                        <h6 class="font-size-md mb-2">{{auth('customer')->user()->f_name}}</h6>
                                        <p class="font-size-md mb-1">{{$conversation['customer_message']}}</p>
                                        <span class="font-size-ms text-muted">
                                                    <i class="czi-time align-middle {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$conversation['created_at'])->format('Y-m-d h:i A')}}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer border-0">
                        <form class="needs-validation" href="{{route('support-ticket.comment',[$ticket['id']])}}"
                            method="post" novalidate>
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="comment" rows="8"
                                        placeholder="{{\App\CPU\translate('Write your message here...')}}" required></textarea>
                                <div class="invalid-tooltip">{{\App\CPU\translate('Please write the message')}}!</div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-end align-items-center gap-8">
                                <div>
                                    <a href="{{route('support-ticket.close',[$ticket['id']])}}" class="btn btn-secondary text-white">{{\App\CPU\translate('close')}}</a>
                                </div>
                                <button class="btn btn--primary my-2" type="submit">{{\App\CPU\translate('Submit message')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection

@push('script')

@endpush
