<!-- Modal -->
@php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
<div class="modal fade" id="location_modal" tabindex="-1" aria-labelledby="location_modal" aria-hidden="true" style="z-index: 2000;" dir="rtl">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body pt-3">
                <div class="row" id="form_location" style="display: none">
                    <form action="{{route('confirm.location')}}" method="post" class="col-lg-12 col-12">
                        @csrf
                        <div class="row mb-3" style="">
                            <div class="col-lg-10 col-8">
                                <input class="form-control" id="head_address" type="text" name="head_address"> <input class="form-control" id="head_city" type="hidden" name="head_city"> <input class="form-control" id="head_country" type="hidden" name="head_country"> <input class="form-control" id="head_new_lat" type="hidden" name="head_new_lat"> <input class="form-control" id="head_new_long" type="hidden" name="head_new_long">
                            </div>
                            <div class="col-lg-2 col-4">
                                <button class="btn btn-danger w-100" id="confirm_btn" type="submit">{{\App\CPU\translate('confirm')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row pt-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-6 text-right">
                                <h4 class="bold s_15"> {{\App\CPU\translate('Use New Address')}} </h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-6 text-left">
                                <h4 class="bold s_19" id="map_lister">
                                    {{\App\CPU\translate('Deliver to ')}}
                                    <span class="second_color" id="main_address">
                                    @if(auth('customer')->check())
                                            {{auth('customer')->user()->city}}
                                        @else
                                            @if(session::has('current_city'))
                                                {{Session::get('current_city')}}
                                            @else
                                                {{\App\CPU\translate('riyad')}}
                                            @endif
                                        @endif
                                    </span>
                                </h4>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center" style="">
                        <input id="pac-input-main" class="controls rounded __inline-46" title="{{\App\CPU\translate('Type Address Here')}}" type="text" placeholder="{{\App\CPU\translate('Type Address Here')}}" style="padding-right: 11px;"/>
                        <input id="pac-input-new-main" class="controls rounded __inline-46" title="{{\App\CPU\translate('Type Address Here')}}" type="text" placeholder="{{\App\CPU\translate('Type Address Here')}}" style="padding-right: 11px;"/>
                        <div class="__h-400px" id="location_map_canvas_main"></div>
                    </div>
                    <input type="hidden" id="main_latitude" name="main_latitude" class="form-control d-inline" placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                    <input type="hidden" name="main_longitude" class="form-control" placeholder="Ex : 103.344322" id="main_longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                    <button type="submit" class="btn btn--primary" style="display: none" id="address_main_submit"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="user_lat">
<input type="hidden" id="user_lng">

@push('script')
    <script

        src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&libraries=places&callback=initMap&v=3.49"></script>
    <script>


        @if(auth('customer')->check() && auth('customer')->user()->street_address != null)

        {{--function initAutocompleteMain() {--}}
        {{--    var myLatLng = {--}}
        {{--        lat: {{auth('customer')->user()->lat ?auth('customer')->user()->lat:$default_location['lat']}},--}}
        {{--        lng: {{auth('customer')->user()->long?auth('customer')->user()->long:$default_location['lng']}}--}}
        {{--    };--}}

        {{--    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {--}}
        {{--        center: {--}}
        {{--            lat: {{auth('customer')->user()->lat ?auth('customer')->user()->lat:$default_location['lat']}},--}}
        {{--            lng: {{auth('customer')->user()->long?auth('customer')->user()->long:$default_location['lng']}}--}}
        {{--        },--}}
        {{--        zoom: 13,--}}
        {{--        mapTypeId: "roadmap",--}}
        {{--    });--}}

        {{--    var marker = new google.maps.Marker({--}}
        {{--        position: myLatLng,--}}
        {{--        map: map,--}}
        {{--    });--}}

        {{--    marker.setMap(map);--}}
        {{--    var geocoder = geocoder = new google.maps.Geocoder();--}}


        {{--    var latlng = new google.maps.LatLng({{auth('customer')->user()->lat ?auth('customer')->user()->lat:$default_location['lat']}}, {{auth('customer')->user()->long?auth('customer')->user()->long:$default_location['lng']}});--}}

        {{--    const locationButton = document.createElement("button");--}}

        {{--    locationButton.textContent = "{{\App\CPU\translate('My location')}}";--}}
        {{--    locationButton.classList.add("custom-map-control-button");--}}
        {{--    map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);--}}


        {{--    locationButton.addEventListener("click", () => {--}}
        {{--        // Try HTML5 geolocation.--}}
        {{--        // $('#loading').parent().parent().css({--}}
        {{--        //     'width': '100%',--}}
        {{--        //     'height': '100vh',--}}
        {{--        //     'z-index': '100000;',--}}
        {{--        //     'position': 'fixed;',--}}
        {{--        //     'top': '0',--}}
        {{--        //     'background': 'rgba(255,255,255,0.18)',--}}
        {{--        // });--}}
        {{--        // $('#loading').show();--}}
        {{--        navigator.geolocation.getCurrentPosition(--}}
        {{--            function (position) {--}}
        {{--                var user_lat = position.coords.latitude;--}}
        {{--                var user_long = position.coords.longitude;--}}

        {{--                // alert(current_lat);--}}

        {{--                var latlng = new google.maps.LatLng(user_lat, user_long);--}}

        {{--                saveCurrentLocation(latlng, user_lat, user_long);--}}


        {{--            },--}}
        {{--            function errorCallback(error) {--}}
        {{--                console.log(error)--}}
        {{--                document.getElementById('main_latitude').value = current_lat;--}}
        {{--                document.getElementById('main_longitude').value = current_long;--}}

        {{--                var myLatLng = {--}}
        {{--                    lat: {{$default_location?$default_location['lat']:'-33.8688'}},--}}
        {{--                    lng: {{$default_location?$default_location['lng']:'151.2195'}}--}}
        {{--                };--}}

        {{--                const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {--}}
        {{--                    center: {--}}
        {{--                        lat: {{$default_location?$default_location['lat']:'-33.8688'}},--}}
        {{--                        lng: {{$default_location?$default_location['lng']:'151.2195'}}--}}
        {{--                    },--}}
        {{--                    zoom: 13,--}}
        {{--                    mapTypeId: "roadmap",--}}
        {{--                });--}}

        {{--                var marker = new google.maps.Marker({--}}
        {{--                    position: myLatLng,--}}
        {{--                    map: map,--}}
        {{--                });--}}

        {{--                marker.setMap(map);--}}
        {{--                var geocoder = geocoder = new google.maps.Geocoder();--}}
        {{--                var latlng = new google.maps.LatLng(current_lat, current_long);--}}

        {{--                google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {--}}
        {{--                    var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);--}}
        {{--                    var coordinates = JSON.parse(coordinates);--}}
        {{--                    var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);--}}
        {{--                    marker.setPosition(latlng);--}}
        {{--                    map.panTo(latlng);--}}

        {{--                    document.getElementById('main_latitude').value = coordinates['lat'];--}}
        {{--                    document.getElementById('main_longitude').value = coordinates['lng'];--}}

        {{--                    geocoder.geocode({'latLng': latlng}, function (results, status) {--}}
        {{--                        if (status == google.maps.GeocoderStatus.OK) {--}}
        {{--                            if (results[10]) {--}}
                                        // console.log(results);

        {{--                                var address_array = results[2].formatted_address.split(',');--}}
        {{--                                var arr_count = address_array.length;--}}
        {{--                                console.log(address_array.length);--}}
        {{--                                console.log(address_array[arr_count - 1]);--}}
        {{--                                console.log(address_array[arr_count - 3]);--}}

        {{--                                var street = results[2].formatted_address;--}}
        {{--                                var city = "";--}}
        {{--                                if (address_array[arr_count - 3] === undefined) {--}}
        {{--                                    city = address_array[arr_count - 2];--}}
        {{--                                } else {--}}
        {{--                                    city = address_array[arr_count - 3];--}}
        {{--                                }--}}
        {{--                                var country = address_array[arr_count - 1];--}}
        {{--                                document.getElementById('main_address').innerHTML = city;--}}
        {{--                                document.getElementById('form_location').style.display = 'block';--}}

        {{--                                document.getElementById('head_address').value = street;--}}
        {{--                                document.getElementById('head_city').value = city;--}}
        {{--                                document.getElementById('head_country').value = country;--}}
        {{--                                document.getElementById('head_new_lat').value = coordinates['lat'];--}}
        {{--                                document.getElementById('head_new_long').value = coordinates['lng'];--}}


        {{--                                // console.log(results);--}}
        {{--                            }--}}
        {{--                        }--}}
        {{--                    });--}}
        {{--                });--}}
        {{--                // Create the search box and link it to the UI element.--}}
        {{--                const input = document.getElementById("pac-input-main");--}}
        {{--                const searchBox = new google.maps.places.SearchBox(input);--}}
        {{--                map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);--}}
        {{--                // Bias the SearchBox results towards current map's viewport.--}}
        {{--                map.addListener("bounds_changed", () => {--}}
        {{--                    searchBox.setBounds(map.getBounds());--}}
        {{--                });--}}
        {{--                let markers = [];--}}
        {{--                // Listen for the event fired when the user selects a prediction and retrieve--}}
        {{--                // more details for that place.--}}
        {{--                searchBox.addListener("places_changed", () => {--}}
        {{--                    const places = searchBox.getPlaces();--}}

        {{--                    if (places.length == 0) {--}}
        {{--                        return;--}}
        {{--                    }--}}
        {{--                    // Clear out the old markers.--}}
        {{--                    markers.forEach((marker) => {--}}
        {{--                        marker.setMap(null);--}}
        {{--                    });--}}
        {{--                    markers = [];--}}
        {{--                    // For each place, get the icon, name and location.--}}
        {{--                    const bounds = new google.maps.LatLngBounds();--}}
        {{--                    places.forEach((place) => {--}}
        {{--                        if (!place.geometry || !place.geometry.location) {--}}
        {{--                            console.log("Returned place contains no geometry");--}}
        {{--                            return;--}}
        {{--                        }--}}
        {{--                        var mrkr = new google.maps.Marker({--}}
        {{--                            map,--}}
        {{--                            title: place.name,--}}
        {{--                            position: place.geometry.location,--}}
        {{--                        });--}}

        {{--                        google.maps.event.addListener(mrkr, "click", function (event) {--}}
        {{--                            document.getElementById('main_latitude').value = this.position.lat();--}}
        {{--                            document.getElementById('main_longitude').value = this.position.lng();--}}

        {{--                        });--}}

        {{--                        markers.push(mrkr);--}}

        {{--                        if (place.geometry.viewport) {--}}
        {{--                            // Only geocodes have viewport.--}}
        {{--                            bounds.union(place.geometry.viewport);--}}
        {{--                        } else {--}}
        {{--                            bounds.extend(place.geometry.location);--}}
        {{--                        }--}}
        {{--                    });--}}
        {{--                    map.fitBounds(bounds);--}}

        {{--                });--}}
        {{--            }--}}
        {{--        );--}}


        {{--    });--}}


        {{--    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {--}}
        {{--        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);--}}
        {{--        var coordinates = JSON.parse(coordinates);--}}
        {{--        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);--}}
        {{--        marker.setPosition(latlng);--}}
        {{--        map.panTo(latlng);--}}

        {{--        document.getElementById('main_latitude').value = coordinates['lat'];--}}
        {{--        document.getElementById('main_longitude').value = coordinates['lng'];--}}

        {{--        geocoder.geocode({'latLng': latlng}, function (results, status) {--}}
        {{--            if (status == google.maps.GeocoderStatus.OK) {--}}
        {{--                if (results[10]) {--}}
                            // console.log(results);

        {{--                    var address_array = results[2].formatted_address.split(',');--}}
        {{--                    var arr_count = address_array.length;--}}
        {{--                    console.log(address_array.length);--}}
        {{--                    console.log(address_array[arr_count - 1]);--}}
        {{--                    console.log(address_array[arr_count - 3]);--}}

        {{--                    var street = results[2].formatted_address;--}}
        {{--                    var city = "";--}}
        {{--                    if (address_array[arr_count - 3] === undefined) {--}}
        {{--                        city = address_array[arr_count - 2];--}}
        {{--                    } else {--}}
        {{--                        city = address_array[arr_count - 3];--}}
        {{--                    }--}}
        {{--                    var country = address_array[arr_count - 1];--}}
        {{--                    document.getElementById('main_address').innerHTML = city;--}}
        {{--                    document.getElementById('form_location').style.display = 'block';--}}

        {{--                    document.getElementById('head_address').value = street;--}}
        {{--                    document.getElementById('head_city').value = city;--}}
        {{--                    document.getElementById('head_country').value = country;--}}
        {{--                    document.getElementById('head_new_lat').value = coordinates['lat'];--}}
        {{--                    document.getElementById('head_new_long').value = coordinates['lng'];--}}


        {{--                    // console.log(results);--}}
        {{--                }--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--    function saveCurrentLocation(latLng, current_lat, current_long) {--}}

        {{--        var myLatLng = {--}}
        {{--            lat: current_lat,--}}
        {{--            lng: current_long--}}
        {{--        };--}}

        {{--        geocoder.geocode({'latLng': myLatLng}, function (results, status) {--}}
        {{--            if (status == google.maps.GeocoderStatus.OK) {--}}
        {{--                if (results) {--}}

        {{--                    var address_array = results[2].formatted_address.split(',');--}}
        {{--                    console.log(address_array);--}}

        {{--                    var arr_count = address_array.length;--}}
        {{--                    console.log(address_array.length);--}}
        {{--                    console.log(address_array[arr_count - 1]);--}}
        {{--                    console.log(address_array[arr_count - 3]);--}}

        {{--                    var street = results[2].formatted_address;--}}
        {{--                    var city = "";--}}
        {{--                    if (address_array[arr_count - 3] === undefined) {--}}
        {{--                        city = address_array[arr_count - 2];--}}
        {{--                    } else {--}}
        {{--                        city = address_array[arr_count - 3];--}}
        {{--                    }--}}
        {{--                    var country = address_array[arr_count - 1];--}}
        {{--                    document.getElementById('main_address').innerHTML = city;--}}
        {{--                    document.getElementById('header_loc_mob').innerHTML = city;--}}
        {{--                    document.getElementById('header_loc_pc').innerHTML = city;--}}
        {{--                    // document.getElementById('form_location').style.display =Sessionauth('customer')->check() ? auth('customer')->user()->city : ::get('current_city')}}')}}"}

        {{--                    document.getElementById('head_address').value = street;--}}
        {{--                    document.getElementById('head_city').value = city;--}}
        {{--                    document.getElementById('head_country').value = country;--}}
        {{--                    document.getElementById('head_new_lat').value = current_lat;--}}
        {{--                    document.getElementById('head_new_long').value = current_long;--}}

        {{--                    document.getElementById('form_location').style.display = 'block';--}}


        {{--                    // here We sent Ajax request to save location into session or to user and his locations--}}
        {{--                    // $.ajax();--}}



        {{--                }--}}
        {{--            }--}}
        {{--        });--}}

        {{--    }--}}


        {{--    // Create the search box and link it to the UI element.--}}
        {{--    const input = document.getElementById("pac-input-main");--}}
        {{--    const searchBox = new google.maps.places.SearchBox(input);--}}
        {{--    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);--}}
        {{--    // Bias the SearchBox results towards current map's viewport.--}}
        {{--    map.addListener("bounds_changed", () => {--}}
        {{--        searchBox.setBounds(map.getBounds());--}}
        {{--    });--}}
        {{--    let markers = [];--}}
        {{--    // Listen for the event fired when the user selects a prediction and retrieve--}}
        {{--    // more details for that place.--}}
        {{--    searchBox.addListener("places_changed", () => {--}}
        {{--        const places = searchBox.getPlaces();--}}

        {{--        if (places.length == 0) {--}}
        {{--            return;--}}
        {{--        }--}}
        {{--        // Clear out the old markers.--}}
        {{--        markers.forEach((marker) => {--}}
        {{--            marker.setMap(null);--}}
        {{--        });--}}
        {{--        markers = [];--}}
        {{--        // For each place, get the icon, name and location.--}}
        {{--        const bounds = new google.maps.LatLngBounds();--}}
        {{--        places.forEach((place) => {--}}
        {{--            if (!place.geometry || !place.geometry.location) {--}}
        {{--                console.log("Returned place contains no geometry");--}}
        {{--                return;--}}
        {{--            }--}}
        {{--            var mrkr = new google.maps.Marker({--}}
        {{--                map,--}}
        {{--                title: place.name,--}}
        {{--                position: place.geometry.location,--}}
        {{--            });--}}

        {{--            google.maps.event.addListener(mrkr, "click", function (event) {--}}
        {{--                document.getElementById('main_latitude').value = this.position.lat();--}}
        {{--                document.getElementById('main_longitude').value = this.position.lng();--}}

        {{--            });--}}

        {{--            markers.push(mrkr);--}}

        {{--            if (place.geometry.viewport) {--}}
        {{--                // Only geocodes have viewport.--}}
        {{--                bounds.union(place.geometry.viewport);--}}
        {{--            } else {--}}
        {{--                bounds.extend(place.geometry.location);--}}
        {{--            }--}}
        {{--        });--}}
        {{--        map.fitBounds(bounds);--}}

        {{--    });--}}
        {{--};--}}
        function initAutocompleteMain(current_lat, current_long) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var current_lat = position.coords.latitude;
                    var current_long = position.coords.longitude;

                    document.getElementById('main_latitude').value = current_lat;
                    document.getElementById('main_longitude').value = current_long;

                    var myLatLng = {
                        lat: current_lat,
                        lng: current_long
                    };

                    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {
                        center: {
                            lat: current_lat,
                            lng: current_long
                        },
                        zoom: 13,
                        mapTypeId: "roadmap",
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                    });

                    marker.setMap(map);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(current_lat, current_long);

                    const locationButton = document.createElement("button");

                    locationButton.textContent = "{{\App\CPU\translate('My location')}}";
                    locationButton.classList.add("custom-map-control-button");
                    map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

                    locationButton.addEventListener("click", () => {
                        // Try HTML5 geolocation.
                        // $('#loading').parent().parent().css({
                        //     'width': '100%',
                        //     'height': '100vh',
                        //     'z-index': '100000;',
                        //     'position': 'fixed;',
                        //     'top': '0',
                        //     'background': 'rgba(255,255,255,0.18)',
                        // });
                        // $('#loading').show();

                        saveCurrentLocation(latlng, current_lat, current_long);

                    });

                    // $('#location_modal').modal('toggle');
                    saveCurrentLocation();


                    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);
                        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                        marker.setPosition(latlng);
                        map.panTo(latlng);

                        document.getElementById('main_latitude').value = coordinates['lat'];
                        document.getElementById('main_longitude').value = coordinates['lng'];

                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[10]) {
                                    console.log(results);

                                    var address_array = results[2].formatted_address.split(',');
                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = coordinates['lat'];
                                    document.getElementById('head_new_long').value = coordinates['lng'];


                                    // console.log(results);
                                }
                            }
                        });
                    });

                    function saveCurrentLocation(latLng, current_lat, current_long) {

                        var myLatLng = {
                            lat: current_lat,
                            lng: current_long
                        };


                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results) {

                                    var address_array = results[2].formatted_address.split(',');
                                    console.log(address_array);

                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('header_loc_mob').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('header_loc_pc').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    // document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = current_lat;
                                    document.getElementById('head_new_long').value = current_long;

                                    document.getElementById('form_location').style.display = 'block';


                                    // here We sent Ajax request to save location into session or to user and his locations
                                    // $.ajax();
                                    {{--$.ajax({--}}
                                    {{--    url: "{{route('confirm.location.ajax')}}",--}}
                                    {{--    data: {--}}
                                    {{--        _token: "{{csrf_token()}}",--}}
                                    {{--        address: street,--}}
                                    {{--        city: city,--}}
                                    {{--        country: country,--}}
                                    {{--        new_lat: current_lat,--}}
                                    {{--        new_long: current_long--}}
                                    {{--    },--}}
                                    {{--    type: "POST",--}}
                                    {{--    success: function (response) {--}}
                                    {{--        if (typeof (response) != 'object') {--}}
                                    {{--            response = $.parseJSON(response)--}}
                                    {{--        }--}}
                                    {{--        // console.log(response.data);--}}

                                    {{--        if (response.status === 1) {--}}
                                    {{--            toastr.success(response.msg);--}}
                                    {{--            window.location.reload();--}}
                                    {{--        }--}}
                                    {{--    }--}}

                                    {{--});--}}

                                }
                            }
                        });

                    }

                    // Create the search box and link it to the UI element.
                    const input = document.getElementById("pac-input-main");
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
                                document.getElementById('main_latitude').value = this.position.lat();
                                document.getElementById('main_longitude').value = this.position.lng();

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
                },
                function errorCallback(error) {
                    console.log(error)
                    document.getElementById('main_latitude').value = current_lat;
                    document.getElementById('main_longitude').value = current_long;

                    var myLatLng = {
                        lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                        lng: {{$default_location?$default_location['lng']:'151.2195'}}
                    };

                    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {
                        center: {
                            lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                            lng: {{$default_location?$default_location['lng']:'151.2195'}}
                        },
                        zoom: 13,
                        mapTypeId: "roadmap",
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                    });

                    marker.setMap(map);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(current_lat, current_long);

                    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);
                        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                        marker.setPosition(latlng);
                        map.panTo(latlng);

                        document.getElementById('main_latitude').value = coordinates['lat'];
                        document.getElementById('main_longitude').value = coordinates['lng'];

                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[10]) {
                                    console.log(results);

                                    var address_array = results[2].formatted_address.split(',');
                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = coordinates['lat'];
                                    document.getElementById('head_new_long').value = coordinates['lng'];


                                    // console.log(results);
                                }
                            }
                        });
                    });
                    // Create the search box and link it to the UI element.
                    const input = document.getElementById("pac-input-main");
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
                                document.getElementById('main_latitude').value = this.position.lat();
                                document.getElementById('main_longitude').value = this.position.lng();

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
                }
            );


        };


        @elseif(Session::has('current_location') && Session::has('current_city') && Session::has('current_country') && Session::has('new_lat') && Session::has('new_long'))


        {{--function initAutocompleteMain() {--}}
        {{--    var myLatLng = {--}}
        {{--        lat: {{Session::has('new_lat') ? Session::get('new_lat'):$default_location['lat']}},--}}
        {{--        lng: {{Session::has('new_long') ? Session::get('new_long'):$default_location['lng']}}--}}
        {{--    };--}}

        {{--    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {--}}
        {{--        center: {--}}
        {{--            lat: {{Session::has('new_lat') ? Session::get('new_lat'):$default_location['lat']}},--}}
        {{--            lng: {{Session::has('new_long') ? Session::get('new_long'):$default_location['lng']}}--}}
        {{--        },--}}
        {{--        zoom: 13,--}}
        {{--        mapTypeId: "roadmap",--}}
        {{--    });--}}

        {{--    var marker = new google.maps.Marker({--}}
        {{--        position: myLatLng,--}}
        {{--        map: map,--}}
        {{--    });--}}

        {{--    marker.setMap(map);--}}
        {{--    var geocoder = geocoder = new google.maps.Geocoder();--}}

        {{--    var latlng = new google.maps.LatLng({{Session::has('new_lat') ? Session::get('new_lat'):$default_location['lat']}}, {{Session::has('new_long') ? Session::get('new_long'):$default_location['lng']}});--}}

        {{--    const locationButton = document.createElement("button");--}}

        {{--    locationButton.textContent = "{{\App\CPU\translate('My location')}}";--}}
        {{--    locationButton.classList.add("custom-map-control-button");--}}
        {{--    map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);--}}


        {{--    locationButton.addEventListener("click", () => {--}}
        {{--        // Try HTML5 geolocation.--}}
        {{--        // $('#loading').parent().parent().css({--}}
        {{--        //     'width': '100%',--}}
        {{--        //     'height': '100vh',--}}
        {{--        //     'z-index': '100000;',--}}
        {{--        //     'position': 'fixed;',--}}
        {{--        //     'top': '0',--}}
        {{--        //     'background': 'rgba(255,255,255,0.18)',--}}
        {{--        // });--}}
        {{--        // $('#loading').show();--}}
        {{--        navigator.geolocation.getCurrentPosition(--}}
        {{--            function (position) {--}}
        {{--                var user_lat = position.coords.latitude;--}}
        {{--                var user_long = position.coords.longitude;--}}

        {{--                // alert(current_lat);--}}

        {{--                var latlng = new google.maps.LatLng(user_lat, user_long);--}}

        {{--                saveCurrentLocation(latlng, user_lat, user_long);--}}


        {{--            },--}}
        {{--            function errorCallback(error) {--}}
        {{--                console.log(error)--}}
        {{--                document.getElementById('main_latitude').value = current_lat;--}}
        {{--                document.getElementById('main_longitude').value = current_long;--}}

        {{--                var myLatLng = {--}}
        {{--                    lat: {{$default_location?$default_location['lat']:'-33.8688'}},--}}
        {{--                    lng: {{$default_location?$default_location['lng']:'151.2195'}}--}}
        {{--                };--}}

        {{--                const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {--}}
        {{--                    center: {--}}
        {{--                        lat: {{$default_location?$default_location['lat']:'-33.8688'}},--}}
        {{--                        lng: {{$default_location?$default_location['lng']:'151.2195'}}--}}
        {{--                    },--}}
        {{--                    zoom: 13,--}}
        {{--                    mapTypeId: "roadmap",--}}
        {{--                });--}}

        {{--                var marker = new google.maps.Marker({--}}
        {{--                    position: myLatLng,--}}
        {{--                    map: map,--}}
        {{--                });--}}

        {{--                marker.setMap(map);--}}
        {{--                var geocoder = geocoder = new google.maps.Geocoder();--}}
        {{--                var latlng = new google.maps.LatLng(current_lat, current_long);--}}

        {{--                google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {--}}
        {{--                    var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);--}}
        {{--                    var coordinates = JSON.parse(coordinates);--}}
        {{--                    var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);--}}
        {{--                    marker.setPosition(latlng);--}}
        {{--                    map.panTo(latlng);--}}

        {{--                    document.getElementById('main_latitude').value = coordinates['lat'];--}}
        {{--                    document.getElementById('main_longitude').value = coordinates['lng'];--}}

        {{--                    geocoder.geocode({'latLng': latlng}, function (results, status) {--}}
        {{--                        if (status == google.maps.GeocoderStatus.OK) {--}}
        {{--                            if (results[10]) {--}}
                                        // console.log(results);

        {{--                                var address_array = results[2].formatted_address.split(',');--}}
        {{--                                var arr_count = address_array.length;--}}
        {{--                                console.log(address_array.length);--}}
        {{--                                console.log(address_array[arr_count - 1]);--}}
        {{--                                console.log(address_array[arr_count - 3]);--}}

        {{--                                var street = results[2].formatted_address;--}}
        {{--                                var city = "";--}}
        {{--                                if (address_array[arr_count - 3] === undefined) {--}}
        {{--                                    city = address_array[arr_count - 2];--}}
        {{--                                } else {--}}
        {{--                                    city = address_array[arr_count - 3];--}}
        {{--                                }--}}
        {{--                                var country = address_array[arr_count - 1];--}}
        {{--                                document.getElementById('main_address').innerHTML = city;--}}
        {{--                                document.getElementById('form_location').style.display = 'block';--}}

        {{--                                document.getElementById('head_address').value = street;--}}
        {{--                                document.getElementById('head_city').value = city;--}}
        {{--                                document.getElementById('head_country').value = country;--}}
        {{--                                document.getElementById('head_new_lat').value = coordinates['lat'];--}}
        {{--                                document.getElementById('head_new_long').value = coordinates['lng'];--}}


        {{--                                // console.log(results);--}}
        {{--                            }--}}
        {{--                        }--}}
        {{--                    });--}}
        {{--                });--}}
        {{--                // Create the search box and link it to the UI element.--}}
        {{--                const input = document.getElementById("pac-input-main");--}}
        {{--                const searchBox = new google.maps.places.SearchBox(input);--}}
        {{--                map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);--}}
        {{--                // Bias the SearchBox results towards current map's viewport.--}}
        {{--                map.addListener("bounds_changed", () => {--}}
        {{--                    searchBox.setBounds(map.getBounds());--}}
        {{--                });--}}
        {{--                let markers = [];--}}
        {{--                // Listen for the event fired when the user selects a prediction and retrieve--}}
        {{--                // more details for that place.--}}
        {{--                searchBox.addListener("places_changed", () => {--}}
        {{--                    const places = searchBox.getPlaces();--}}

        {{--                    if (places.length == 0) {--}}
        {{--                        return;--}}
        {{--                    }--}}
        {{--                    // Clear out the old markers.--}}
        {{--                    markers.forEach((marker) => {--}}
        {{--                        marker.setMap(null);--}}
        {{--                    });--}}
        {{--                    markers = [];--}}
        {{--                    // For each place, get the icon, name and location.--}}
        {{--                    const bounds = new google.maps.LatLngBounds();--}}
        {{--                    places.forEach((place) => {--}}
        {{--                        if (!place.geometry || !place.geometry.location) {--}}
        {{--                            console.log("Returned place contains no geometry");--}}
        {{--                            return;--}}
        {{--                        }--}}
        {{--                        var mrkr = new google.maps.Marker({--}}
        {{--                            map,--}}
        {{--                            title: place.name,--}}
        {{--                            position: place.geometry.location,--}}
        {{--                        });--}}

        {{--                        google.maps.event.addListener(mrkr, "click", function (event) {--}}
        {{--                            document.getElementById('main_latitude').value = this.position.lat();--}}
        {{--                            document.getElementById('main_longitude').value = this.position.lng();--}}

        {{--                        });--}}

        {{--                        markers.push(mrkr);--}}

        {{--                        if (place.geometry.viewport) {--}}
        {{--                            // Only geocodes have viewport.--}}
        {{--                            bounds.union(place.geometry.viewport);--}}
        {{--                        } else {--}}
        {{--                            bounds.extend(place.geometry.location);--}}
        {{--                        }--}}
        {{--                    });--}}
        {{--                    map.fitBounds(bounds);--}}

        {{--                });--}}
        {{--            }--}}
        {{--        );--}}


        {{--    });--}}

        {{--    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {--}}
        {{--        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);--}}
        {{--        var coordinates = JSON.parse(coordinates);--}}
        {{--        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);--}}
        {{--        marker.setPosition(latlng);--}}
        {{--        map.panTo(latlng);--}}

        {{--        document.getElementById('main_latitude').value = coordinates['lat'];--}}
        {{--        document.getElementById('main_longitude').value = coordinates['lng'];--}}

        {{--        geocoder.geocode({'latLng': latlng}, function (results, status) {--}}
        {{--            if (status == google.maps.GeocoderStatus.OK) {--}}
        {{--                if (results[10]) {--}}
                            // console.log(results);

        {{--                    var address_array = results[2].formatted_address.split(',');--}}
        {{--                    var arr_count = address_array.length;--}}
        {{--                    console.log(address_array.length);--}}
        {{--                    console.log(address_array[arr_count - 1]);--}}
        {{--                    console.log(address_array[arr_count - 3]);--}}

        {{--                    var street = results[2].formatted_address;--}}
        {{--                    var city = "";--}}
        {{--                    if (address_array[arr_count - 3] === undefined) {--}}
        {{--                        city = address_array[arr_count - 2];--}}
        {{--                    } else {--}}
        {{--                        city = address_array[arr_count - 3];--}}
        {{--                    }--}}
        {{--                    var country = address_array[arr_count - 1];--}}
        {{--                    document.getElementById('main_address').innerHTML = city;--}}
        {{--                    document.getElementById('form_location').style.display = 'block';--}}

        {{--                    document.getElementById('head_address').value = street;--}}
        {{--                    document.getElementById('head_city').value = city;--}}
        {{--                    document.getElementById('head_country').value = country;--}}
        {{--                    document.getElementById('head_new_lat').value = coordinates['lat'];--}}
        {{--                    document.getElementById('head_new_long').value = coordinates['lng'];--}}


        {{--                    // console.log(results);--}}
        {{--                }--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--    // Hereeeeeee--}}
        {{--    function saveCurrentLocation(latLng, current_lat, current_long) {--}}

        {{--        var myLatLng = {--}}
        {{--            lat: current_lat,--}}
        {{--            lng: current_long--}}
        {{--        };--}}

        {{--        geocoder.geocode({'latLng': myLatLng}, function (results, status) {--}}
        {{--            if (status == google.maps.GeocoderStatus.OK) {--}}
        {{--                if (results) {--}}

        {{--                    var address_array = results[2].formatted_address.split(',');--}}
        {{--                    console.log(address_array);--}}

        {{--                    var arr_count = address_array.length;--}}
        {{--                    console.log(address_array.length);--}}
        {{--                    console.log(address_array[arr_count - 1]);--}}
        {{--                    console.log(address_array[arr_count - 3]);--}}

        {{--                    var street = results[2].formatted_address;--}}
        {{--                    var city = "";--}}
        {{--                    if (address_array[arr_count - 3] === undefined) {--}}
        {{--                        city = address_array[arr_count - 2];--}}
        {{--                    } else {--}}
        {{--                        city = address_array[arr_count - 3];--}}
        {{--                    }--}}
        {{--                    var country = address_array[arr_count - 1];--}}
        {{--                    document.getElementById('main_address').innerHTML = city;--}}
        {{--                    // document.getElementById('form_location').style.display =Sessionauth('customer')->check() ? auth('customer')->user()->city : ::get('current_city')}}')}}"}

        {{--                    document.getElementById('head_address').value = street;--}}
        {{--                    document.getElementById('head_city').value = city;--}}
        {{--                    document.getElementById('head_country').value = country;--}}
        {{--                    document.getElementById('head_new_lat').value = current_lat;--}}
        {{--                    document.getElementById('head_new_long').value = current_long;--}}

        {{--                    document.getElementById('form_location').style.display = 'block';--}}


        {{--                    // here We sent Ajax request to save location into session or to user and his locations--}}
        {{--                    // $.ajax();--}}
        {{--                    --}}{{--$.ajax({--}}
        {{--                    --}}{{--    url: "{{route('confirm.location.ajax')}}",--}}
        {{--                    --}}{{--    data: {--}}
        {{--                    --}}{{--        _token: "{{csrf_token()}}",--}}
        {{--                    --}}{{--        address: street,--}}
        {{--                    --}}{{--        city: city,--}}
        {{--                    --}}{{--        country: country,--}}
        {{--                    --}}{{--        new_lat: current_lat,--}}
        {{--                    --}}{{--        new_long: current_long--}}
        {{--                    --}}{{--    },--}}
        {{--                    --}}{{--    type: "POST",--}}
        {{--                    --}}{{--    success: function (response) {--}}
        {{--                    --}}{{--        if (typeof (response) != 'object') {--}}
        {{--                    --}}{{--            response = $.parseJSON(response)--}}
        {{--                    --}}{{--        }--}}
        {{--                    --}}{{--        // console.log(response.data);--}}

        {{--                    --}}{{--        if (response.status === 1) {--}}
        {{--                    --}}{{--            toastr.success(response.msg);--}}
        {{--                    --}}{{--            window.location.reload();--}}
        {{--                    --}}{{--        }--}}
        {{--                    --}}{{--    }--}}

        {{--                    --}}{{--});--}}

        {{--                }--}}
        {{--            }--}}
        {{--        });--}}

        {{--    }--}}

        {{--    // Create the search box and link it to the UI element.--}}
        {{--    const input = document.getElementById("pac-input-main");--}}
        {{--    const searchBox = new google.maps.places.SearchBox(input);--}}
        {{--    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);--}}
        {{--    // Bias the SearchBox results towards current map's viewport.--}}
        {{--    map.addListener("bounds_changed", () => {--}}
        {{--        searchBox.setBounds(map.getBounds());--}}
        {{--    });--}}
        {{--    let markers = [];--}}
        {{--    // Listen for the event fired when the user selects a prediction and retrieve--}}
        {{--    // more details for that place.--}}
        {{--    searchBox.addListener("places_changed", () => {--}}
        {{--        const places = searchBox.getPlaces();--}}

        {{--        if (places.length == 0) {--}}
        {{--            return;--}}
        {{--        }--}}
        {{--        // Clear out the old markers.--}}
        {{--        markers.forEach((marker) => {--}}
        {{--            marker.setMap(null);--}}
        {{--        });--}}
        {{--        markers = [];--}}
        {{--        // For each place, get the icon, name and location.--}}
        {{--        const bounds = new google.maps.LatLngBounds();--}}
        {{--        places.forEach((place) => {--}}
        {{--            if (!place.geometry || !place.geometry.location) {--}}
        {{--                console.log("Returned place contains no geometry");--}}
        {{--                return;--}}
        {{--            }--}}
        {{--            var mrkr = new google.maps.Marker({--}}
        {{--                map,--}}
        {{--                title: place.name,--}}
        {{--                position: place.geometry.location,--}}
        {{--            });--}}

        {{--            google.maps.event.addListener(mrkr, "click", function (event) {--}}
        {{--                document.getElementById('main_latitude').value = this.position.lat();--}}
        {{--                document.getElementById('main_longitude').value = this.position.lng();--}}

        {{--            });--}}

        {{--            markers.push(mrkr);--}}

        {{--            if (place.geometry.viewport) {--}}
        {{--                // Only geocodes have viewport.--}}
        {{--                bounds.union(place.geometry.viewport);--}}
        {{--            } else {--}}
        {{--                bounds.extend(place.geometry.location);--}}
        {{--            }--}}
        {{--        });--}}
        {{--        map.fitBounds(bounds);--}}
        {{--    });--}}
        {{--};--}}
        function initAutocompleteMain(current_lat, current_long) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var current_lat = position.coords.latitude;
                    var current_long = position.coords.longitude;

                    document.getElementById('main_latitude').value = current_lat;
                    document.getElementById('main_longitude').value = current_long;

                    var myLatLng = {
                        lat: current_lat,
                        lng: current_long
                    };

                    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {
                        center: {
                            lat: current_lat,
                            lng: current_long
                        },
                        zoom: 13,
                        mapTypeId: "roadmap",
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                    });

                    marker.setMap(map);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(current_lat, current_long);

                    const locationButton = document.createElement("button");

                    locationButton.textContent = "{{\App\CPU\translate('My location')}}";
                    locationButton.classList.add("custom-map-control-button");
                    map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

                    locationButton.addEventListener("click", () => {
                        // Try HTML5 geolocation.
                        // $('#loading').parent().parent().css({
                        //     'width': '100%',
                        //     'height': '100vh',
                        //     'z-index': '100000;',
                        //     'position': 'fixed;',
                        //     'top': '0',
                        //     'background': 'rgba(255,255,255,0.18)',
                        // });
                        // $('#loading').show();

                        saveCurrentLocation(latlng, current_lat, current_long);

                    });

                    // $('#location_modal').modal('toggle');
                    saveCurrentLocation();


                    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);
                        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                        marker.setPosition(latlng);
                        map.panTo(latlng);

                        document.getElementById('main_latitude').value = coordinates['lat'];
                        document.getElementById('main_longitude').value = coordinates['lng'];

                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[10]) {
                                    console.log(results);

                                    var address_array = results[2].formatted_address.split(',');
                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = coordinates['lat'];
                                    document.getElementById('head_new_long').value = coordinates['lng'];


                                    // console.log(results);
                                }
                            }
                        });
                    });

                    function saveCurrentLocation(latLng, current_lat, current_long) {

                        var myLatLng = {
                            lat: current_lat,
                            lng: current_long
                        };


                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results) {

                                    var address_array = results[2].formatted_address.split(',');
                                    console.log(address_array);

                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('header_loc_mob').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('header_loc_pc').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    // document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = current_lat;
                                    document.getElementById('head_new_long').value = current_long;

                                    document.getElementById('form_location').style.display = 'block';


                                    // here We sent Ajax request to save location into session or to user and his locations
                                    // $.ajax();
                                    {{--$.ajax({--}}
                                    {{--    url: "{{route('confirm.location.ajax')}}",--}}
                                    {{--    data: {--}}
                                    {{--        _token: "{{csrf_token()}}",--}}
                                    {{--        address: street,--}}
                                    {{--        city: city,--}}
                                    {{--        country: country,--}}
                                    {{--        new_lat: current_lat,--}}
                                    {{--        new_long: current_long--}}
                                    {{--    },--}}
                                    {{--    type: "POST",--}}
                                    {{--    success: function (response) {--}}
                                    {{--        if (typeof (response) != 'object') {--}}
                                    {{--            response = $.parseJSON(response)--}}
                                    {{--        }--}}
                                    {{--        // console.log(response.data);--}}

                                    {{--        if (response.status === 1) {--}}
                                    {{--            toastr.success(response.msg);--}}
                                    {{--            window.location.reload();--}}
                                    {{--        }--}}
                                    {{--    }--}}

                                    {{--});--}}

                                }
                            }
                        });

                    }

                    // Create the search box and link it to the UI element.
                    const input = document.getElementById("pac-input-main");
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
                                document.getElementById('main_latitude').value = this.position.lat();
                                document.getElementById('main_longitude').value = this.position.lng();

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
                },
                function errorCallback(error) {
                    console.log(error)
                    document.getElementById('main_latitude').value = current_lat;
                    document.getElementById('main_longitude').value = current_long;

                    var myLatLng = {
                        lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                        lng: {{$default_location?$default_location['lng']:'151.2195'}}
                    };

                    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {
                        center: {
                            lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                            lng: {{$default_location?$default_location['lng']:'151.2195'}}
                        },
                        zoom: 13,
                        mapTypeId: "roadmap",
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                    });

                    marker.setMap(map);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(current_lat, current_long);

                    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);
                        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                        marker.setPosition(latlng);
                        map.panTo(latlng);

                        document.getElementById('main_latitude').value = coordinates['lat'];
                        document.getElementById('main_longitude').value = coordinates['lng'];

                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[10]) {
                                    console.log(results);

                                    var address_array = results[2].formatted_address.split(',');
                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = coordinates['lat'];
                                    document.getElementById('head_new_long').value = coordinates['lng'];


                                    // console.log(results);
                                }
                            }
                        });
                    });
                    // Create the search box and link it to the UI element.
                    const input = document.getElementById("pac-input-main");
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
                                document.getElementById('main_latitude').value = this.position.lat();
                                document.getElementById('main_longitude').value = this.position.lng();

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
                }
            );


        };

        @else
        // alert('test');
        // function getCurrentUserLocation() {
        //     navigator.geolocation.getCurrentPosition(
        //         function (position) {
        //             var current_lat = position.coords.latitude;
        //             var current_lang = position.coords.longitude;
        //
        //             document.getElementById('main_latitude').value = current_lat;
        //             document.getElementById('main_longitude').value = current_lang;
        //             return [
        //                 current_lat,
        //                 current_lang
        //             ];
        //         },
        //         function errorCallback(error) {
        //             console.log(error)
        //         }
        //     );
        // };
        function initAutocompleteMain(current_lat, current_long) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var current_lat = position.coords.latitude;
                    var current_long = position.coords.longitude;

                    document.getElementById('main_latitude').value = current_lat;
                    document.getElementById('main_longitude').value = current_long;

                    var myLatLng = {
                        lat: current_lat,
                        lng: current_long
                    };

                    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {
                        center: {
                            lat: current_lat,
                            lng: current_long
                        },
                        zoom: 13,
                        mapTypeId: "roadmap",
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                    });

                    marker.setMap(map);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(current_lat, current_long);

                    const locationButton = document.createElement("button");

                    locationButton.textContent = "{{\App\CPU\translate('My location')}}";
                    locationButton.classList.add("custom-map-control-button");
                    map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

                    locationButton.addEventListener("click", () => {
                        // Try HTML5 geolocation.
                        // $('#loading').parent().parent().css({
                        //     'width': '100%',
                        //     'height': '100vh',
                        //     'z-index': '100000;',
                        //     'position': 'fixed;',
                        //     'top': '0',
                        //     'background': 'rgba(255,255,255,0.18)',
                        // });
                        // $('#loading').show();

                        saveCurrentLocation(latlng, current_lat, current_long);

                    });

                    $('#location_modal').modal('toggle');
                    saveCurrentLocation();


                    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);
                        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                        marker.setPosition(latlng);
                        map.panTo(latlng);

                        document.getElementById('main_latitude').value = coordinates['lat'];
                        document.getElementById('main_longitude').value = coordinates['lng'];

                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[10]) {
                                    console.log(results);

                                    var address_array = results[2].formatted_address.split(',');
                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = coordinates['lat'];
                                    document.getElementById('head_new_long').value = coordinates['lng'];


                                    // console.log(results);
                                }
                            }
                        });
                    });

                    function saveCurrentLocation(latLng, current_lat, current_long) {

                        var myLatLng = {
                            lat: current_lat,
                            lng: current_long
                        };


                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results) {

                                    var address_array = results[2].formatted_address.split(',');
                                    console.log(address_array);

                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('header_loc_mob').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('header_loc_pc').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    // document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = current_lat;
                                    document.getElementById('head_new_long').value = current_long;

                                    document.getElementById('form_location').style.display = 'block';


                                    // here We sent Ajax request to save location into session or to user and his locations
                                    // $.ajax();
                                    {{--$.ajax({--}}
                                    {{--    url: "{{route('confirm.location.ajax')}}",--}}
                                    {{--    data: {--}}
                                    {{--        _token: "{{csrf_token()}}",--}}
                                    {{--        address: street,--}}
                                    {{--        city: city,--}}
                                    {{--        country: country,--}}
                                    {{--        new_lat: current_lat,--}}
                                    {{--        new_long: current_long--}}
                                    {{--    },--}}
                                    {{--    type: "POST",--}}
                                    {{--    success: function (response) {--}}
                                    {{--        if (typeof (response) != 'object') {--}}
                                    {{--            response = $.parseJSON(response)--}}
                                    {{--        }--}}
                                    {{--        // console.log(response.data);--}}

                                    {{--        if (response.status === 1) {--}}
                                    {{--            toastr.success(response.msg);--}}
                                    {{--            window.location.reload();--}}
                                    {{--        }--}}
                                    {{--    }--}}

                                    {{--});--}}

                                }
                            }
                        });

                    }

                    // Create the search box and link it to the UI element.
                    const input = document.getElementById("pac-input-main");
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
                                document.getElementById('main_latitude').value = this.position.lat();
                                document.getElementById('main_longitude').value = this.position.lng();

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
                },
                function errorCallback(error) {
                    console.log(error)
                    document.getElementById('main_latitude').value = current_lat;
                    document.getElementById('main_longitude').value = current_long;

                    var myLatLng = {
                        lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                        lng: {{$default_location?$default_location['lng']:'151.2195'}}
                    };

                    const map = new google.maps.Map(document.getElementById("location_map_canvas_main"), {
                        center: {
                            lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                            lng: {{$default_location?$default_location['lng']:'151.2195'}}
                        },
                        zoom: 13,
                        mapTypeId: "roadmap",
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                    });

                    marker.setMap(map);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(current_lat, current_long);

                    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);
                        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                        marker.setPosition(latlng);
                        map.panTo(latlng);

                        document.getElementById('main_latitude').value = coordinates['lat'];
                        document.getElementById('main_longitude').value = coordinates['lng'];

                        geocoder.geocode({'latLng': latlng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[10]) {
                                    console.log(results);

                                    var address_array = results[2].formatted_address.split(',');
                                    var arr_count = address_array.length;
                                    // console.log(address_array.length);
                                    // console.log(address_array[arr_count - 1]);
                                    // console.log(address_array[arr_count - 3]);

                                    var street = results[0].formatted_address;
                                    var city = results[2].address_components[1]['short_name'];
                                    var country = results[2].address_components[2]['short_name'];

                                    document.getElementById('main_address').innerHTML = "{{auth('customer')->check() ? auth('customer')->user()->city : Session::get('current_city')}}";
                                    document.getElementById('form_location').style.display = 'block';

                                    document.getElementById('head_address').value = street;
                                    document.getElementById('head_city').value = city;
                                    document.getElementById('head_country').value = country;
                                    document.getElementById('head_new_lat').value = coordinates['lat'];
                                    document.getElementById('head_new_long').value = coordinates['lng'];


                                    // console.log(results);
                                }
                            }
                        });
                    });
                    // Create the search box and link it to the UI element.
                    const input = document.getElementById("pac-input-main");
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
                                document.getElementById('main_latitude').value = this.position.lat();
                                document.getElementById('main_longitude').value = this.position.lng();

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
                }
            );


        };

        @endif

        // $('.list-ship').on('click', function () {
        //     initAutocompleteMain();
        //     $('.custom-map-control-button').click();
        //
        //
        // });


        let confirm_btn = $('#confirm_btn');
        confirm_btn.on('click', function (e) {
            e.preventDefault();
            let street = document.getElementById('head_address').value ;
            let city = document.getElementById('head_city').value ;
            let country = document.getElementById('head_country').value ;
            let current_lat = document.getElementById('head_new_lat').value ;
            let current_long = document.getElementById('head_new_long').value ;

            $.ajax({
                url: "{{route('confirm.location.ajax')}}",
                data: {
                    _token: "{{csrf_token()}}",
                    address: street,
                    city: city,
                    country: country,
                    new_lat: current_lat,
                    new_long: current_long
                },
                type: "POST",
                success: function (response) {
                    if (typeof (response) != 'object') {
                        response = $.parseJSON(response)
                    }
                    // console.log(response.data);

                    if (response.status === 1) {
                        toastr.success(response.msg);
                        window.location.reload();
                    }
                }

            });
        });


        $(document).on('ready', function () {
            initAutocompleteMain();

            $('.list-ship').on('click', function () {
                initAutocompleteMain();
            });

        });

        $(document).on("keydown", "input", function (e) {
            if (e.which == 13) e.preventDefault();
        });
    </script>

@endpush


