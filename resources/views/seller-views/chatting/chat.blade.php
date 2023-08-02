@extends('layouts.back-end.app-seller')

@section('title',\App\CPU\translate('Chatting Page'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/support-ticket.png')}}" alt="">
                {{\App\CPU\translate('Chatting_List')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Content-->
        <div class="row">
            @if(isset($chattings_user))
                <div class="col-xl-3 col-lg-4 chatSel">
                    <div class="card card-body px-0 h-100">
                        <div class="media align-items-center px-3 gap-3 mb-4">
                            <div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" src="{{asset('storage/app/public/seller/')}}/{{auth('seller')->user()->image}}" alt="Image Description">
                                <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                            </div>
                            <div class="media-body">
                                <h5 class="profile-name mb-1">{{ $shop->name }}</h5>
                                <span class="fz-12">{{\App\CPU\translate('Seller')}}</span>
                            </div>
                        </div>

                        <div class="inbox_people">
                            <form class="search-form px-3" id="chat-search-form">
                                <div class="search-input-group">
                                    <i class="tio-search search-icon" aria-hidden="true"></i>
                                    <input
                                        class=""
                                        id="myInput" type="text"
                                        @if(Request::is('seller/messages/chat/customer'))
                                            placeholder="{{\App\CPU\translate('Search_customers...')}}"
                                        @elseif(Request::is('seller/messages/chat/delivery-man'))
                                            placeholder="{{\App\CPU\translate('Search_delivery_men...')}}"
                                        @endif
                                        aria-label="Search customers...">
                                </div>
                            </form>

                            <div class="inbox_chat d-flex flex-column mt-1">
                                @foreach($chattings_user as $key => $chatting)
                                    <div class="list_filter">
                                        <div class="chat_list p-3 d-flex gap-2 user_{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}} seller-list @if ($key == 0) active @endif"
                                             id="{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}}" data-name="{{$chatting->f_name}} {{$chatting->l_name}}" data-phone="{{ $chatting->phone }}">
                                            <div class="chat_people media gap-10" id="chat_people">
                                                <div class="chat_img avatar avatar-sm avatar-circle">
                                                    <img
                                                        @if (Request::is('seller/messages/chat/customer'))
                                                            src="{{ asset('storage/app/public/profile/'.$chatting->image) }}"
                                                        @else
                                                            src="{{ asset('storage/app/public/delivery-man/'.$chatting->image) }}"
                                                        @endif
                                                        id="{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}}" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" class="avatar-img avatar-circle">
                                                    <span class="avatar-satatus avatar-sm-status avatar-status-success"></span>
                                                </div>
                                                <div class="chat_ib media-body">
                                                    <h5 class="mb-1 seller @if($chatting->seen_by_seller)active-text @endif"
                                                        id="{{$chatting->user_id? $chatting->user_id : $chatting->delivery_man_id}}" data-name="{{$chatting->f_name}} {{$chatting->l_name}}" data-phone="{{ $chatting->phone }}">
                                                        {{$chatting->f_name}} {{$chatting->l_name}}
                                                        <br><span class="mt-2 font-weight-normal text-muted" id="{{$chatting->user_id? $chatting->user_id: $chatting->delivery_man_id}}" data-name="{{$chatting->f_name}} {{$chatting->l_name}}" data-phone="{{ $chatting->phone }}">{{ $chatting->phone }}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            @if($chatting->seen_by_seller == false)
                                                <div class="message-status bg-danger" id="notif-alert-{{ $chatting->user_id? $chatting->user_id: $chatting->delivery_man_id }}"></div>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <section class="col-xl-9 col-lg-8 mt-4 mt-lg-0">
                    <div class="card card-body card-chat justify-content-between Chat">
                        <!-- Inbox Message Header -->
                        <div class="inbox_msg_header d-flex flex-wrap gap-3 justify-content-between align-items-center border px-3 py-2 rounded mb-4">
                            <!-- Profile -->
                            <div class="media align-items-center gap-3">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" id="profile_image"
                                         @if (Request::is('seller/messages/chat/customer'))
                                         src="{{ asset('storage/app/public/profile/'.$chattings_user[0]->image) }}"
                                         @else
                                         src="{{ asset('storage/app/public/delivery-man/'.$chattings_user[0]->image) }}"
                                         @endif
                                         onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                                <div class="media-body">
                                    <h5 class="profile-name mb-1" id="profile_name">{{ $chattings_user[0]->f_name.' '.$chattings_user[0]->l_name }}</h5>
                                    <span class="fz-12" id="profile_phone">{{ $chattings_user[0]->phone }}</span>
                                </div>
                            </div>
                            <!-- End Profile -->

                        </div>
                        <!-- End Inbox Message Header -->

                        <div class="messaging">
                            <div class="inbox_msg">
                                <!-- Message Body -->
                                <div class="mesgs">
                                    <div class="msg_history d-flex flex-column-reverse" id="show_msg">
                                        @foreach($chattings as $key => $message)
                                                @if ( $message->sent_by_customer? $message->sent_by_customer: $message->sent_by_delivery_man)
                                                    <div class="incoming_msg">
                                                        <div class="received_msg">
                                                            <div class="received_withd_msg">
                                                                <p class="bg-chat rounded px-3 py-2 mb-1">
                                                                    {{$message->message}}
                                                                </p>
                                                                <span class="time_date fz-12"> {{$message->created_at->format('h:i A')}}    |    {{$message->created_at->format('M d')}} </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="outgoing_msg">
                                                        <div class="sent_msg">
                                                            <p class="bg-c1 text-white rounded px-3 py-2 mb-1">
                                                                {{$message->message}}
                                                            </p>
                                                            <span class="time_date fz-12 d-flex justify-content-end"> {{$message->created_at->format('h:i A')}}    |    {{$message->created_at->format('M d')}} </span>
                                                        </div>
                                                    </div>
                                            @endif
                                        @endForeach
                                    </div>
                                    <div class="type_msg">
                                        <div class="input_msg_write">
                                            <form class="mt-4" id="myForm">
                                                @csrf
                                                    <input type="text" id="hidden_value" hidden
                                                           value="{{$last_chat->user_id? $last_chat->user_id : $last_chat->delivery_man_id}}" name="">

                                                <textarea
                                                    class="form-control h-120"
                                                    id="msgInputValue"
                                                    type="text" placeholder="{{\App\CPU\translate('Send a message')}}"
                                                    aria-label="Search"></textarea>
                                                <div class="mt-3 d-flex justify-content-end">
                                                    <button class="aSend btn btn--primary" type="submit" id="msgSendBtn">{{\App\CPU\translate('Send_Reply')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Message Body -->
                            </div>
                        </div>
                    </div>

                </section>

            @else
                <div class="offset-md-1 col-md-10 d-flex justify-content-center align-items-center">
                    <p>{{\App\CPU\translate('No conversation found')}}</p>
                </div>
            @endif

        </div>


    </div>

@endsection

@push('script')
    <script>
        function messageGet(e){
            e.stopPropagation();
            user_id = e.target.id;
            let customer_name = $(e.target).attr('data-name');
            let customer_phone = $(e.target).attr('data-phone');
            let customer_image = $('#'+user_id).attr('src');

            $('#profile_name').text(customer_name)
            $('#profile_phone').text(customer_phone)
            $('#profile_image').attr("src", customer_image)

            //active when click on seller
            $('.chat_list.active').removeClass('active');
            $(`.user_${user_id}`).addClass("active");
            $('.seller').css('color', 'black');
            $(`.user_${user_id} h5`).css('color', '#377dff');
            // $('.inbox_chat').find('h5.active-text').removeClass(".active-text");

            let url;

            if ("{{ Request::is('seller/messages/chat/customer') }}" == true){
                url = "{{ route('seller.messages.ajax-message-by-user') }}" +"?user_id=" + user_id;
            }
            else if("{{ Request::is('seller/messages/chat/delivery-man') }}" == true) {
                url = "{{ route('seller.messages.ajax-message-by-user') }}" +"?delivery_man_id=" + user_id;
            }

            $.ajax({
                type: "get",
                url: url,

                success: function (data) {
                    $('.msg_history').html('');
                    $('.chat_ib').find('#' + user_id).removeClass('active-text');
                    //$(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 1000);

                    if (data.length != 0) {
                        data.map((element, index) => {
                            let dateTime = new Date(element.created_at);
                            let month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                            let time = dateTime.toLocaleTimeString().toLowerCase();
                            let date = month[dateTime.getMonth().toString()] + " " + dateTime.getDate().toString();

                            if (element.sent_by_seller) {
                                $(".msg_history").prepend(`
                                      <div class="outgoing_msg" id="outgoing_msg">
                                        <div class='sent_msg'>
                                          <p class="bg-c1 text-white rounded px-3 py-2 mb-1">${element.message}</p>
                                          <span class='time_date'> ${time}    |    ${date}</span>
                                        </div>
                                      </div>`
                                )

                            } else {
                                $(".msg_history").prepend(`
                                      <div class="incoming_msg" id="incoming_msg">
                                        <div class="received_msg">
                                          <div class="received_withd_msg">
                                            <p class="bg-chat rounded px-3 py-2 mb-1" id="receive_msg">${element.message}</p>
                                          <span class="time_date fz-12"> ${time}    |    ${date}</span></div>
                                        </div>
                                      </div>`
                                )
                            }

                            $('#hidden_value').attr("value", user_id);
                            $('#notif-alert-'+user_id).hide();
                        })
                    } else {
                        $(".msg_history").html(`<p> {{\App\CPU\translate('No Message available')}} </p>`);
                        data = [];
                    }

                }
            });

            $('.type_msg').css('display', 'block');
        }

        $(document).ready(function () {
            var user_id;

            //$(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 1000);

            $(".seller").click(function (e) {
                messageGet(e);
            });
            $(".seller-list").click(function (e) {
                messageGet(e);
            });

            $("#myInput").on("keyup", function (e) {
                var value = $(this).val().toLowerCase();
                $(".list_filter").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            //Chat Search Form
            $('#chat-search-form').on('submit', function(e) {
                e.preventDefault();
            });

            $("#msgSendBtn").click(function (e) {
                e.preventDefault();
                // get all the inputs into an array.
                var user_id = $('#hidden_value').val();
                var inputs = $('#myForm').find('#msgInputValue').val();
                let post_url;

                if ("{{ Request::is('seller/messages/chat/customer') }}" == true){
                    post_url = "{{ route('seller.messages.ajax-seller-message-store') }}" +"?user_id=" + user_id;
                }
                else if("{{ Request::is('seller/messages/chat/delivery-man') }}" == true) {
                    post_url = "{{ route('seller.messages.ajax-seller-message-store') }}" +"?delivery_man_id=" + user_id;
                }

                let data = {
                    message: inputs,
                    user_id: user_id
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: post_url,
                    data: data,
                    success: function (respons) {

                        let dateTime = new Date(respons.time);
                        let month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                        let time = dateTime.toLocaleTimeString().toLowerCase();
                        let date = month[dateTime.getMonth().toString()] + " " + dateTime.getDate().toString();

                        $(".msg_history").prepend(`
                                  <div class="outgoing_msg" id="outgoing_msg">
                                    <div class='sent_msg'>
                                      <p class="bg-c1 text-white rounded px-3 py-2 mb-1"">${respons.message}</p>
                                      <span class='time_date'> now </span>
                                    </div>
                                  </div>`
                        )
                    },
                    error: function (error) {
                        toastr.warning(error.responseText);
                    }
                });
                //scrolling
                //$(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 200);
                //remove value from input box
                $('#myForm').find('#msgInputValue').val('');
            });
        });
    </script>

@endpush

