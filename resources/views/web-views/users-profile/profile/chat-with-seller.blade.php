@extends('layouts.front-end.app')

@section('title')
{{ Request::is('chat/seller') ? \App\CPU\translate('chat_with_seller') : \App\CPU\translate('chat_with_delivery-man')}}
@endsection

@push('css_or_js')
    <style>

        .headind_srch {
            padding: {{Session::get('direction') === "rtl" ? '10px 20px 10px 29px' : '10px 29px 10px 20px'}};
        }
        .chat_ib {
            padding: {{Session::get('direction') === "rtl" ? '0 15px 6px 0' : '0 0 6px 15px'}};
        }
        .received_msg {
            padding: {{Session::get('direction') === "rtl" ? '0 10px 0 0' : '0 0 0 10px'}};
        }
        .received_withd_msg p {
            padding: {{Session::get('direction') === "rtl" ? '4px 10px 3px 8px' : '4px 8px 3px 10px'}};
        }
        .mesgs {
            padding: {{Session::get('direction') === "rtl" ? '30px 25px 0 15px' : '30px 15px 0 25px'}};
        }
        .send_msg p {
            padding: {{Session::get('direction') === "rtl" ? '5px 12px 5px 10px' : '5px 10px 5px 12px'}};
        }
        .send_msg {
            margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 10px;
        }
        @media (max-width: 600px) {
            .send_msg {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 7px;
            }
        }

    </style>

@endpush

