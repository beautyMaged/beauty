@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Address'))

@push('css_or_js')
    <link rel="stylesheet" media="screen"
          href="{{asset('assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/bootstrap-select.min.css') }}">

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
<div class="container pb-5 mb-2 mb-md-4 rtl __account-address" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
    <h2 class="text-center py-3 m-0 headerTitle">{{\App\CPU\translate('UPDATE_ADDRESSES')}}</h2>
    <div class="row">
        <!-- Sidebar-->
    @include('web-views.partials._profile-aside')
    <section class="col-lg-9 col-md-9">

            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <form action="{{route('address-update')}}" method="post">
                            @csrf
                            <div class="row pb-1">
                                <div class="col-md-6">
                                    <!-- Nav pills -->
                                    <input type="hidden" name="id" value="{{$shippingAddress->id}}">
                                    <ul class="donate-now">
                                        <li class="address_type_li">
                                            <input type="radio" class="address_type" id="a25" name="addressAs" value="permanent"  {{ $shippingAddress->address_type == 'permanent' ? 'checked' : ''}} />
                                            <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                        </li>
                                        <li class="address_type_li">
                                            <input type="radio" class="address_type" id="a50" name="addressAs" value="home" {{ $shippingAddress->address_type == 'home' ? 'checked' : ''}} />
                                            <label for="a50" class="component">{{\App\CPU\translate('Home Address')}}</label>
                                        </li>
                                        <li class="address_type_li">
                                            <input type="radio" class="address_type" id="a75" name="addressAs" value="office" {{ $shippingAddress->address_type == 'office' ? 'checked' : ''}}/>
                                            <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <!-- Nav pills -->
                                    <input type="hidden" id="is_billing" value="{{$shippingAddress->is_billing}}">
                                    <ul class="donate-now">
                                        <li class="address_type_bl">
                                            <input type="radio" class="bill_type" id="b25" name="is_billing" value="0"  {{ $shippingAddress->is_billing == '0' ? 'checked' : ''}} />
                                            <label for="b25" class="component">{{\App\CPU\translate('shipping')}}</label>
                                        </li>
                                        <li class="address_type_bl">
                                            <input type="radio" class="bill_type" id="b50" name="is_billing" value="1" {{ $shippingAddress->is_billing == '1' ? 'checked' : ''}} />
                                            <label for="b50" class="component">{{\App\CPU\translate('billing')}}</label>
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
                                <div class="form-group col-md-12">
                                    <label for="city">{{\App\CPU\translate('City')}}</label>

                                    <input class="form-control" type="text" id="city" name="city" value="{{$shippingAddress->city}}" required>
                                </div>
                                <div class="form-group col-md-6 d-none">
                                    <label for="zip_code">{{\App\CPU\translate('zip_code')}}</label>
                                    @if($zip_restrict_status)
                                        <select name="zip" class="form-control selectpicker" data-live-search="true" id="" required>
                                            @foreach($delivery_zipcodes as $zip)
                                                <option value="{{ $zip->zipcode }}" {{ $zip->zipcode == $shippingAddress->zip? 'selected' : ''}}>{{ $zip->zipcode }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input class="form-control" type="text" id="zip_code" name="zip" value="{{$shippingAddress->zip}}" required>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 d-none">
                                    <label for="city">{{\App\CPU\translate('Country')}}</label>
                                    <input name="country" class="form-control selectpicker" value="السعودية" data-live-search="true" id="" required>

                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="own_address">{{\App\CPU\translate('address')}}</label>
                                    <textarea class="form-control" id="address"
                                        type="text"  name="address" required>{{$shippingAddress->address}}</textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <input id="pac-input" class="controls rounded __inline-46" title="{{\App\CPU\translate('search_your_location_here')}}" type="text" placeholder="{{\App\CPU\translate('search_here')}}"/>
                                    <div class="__h-200px" id="location_map_canvas"></div>
                                </div>
                            </div>
                            @php($shipping_latitude=$shippingAddress->latitude)
                            @php($shipping_longitude=$shippingAddress->longitude)
                            <input type="hidden" id="latitude"
                                name="latitude" class="form-control d-inline"
                                placeholder="Ex : -94.22213" value="{{$shipping_latitude??0}}" required readonly>
                            <input type="hidden"
                                name="longitude" class="form-control"
                                placeholder="Ex : 103.344322" id="longitude" value="{{$shipping_longitude??0}}" required readonly>
                            <div class="modal-footer">
                                <a href="{{ route('account-address') }}" class="closeB btn btn-secondary">{{\App\CPU\translate('close')}}</a>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('update')}}  </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </section>
</div>
@endsection

@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&libraries=places&v=3.49"></script>
<script src="{{ asset('public/assets/front-end/js/bootstrap-select.min.js') }}"></script>
<script>

    function initAutocomplete() {
        var myLatLng = { lat: {{$shipping_latitude??'-33.8688'}}, lng: {{$shipping_longitude??'151.2195'}} };

        const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
            center: { lat: {{$shipping_latitude??'-33.8688'}}, lng: {{$shipping_longitude??'151.2195'}} },
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
