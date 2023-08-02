@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Address'))

@push('css_or_js')
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
    <link rel="stylesheet" href="{{ asset('public/assets/front-end/css/bootstrap-select.min.css') }}">

    <style>
        .cz-sidebar-body h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}} !important;
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: {{$web_config['primary_color']}};
        }

        .iconHad {
            color: {{$web_config['primary_color']}};
        }
        .namHad {
            padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 13px;
        }
        .modal-backdrop {
            z-index: 0 !important;
            display: none;
        }
        .donate-now li {
            margin: {{Session::get('direction') === "rtl" ? '0 0 0 5px' : '0 5px 0 0'}};
        }
        .donate-now input[type="radio"]:checked + label,
        .Checked + label {
            background: {{$web_config['primary_color']}};
        }
        .filter-option{
            display: block;
            width: 100%;
            height: calc(1.5em + 1.25rem + 2px);
            padding: 0.625rem 1rem;
            font-size: .9375rem;
            font-weight: 400;
            line-height: 1.5;
            color: #4b566b;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #dae1e7;
            border-radius: 0.3125rem;
            box-shadow: 0 0 0 0 transparent;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .btn-light + .dropdown-menu{
            transform: none !important;
            top: 41px !important;
        }
    </style>
@endpush

@section('content')
    <div class="__account-address">
        <div class="modal fade rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-name">{{\App\CPU\translate('add_new_address')}}</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('address-store')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Nav pills -->
                                    <ul class="donate-now d-flex">
                                        <li>
                                            <input type="radio" id="a25" name="addressAs" value="permanent"/>
                                            <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="a50" name="addressAs" value="home"/>
                                            <label for="a50" class="component">{{\App\CPU\translate('Home Address')}}</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="a75" name="addressAs" value="office" checked="checked"/>
                                            <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                        </li>

                                    </ul>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <!-- Nav pills -->

                                <ul class="donate-now">
                                    <li>
                                        <input type="radio" name="is_billing" id="b25" value="0" checked/>
                                        <label for="b25" class="billing_component">{{\App\CPU\translate('shipping')}}</label>
                                    </li>
                                    <li>
                                        <input type="radio" name="is_billing" id="b50" value="1"/>
                                        <label for="b50" class="billing_component">{{\App\CPU\translate('billing')}}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">{{\App\CPU\translate('contact_person_name')}}</label>
                                            <input class="form-control" type="text" id="name" name="name" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{\App\CPU\translate('Phone')}}</label>
                                            <input class="form-control" type="text" id="phone" name="phone" required>
                                        </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address-city">{{\App\CPU\translate('City')}}</label>
                                        <input class="form-control" type="text" id="address-city" name="city" required>
                                    </div>
                                    <div class="form-group col-md-6 d-none">
                                        <label for="zip">{{\App\CPU\translate('zip_code')}}</label>
                                        @if($zip_restrict_status)
                                            <select name="zip" id="" class="form-control selectpicker" data-live-search="true">
                                                @foreach($zip_codes as $code)
                                                    <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input class="form-control" type="text" id="zip" name="zip" value="11011" required>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row d-none">
                                    <div class="form-group col-md-12 ">
                                        <label for="address-city">{{\App\CPU\translate('Country')}}</label>
                                        <input name="country" id="" value="السعودية" class="form-control selectpicker" data-live-search="true">

                                    </div>
                                </div>
                                    @if(auth('customer')->check() && auth('customer')->user()->street_address != null)

                                        <div class="form-group col-md-12">
                                            <label for="address">{{\App\CPU\translate('address')}}</label>

                                            <textarea class="form-control" id="address"
                                                      type="text"  name="address" required>{{auth('customer')->user()->street_address}}</textarea>
                                        </div>
                                    @elseif(Session::has('current_location'))


                                        <div class="form-group col-md-12">
                                            <label for="address">{{\App\CPU\translate('address')}}</label>

                                            <textarea class="form-control" id="address"
                                                      type="text"  name="address" required>{{Session::get('current_location')}}</textarea>
                                        </div>
                                    @else
                                        <div class="form-group col-md-12">
                                            <label for="address">{{\App\CPU\translate('address')}}</label>

                                            <textarea class="form-control" id="address"
                                                      type="text"  name="address" required></textarea>
                                        </div>
                                    @endif
                                <div class="form-row">







                                    @php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
                                    <div class="form-group col-md-12">
                                        <input id="pac-input" class="controls rounded __inline-46" title="{{\App\CPU\translate('search_your_location_here')}}" type="text" placeholder="{{\App\CPU\translate('search_here')}}"/>
                                        <div class="__h-200px" id="location_map_canvas"></div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="latitude"
                                name="latitude" class="form-control d-inline"
                                placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                            <input type="hidden"
                                name="longitude" class="form-control"
                                placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Add')}} {{\App\CPU\translate('Informations')}}  </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </div>

        <!-- Page Content-->
        <div class="container pb-5 mb-2 rtl">
            <h3 class="py-3 text-center headerTitle">{{\App\CPU\translate('addresses')}}</h3>
            <div class="row">
                <!-- Sidebar-->
            @include('web-views.partials._profile-aside')
            <!-- Content  -->
                <section class="col-lg-9 col-md-9">

                    <!-- Addresses list-->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn--primary" data-toggle="modal"
                            data-target="#exampleModal" id="add_new_address">{{\App\CPU\translate('add_new_address')}}
                        </button>
                    </div>
                    <div class="row g-3">
                    @foreach($shippingAddresses as $shippingAddress)
                        <section class="col-lg-6 col-md-6">
                            <div class="card __shadow h-100">

                                    <div class="card-header d-flex justify-content-between d-flex align-items-center">
                                        <div>
                                            <i class="fa fa-thumb-tack fa-2x iconHad" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <span>  {{\App\CPU\translate('address')}} {{\App\CPU\translate($shippingAddress['address_type'])}} ({{$shippingAddress['is_billing']==1?\App\CPU\translate('Billing_address'):\App\CPU\translate('shipping_address')}}) </span>
                                        </div>

                                        <div class="d-flex justify-content-between">


                                                <a class="" title="Edit Address" id="edit" href="{{route('address-edit',$shippingAddress->id)}}">
                                                    <i class="fa fa-edit fa-lg"></i>
                                                </a>

                                                <a class="" title="Delete Address" href="{{ route('address-delete',['id'=>$shippingAddress->id])}}" onclick="return confirm('{{\App\CPU\translate('Are you sure you want to Delete')}}?');" id="delete">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </a>

                                        </div>
                                    </div>


                                    {{-- Modal Address Edit --}}
                                    <div class="modal fade" id="editAddress_{{$shippingAddress->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog  modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <div class="row">
                                                    <div class="col-md-12"> <h5 class="modal-title font-name ">{{\App\CPU\translate('update')}} {{\App\CPU\translate('address')}}  </h5></div>
                                                </div>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="updateForm">
                                                        @csrf
                                                        <div class="row pb-1">
                                                            <div class="col-md-6 d-flex">
                                                                <!-- Nav pills -->
                                                                <input type="hidden" id="defaultValue" class="add_type" value="{{$shippingAddress->address_type}}">
                                                                <ul class="donate-now">
                                                                    <li class="address_type_li">
                                                                        <input type="radio" class="address_type" id="a25" name="addressAs" value="permanent"  {{ $shippingAddress->address_type == 'permanent' ? 'checked' : ''}} />
                                                                        <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                                                    </li>
                                                                    <li class="address_type_li">
                                                                        <input type="radio" class="address_type" id="a50" name="addressAs" value="home" {{ $shippingAddress->address_type == 'home' ? 'checked' : ''}} />
                                                                        <label for="a50" class="component">{{\App\CPU\translate('Home')}}</label>
                                                                    </li>
                                                                    <li class="address_type_li">
                                                                        <input type="radio" class="address_type" id="a75" name="addressAs" value="office" {{ $shippingAddress->address_type == 'office' ? 'checked' : ''}}/>
                                                                        <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                        </div>
                                                        <!-- Tab panes -->
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="person_name">{{\App\CPU\translate('contact_person_name')}}</label>
                                                                <input class="form-control" type="text" id="person_name"
                                                                    name="name"
                                                                    value="{{$shippingAddress->contact_person_name}}"
                                                                    required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="own_phone">{{\App\CPU\translate('Phone')}}</label>
                                                                <input class="form-control" type="text" id="own_phone" name="phone" value="{{$shippingAddress->phone}}" required="required">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="city">{{\App\CPU\translate('City')}}</label>

                                                                    <input class="form-control" type="text" id="city" name="city" value="{{$shippingAddress->city}}" required>
                                                                </div>
                                                                <div class="form-group col-md-6 d-none">
                                                                    <label for="zip_code">{{\App\CPU\translate('zip_code')}}</label>
                                                                    <input class="form-control" type="text" id="zip_code" name="zip" value="{{$shippingAddress->zip}}" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                <label for="own_state">{{\App\CPU\translate('State')}}</label>
                                                                    <input type="text" class="form-control" name="state" value="{{ $shippingAddress->state }}" id="own_state"  placeholder="" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                <label for="own_country">{{\App\CPU\translate('Country')}}</label>
                                                                    <input type="text" class="form-control" id="own_country" name="country" value="{{ $shippingAddress->country }}" placeholder="" required>
                                                                </div>
                                                            </div>



                                                            <div class="form-row">

                                                                <div class="form-group col-md-12">
                                                                    <label for="own_address">{{\App\CPU\translate('address')}}</label>
                                                                    <input class="form-control" type="text" id="own_address"
                                                                        name="address"
                                                                        value="{{$shippingAddress->address}}" required>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" id="latitude"
                                                                name="latitude" class="form-control d-inline"
                                                                placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                                                            <input type="hidden"
                                                                name="longitude" class="form-control"
                                                                placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                                                            <div class="modal-footer">
                                                                <button type="button" class="closeB btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                                                <button type="submit" class="btn btn--primary" id="addressUpdate" data-id="{{$shippingAddress->id}}">{{\App\CPU\translate('update')}}  </button>
                                                            </div>
                                                        </form>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="card-body">
                                        <div class="font-name"><span>{{$shippingAddress['contact_person_name']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('Phone')}}  :</strong>  {{$shippingAddress['phone']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('City')}}  :</strong>  {{$shippingAddress['city']}}</span>
                                        </div>
{{--                                        <div><span class="font-nameA"> <strong> {{\App\CPU\translate('zip_code')}} :</strong> {{$shippingAddress['zip']}}</span>--}}
{{--                                        </div>--}}
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('address')}} :</strong> {{$shippingAddress['address']}}</span>
                                        </div>
{{--                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('country')}} :</strong> {{$shippingAddress['country']}}</span>--}}
{{--                                        </div>--}}

                                    </div>

                            </div>
                        </section>
                    @endforeach
                </div>
        </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/front-end/js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('.address_type_li').on('click', function (e) {
                // e.preventDefault();
                $('.address_type_li').find('.address_type').removeAttr('checked', false);
                $('.address_type_li').find('.component').removeClass('active_address_type');
                $(this).find('.address_type').attr('checked', true);
                $(this).find('.address_type').removeClass('add_type');
                $('#defaultValue').removeClass('add_type');
                $(this).find('.address_type').addClass('add_type');

                $(this).find('.component').addClass('active_address_type');
            });
        })

        $('#addressUpdate').on('click', function(e){
            e.preventDefault();
            let addressAs, address, name, zip, city, state, country, phone;

            addressAs = $('.add_type').val();

            address = $('#own_address').val();
            name = $('#person_name').val();
            zip = $('#zip_code').val();
            city = $('#city').val();
            state = $('#own_state').val();
            country = $('#own_country').val();
            phone = $('#own_phone').val();

            let id = $(this).attr('data-id');

            if (addressAs != '' && address != '' && name != '' && zip != '' && city != '' && state != '' && country != '' && phone != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('address-update')}}",
                    method: 'POST',
                    data: {
                        id : id,
                        addressAs: addressAs,
                        address: address,
                        name: name,
                        zip: zip,
                        city: city,
                        state: state,
                        country: country,
                        phone: phone
                    },
                    success: function () {
                        toastr.success('{{\App\CPU\translate('Address Update Successfully')}}.');
                        location.reload();


                    }
                });
            }else{
                toastr.error('{{\App\CPU\translate('All input field required')}}.');
            }

        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&libraries=places&v=3.49"></script>
    <script>

        function initAutocomplete() {
            var myLatLng = { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocomplete();

        });

        $(document).on("keydown", "input", function(e) {
          if (e.which==13) e.preventDefault();
        });
    </script>
@endpush