@section('content')
<div class="__chat-seller">
    <!-- Page Title-->
    <div class="container rtl">
        <h3 class="text-center py-3 headerTitle m-0">{{ Request::is('chat/seller') ? \App\CPU\translate('chat_with_seller') : \App\CPU\translate('chat_with_delivery-man')}}</h3>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
            @include('web-views.partials._profile-aside')

            {{-- Seller List start --}}
            @if (isset($unique_shops))

                <div class="col-lg-3 chatSel">
                    <div class="card __shadow">
                        <div class="inbox_people">
                            <div class="headind_srch">
                                <form
                                    class="form-inline d-flex justify-content-center md-form form-sm active-cyan-2 mt-2">
                                    <input
                                        class="form-control form-control-sm border-0 {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}} w-75"
                                        id="myInput" type="text" placeholder="{{\App\CPU\translate('Search')}}" aria-label="Search">
                                    <i class="fa fa-search __color-92C6FF" aria-hidden="true"></i>
                                </form>
                                <hr>
                            </div>
                            <div class="inbox_chat">
                                @if (isset($unique_shops))
                                    @foreach($unique_shops as $key=>$shop)
                                        <div class="chat_list @if ($key == 0) btn--primary @endif"
                                             id="user_{{$shop->delivery_man_id ? $shop->delivery_man_id: $shop->shop_id}}">
                                            <div class="chat_people" id="chat_people">
                                                <div class="chat_img">
                                                    <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{ $shop->delivery_man_id ?asset('storage/app/public/delivery-man/'.$shop->image) : asset('storage/app/public/shop/'.$shop->image)}}"
                                                        class="__rounded-10">
                                                </div>
                                                <div class="chat_ib">
                                                    <h5 class="seller @if($shop->seen_by_customer)active-text @endif"
                                                        id="{{$shop->delivery_man_id ? $shop->delivery_man_id: $shop->shop_id}}">{{$shop->f_name? $shop->f_name. ' ' . $shop->l_name: $shop->name}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endForeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <section class="col-lg-6">
                    <div class="card Chat __shadow">
                        <div class="messaging">
                            <div class="inbox_msg">

                                <div class="mesgs">
                                    <div class="msg_history" id="show_msg">
                                        @if (isset($chattings))

                                            @foreach($chattings as $key => $chat)
                                                @if ($chat->sent_by_seller? $chat->sent_by_seller : $chat->sent_by_delivery_man)
                                                    <div class="incoming_msg">
                                                        <div class="incoming_msg_img"><img
                                                                src="@if($chat->image == 'def.png'){{asset('storage/app/public/'.$chat->image)}} @else {{ $shop->delivery_man_id ?asset('storage/app/public/delivery-man/'.$last_chat->delivery_man->image) : asset('storage/app/public/shop/'.$last_chat->shop->image)}}
                                                                @endif"
                                                                alt="sunil"></div>
                                                        <div class="received_msg">
                                                            <div class="received_withd_msg">
                                                                <p>
                                                                    {{$chat->message}}
                                                                </p>
                                                                <span class="time_date"> {{$chat->created_at->format('h:i A')}}    |    {{$chat->created_at->format('M d')}} </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @else

                                                    <div class="outgoing_msg">
                                                        <div class="send_msg">
                                                            <p class="btn--primary">
                                                                {{$chat->message}}
                                                            </p>
                                                            <span class="time_date"> {{$chat->created_at->format('h:i A')}}    |    {{$chat->created_at->format('M d')}} </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endForeach
                                            {{-- for scroll down --}}
                                            <div id="down"></div>
                                        @endif
                                    </div>
                                    <div class="type_msg">
                                        <div class="input_msg_write">
                                            <form
                                                class="form-inline d-flex justify-content-center md-form form-sm active-cyan-2 mt-2"
                                                id="myForm">
                                                @csrf
                                                @if( Request::is('chat/seller') )
                                                    <input type="text" id="hidden_value" hidden
                                                           value="{{$last_chat->shop_id}}" name="">
                                                    @if($last_chat->shop)
                                                        <input type="text" id="seller_value" hidden
                                                               value="{{$last_chat->shop->seller_id}}" name="">
                                                    @endif
                                                @elseif( Request::is('chat/delivery-man') )
                                                    <input type="text" id="hidden_value_dm" hidden
                                                           value="{{$last_chat->delivery_man_id}}" name="">
                                                @endif
                                                <input class="form-control form-control-sm {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}} w-75"
                                                    id="msgInputValue" type="text" placeholder="{{\App\CPU\translate('Send a message')}}" aria-label="Search">
                                                <input class="aSend __w-45px" type="submit" id="msgSendBtn" value="{{\App\CPU\translate('Send')}}">

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @else
                <div class="col-md-8 d-flex justify-content-center align-items-center">
                    <p>{{\App\CPU\translate('No conversation found')}}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>
        $(document).ready(function () {
            var shop_id;
            $(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 1000);

            $(".seller").click(function (e) {
                e.stopPropagation();
                shop_id = e.target.id;
                console.log(shop_id)
                //active when click on seller
                $('.chat_list.btn--primary').removeClass('btn--primary');
                $(`#user_${shop_id}`).addClass("btn--primary");
                $('.seller').css('color', 'black');
                $(`#user_${shop_id} h5`).css('color', 'white');

                let url;

                if ("{{ Request::is('chat/seller') }}" == true){
                    url = "{{ route('messages') }}" +"?shop_id=" + shop_id;
                }
                else if("{{ Request::is('chat/delivery-man') }}" == true) {
                    url = "{{ route('messages') }}" +"?delivery_man_id=" + shop_id;
                }


                $.ajax({
                    type: "get",
                    url: url,
                    success: function (data) {
                        $('.msg_history').html('');
                        $('.chat_ib').find('#' + shop_id).removeClass('active-text');

                        if (data.length != 0) {
                            data.map((element, index) => {
                                let dateTime = new Date(element.created_at);
                                var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                                let time = dateTime.toLocaleTimeString().toLowerCase();
                                let date = month[dateTime.getMonth().toString()] + " " + dateTime.getDate().toString();

                                if (element.sent_by_customer) {

                                    $(".msg_history").append(`
                                        <div class="outgoing_msg">
                                          <div class='send_msg'>
                                            <p class="btn--primary">${element.message}</p>
                                            <span class='time_date'> ${time}    |    ${date}</span>
                                          </div>
                                        </div>`
                                    )

                                } else {
                                    let img_path = element.image == 'def.png' ? `{{ asset('storage/app/public/shop') }}/${element.image}` : `{{ (isset($shop->delivery_man_id) && $shop->delivery_man_id) ? asset('storage/app/public/delivery-man') : asset('storage/app/public/shop') }}/${element.image}`;

                                    $(".msg_history").append(`
                                        <div class="incoming_msg d-flex" id="incoming_msg">
                                          <div class="incoming_msg_img" id="">
                                            <img src="${img_path}" alt="">
                                          </div>
                                          <div class="received_msg">
                                            <div class="received_withd_msg">
                                              <p id="receive_msg">${element.message}</p>
                                            <span class="time_date">${time}    |    ${date}</span></div>
                                          </div>
                                        </div>`
                                    )
                                }
                                $('#hidden_value').attr("value", shop_id);
                            });
                        } else {
                            $(".msg_history").html(`<p> No Message available </p>`);
                            data = [];
                        }
                        // data = "";
                        // $('.msg_history > div').remove();

                    }
                });

                $('.type_msg').css('display', 'block');
                $(".msg_history").stop().animate({scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);

            });

            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".chat_list").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $("#msgSendBtn").click(function (e) {
                e.preventDefault();
                // get all the inputs into an array.
                var inputs = $('#myForm').find('#msgInputValue').val();
                var new_shop_id = $('#myForm').find('#hidden_value').val();
                var new_seller_id = $('#myForm').find('#seller_value').val();


                let data = {
                    message: inputs,
                    shop_id: new_shop_id,
                    seller_id: new_seller_id,
                    delivery_man_id: $('#myForm').find('#hidden_value_dm').val(),
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('messages_store')}}',
                    data: data,
                    success: function (respons) {
                        $(".msg_history").append(`
                            <div class="outgoing_msg" id="outgoing_msg">
                              <div class='send_msg'>
                                <p class="btn--primary">${respons}</p>
                                <span class='time_date'> now</span>
                              </div>
                            </div>`
                        )
                    },
                    error: function (error) {
                        toastr.warning(error.responseJSON)
                    }
                });
                $('#myForm').find('#msgInputValue').val('');
                $(".msg_history").stop().animate({scrollTop: $(".msg_history")[0].scrollHeight}, 1000);

            });
        });
    </script>

@endpush

