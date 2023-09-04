@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('general_settings'))

@push('css_or_js')
    <link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/back-end/css/custom.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')
        <!-- End Inlile Menu -->

        <div class="alert alert-danger d-none mb-3" role="alert">
            {{\App\CPU\translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                    @php($config=\App\CPU\Helpers::get_business_settings('maintenance_mode'))
                    <h5 class="mb-0 d-flex gap-1 c1">
                        <!-- <i class="tio-settings-outlined"></i> -->
                        {{\App\CPU\translate('maintenance_mode')}}
                    </h5>
                    <div class="position-relative">
                        <label class="switcher">
                            <input type="checkbox" class="switcher_input" onclick="maintenance_mode()"
                                {{isset($config) && $config?'checked':''}}>
                            <span class="switcher_control"></span>
                        </label>
                    </div>
                </div>
                <p>*{{\App\CPU\translate('By_turning_on_maintaince_mode,_all_your_app_and_customer_side_website_will_be_off._Only_admin_panel_and_seller_panel_will_be_functional')}}</p>
            </div>
        </div>

        <form action="{{ route('admin.business-settings.update-info') }}" method="POST"
                enctype="multipart/form-data">
            @csrf
            <!-- Company Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-1">
                        <i class="tio-user-big"></i>
                        {{\App\CPU\translate('Company_Information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @php($companyName=\App\Model\BusinessSetting::where('type','company_name')->first())
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{\App\CPU\translate('company')}} {{\App\CPU\translate('name')}}</label>
                                <input class="form-control" type="text" name="company_name"
                                    value="{{ $companyName->value?$companyName->value:" " }}"
                                    placeholder="New Business">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($company_phone=\App\Model\BusinessSetting::where('type','company_phone')->first())
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('Phone')}}</label>
                                <input class="form-control" type="text" name="company_phone"
                                    value="{{ $company_phone->value?$company_phone->value:"" }}"
                                    placeholder="New Business">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($cs_phone=\App\Model\BusinessSetting::where('type','customer_service')->first())
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('Customer Service Phone')}}</label>
                                <input class="form-control" type="text" name="customer_service"
                                    value="{{ $cs_phone->value?$cs_phone->value:"" }}"
                                    placeholder="New Business">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($company_email=\App\Model\BusinessSetting::where('type','company_email')->first())
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{\App\CPU\translate('Email')}}</label>
                                <input class="form-control" type="text" name="company_email"
                                    value="{{ $company_email->value?$company_email->value:" " }}"
                                    placeholder="New Business">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($shop_address=\App\CPU\Helpers::get_business_settings('shop_address'))
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('company_address')}}</label>
                                <input type="text" value="{{isset($shop_address)!=null?$shop_address:''}}"
                                    name="shop_address" class="form-control"
                                    placeholder="{{\App\CPU\translate('Your_shop_address')}}"
                                    required>
                            </div>
                        </div>
                        @php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{\App\CPU\translate('latitude')}}</label>
                                <input class="form-control" type="text" name="latitude"
                                    value="{{ isset($default_location)?$default_location['lat']:'' }}"
                                    placeholder="{{\App\CPU\translate('latitude')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{\App\CPU\translate('longitude')}}</label>
                                <input class="form-control" type="text" name="longitude"
                                    value="{{ isset($default_location)?$default_location['lng']:'' }}"
                                    placeholder="{{\App\CPU\translate('longitude')}}">
                            </div>
                        </div>

                        @php($tz=\App\Model\BusinessSetting::where('type','timezone')->first())
                        @php($tz=$tz?$tz->value:0)
                        <div class="col-md-4 d-none">
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('time')}} {{\App\CPU\translate('zone')}}</label>
                                <select name="timezone" class="form-control js-select2-custom">
                                    <option value="UTC" {{$tz?($tz==''?'selected':''):''}}>UTC</option>
                                    <option value="Etc/GMT+12" {{$tz?($tz=='Etc/GMT+12'?'selected':''):''}}>(GMT-12:00)
                                        International Date Line West
                                    </option>
                                    <option value="Pacific/Midway" {{$tz?($tz=='Pacific/Midway'?'selected':''):''}}>
                                        (GMT-11:00)
                                        Midway Island, Samoa
                                    </option>
                                    <option value="Pacific/Honolulu" {{$tz?($tz=='Pacific/Honolulu'?'selected':''):''}}>
                                        (GMT-10:00)
                                        Hawaii
                                    </option>
                                    <option value="US/Alaska" {{$tz?($tz=='US/Alaska'?'selected':''):''}}>(GMT-09:00) Alaska
                                    </option>
                                    <option
                                        value="America/Los_Angeles" {{$tz?($tz=='America/Los_Angeles'?'selected':''):''}}>
                                        (GMT-08:00) Pacific Time (US & Canada)
                                    </option>
                                    <option value="America/Tijuana" {{$tz?($tz=='America/Tijuana'?'selected':''):''}}>
                                        (GMT-08:00)
                                        Tijuana, Baja California
                                    </option>
                                    <option value="US/Arizona" {{$tz?($tz=='US/Arizona'?'selected':''):''}}>(GMT-07:00)
                                        Arizona
                                    </option>
                                    <option value="America/Chihuahua" {{$tz?($tz=='America/Chihuahua'?'selected':''):''}}>
                                        (GMT-07:00) Chihuahua, La Paz, Mazatlan
                                    </option>
                                    <option value="US/Mountain" {{$tz?($tz=='US/Mountain'?'selected':''):''}}>(GMT-07:00)
                                        Mountain
                                        Time (US & Canada)
                                    </option>
                                    <option value="America/Managua" {{$tz?($tz=='America/Managua'?'selected':''):''}}>
                                        (GMT-06:00)
                                        Central America
                                    </option>
                                    <option value="US/Central" {{$tz?($tz=='US/Central'?'selected':''):''}}>(GMT-06:00)
                                        Central Time
                                        (US & Canada)
                                    </option>
                                    <option
                                        value="America/Mexico_City" {{$tz?($tz=='America/Mexico_City'?'selected':''):''}}>
                                        (GMT-06:00) Guadalajara, Mexico City, Monterrey
                                    </option>
                                    <option
                                        value="Canada/Saskatchewan" {{$tz?($tz=='Canada/Saskatchewan'?'selected':''):''}}>
                                        (GMT-06:00) Saskatchewan
                                    </option>
                                    <option value="America/Bogota" {{$tz?($tz=='America/Bogota'?'selected':''):''}}>
                                        (GMT-05:00)
                                        Bogota, Lima, Quito, Rio Branco
                                    </option>
                                    <option value="US/Eastern" {{$tz?($tz=='US/Eastern'?'selected':''):''}}>(GMT-05:00)
                                        Eastern Time
                                        (US & Canada)
                                    </option>
                                    <option value="US/East-Indiana" {{$tz?($tz=='US/East-Indiana'?'selected':''):''}}>
                                        (GMT-05:00)
                                        Indiana (East)
                                    </option>
                                    <option value="Canada/Atlantic" {{$tz?($tz=='Canada/Atlantic'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Atlantic Time (Canada)
                                    </option>
                                    <option value="America/Caracas" {{$tz?($tz=='America/Caracas'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Caracas, La Paz
                                    </option>
                                    <option value="America/Manaus" {{$tz?($tz=='America/Manaus'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Manaus
                                    </option>
                                    <option value="America/Santiago" {{$tz?($tz=='America/Santiago'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Santiago
                                    </option>
                                    <option
                                        value="Canada/Newfoundland" {{$tz?($tz=='Canada/Newfoundland'?'selected':''):''}}>
                                        (GMT-03:30) Newfoundland
                                    </option>
                                    <option value="America/Sao_Paulo" {{$tz?($tz=='America/Sao_Paulo'?'selected':''):''}}>
                                        (GMT-03:00) Brasilia
                                    </option>
                                    <option
                                        value="America/Argentina/Buenos_Aires" {{$tz?($tz=='America/Argentina/Buenos_Aires'?'selected':''):''}}>
                                        (GMT-03:00) Buenos Aires, Georgetown
                                    </option>
                                    <option value="America/Godthab" {{$tz?($tz=='America/Godthab'?'selected':''):''}}>
                                        (GMT-03:00)
                                        Greenland
                                    </option>
                                    <option value="America/Montevideo" {{$tz?($tz=='America/Montevideo'?'selected':''):''}}>
                                        (GMT-03:00) Montevideo
                                    </option>
                                    <option value="America/Noronha" {{$tz?($tz=='America/Noronha'?'selected':''):''}}>
                                        (GMT-02:00)
                                        Mid-Atlantic
                                    </option>
                                    <option
                                        value="Atlantic/Cape_Verde" {{$tz?($tz=='Atlantic/Cape_Verde'?'selected':''):''}}>
                                        (GMT-01:00) Cape Verde Is.
                                    </option>
                                    <option value="Atlantic/Azores" {{$tz?($tz=='Atlantic/Azores'?'selected':''):''}}>
                                        (GMT-01:00)
                                        Azores
                                    </option>
                                    <option value="Africa/Casablanca" {{$tz?($tz=='Africa/Casablanca'?'selected':''):''}}>
                                        (GMT+00:00) Casablanca, Monrovia, Reykjavik
                                    </option>
                                    <option value="Etc/Greenwich" {{$tz?($tz=='Etc/Greenwich'?'selected':''):''}}>
                                        (GMT+00:00)
                                        Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London
                                    </option>
                                    <option value="Europe/Amsterdam" {{$tz?($tz=='Europe/Amsterdam'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna
                                    </option>
                                    <option value="Europe/Belgrade" {{$tz?($tz=='Europe/Belgrade'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Belgrade, Bratislava, Budapest, Ljubljana, Prague
                                    </option>
                                    <option value="Europe/Brussels" {{$tz?($tz=='Europe/Brussels'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Brussels, Copenhagen, Madrid, Paris
                                    </option>
                                    <option value="Europe/Sarajevo" {{$tz?($tz=='Europe/Sarajevo'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Sarajevo, Skopje, Warsaw, Zagreb
                                    </option>
                                    <option value="Africa/Lagos" {{$tz?($tz=='Africa/Lagos'?'selected':''):''}}>(GMT+01:00)
                                        West
                                        Central Africa
                                    </option>
                                    <option value="Asia/Amman" {{$tz?($tz=='Asia/Amman'?'selected':''):''}}>(GMT+02:00)
                                        Amman
                                    </option>
                                    <option value="Europe/Athens" {{$tz?($tz=='Europe/Athens'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Athens, Bucharest, Istanbul
                                    </option>
                                    <option value="Asia/Beirut" {{$tz?($tz=='Asia/Beirut'?'selected':''):''}}>(GMT+02:00)
                                        Beirut
                                    </option>
                                    <option value="Africa/Cairo" {{$tz?($tz=='Africa/Cairo'?'selected':''):''}}>(GMT+02:00)
                                        Cairo
                                    </option>
                                    <option value="Africa/Harare" {{$tz?($tz=='Africa/Harare'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Harare, Pretoria
                                    </option>
                                    <option value="Europe/Helsinki" {{$tz?($tz=='Europe/Helsinki'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius
                                    </option>
                                    <option value="Asia/Jerusalem" {{$tz?($tz=='Asia/Jerusalem'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Jerusalem
                                    </option>
                                    <option value="Europe/Minsk" {{$tz?($tz=='Europe/Minsk'?'selected':''):''}}>(GMT+02:00)
                                        Minsk
                                    </option>
                                    <option value="Africa/Windhoek" {{$tz?($tz=='Africa/Windhoek'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Windhoek
                                    </option>
                                    <option value="Asia/Kuwait" {{$tz?($tz=='Asia/Kuwait'?'selected':''):''}}>(GMT+03:00)
                                        Kuwait,
                                        Riyadh, Baghdad
                                    </option>
                                    <option value="Europe/Moscow" {{$tz?($tz=='Europe/Moscow'?'selected':''):''}}>
                                        (GMT+03:00)
                                        Moscow, St. Petersburg, Volgograd
                                    </option>
                                    <option value="Africa/Nairobi" {{$tz?($tz=='Africa/Nairobi'?'selected':''):''}}>
                                        (GMT+03:00)
                                        Nairobi
                                    </option>
                                    <option value="Asia/Tbilisi" {{$tz?($tz=='Asia/Tbilisi'?'selected':''):''}}>(GMT+03:00)
                                        Tbilisi
                                    </option>
                                    <option value="Asia/Tehran" {{$tz?($tz=='Asia/Tehran'?'selected':''):''}}>(GMT+03:30)
                                        Tehran
                                    </option>
                                    <option value="Asia/Muscat" {{$tz?($tz=='Asia/Muscat'?'selected':''):''}}>(GMT+04:00)
                                        Abu Dhabi,
                                        Muscat
                                    </option>
                                    <option value="Asia/Baku" {{$tz?($tz=='Asia/Baku'?'selected':''):''}}>(GMT+04:00) Baku
                                    </option>
                                    <option value="Asia/Yerevan" {{$tz?($tz=='Asia/Yerevan'?'selected':''):''}}>(GMT+04:00)
                                        Yerevan
                                    </option>
                                    <option value="Asia/Kabul" {{$tz?($tz=='Asia/Kabul'?'selected':''):''}}>(GMT+04:30)
                                        Kabul
                                    </option>
                                    <option value="Asia/Yekaterinburg" {{$tz?($tz=='Asia/Yekaterinburg'?'selected':''):''}}>
                                        (GMT+05:00) Yekaterinburg
                                    </option>
                                    <option value="Asia/Karachi" {{$tz?($tz=='Asia/Karachi'?'selected':''):''}}>(GMT+05:00)
                                        Islamabad, Karachi, Tashkent
                                    </option>
                                    <option value="Asia/Calcutta" {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>
                                        (GMT+05:30)
                                        Chennai, Kolkata, Mumbai, New Delhi
                                    </option>
                                    <!-- <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Sri Jayawardenapura</option> -->
                                    <option value="Asia/Katmandu" {{$tz?($tz=='Asia/Katmandu'?'selected':''):''}}>
                                        (GMT+05:45)
                                        Kathmandu
                                    </option>
                                    <option value="Asia/Almaty" {{$tz?($tz=='Asia/Almaty'?'selected':''):''}}>(GMT+06:00)
                                        Almaty,
                                        Novosibirsk
                                    </option>
                                    <option value="Asia/Dhaka" {{$tz?($tz=='Asia/Dhaka'?'selected':''):''}}>(GMT+06:00)
                                        Astana,
                                        Dhaka
                                    </option>
                                    <option value="Asia/Rangoon" {{$tz?($tz=='Asia/Rangoon'?'selected':''):''}}>(GMT+06:30)
                                        Yangon
                                        (Rangoon)
                                    </option>
                                    <option value="Asia/Bangkok" {{$tz?($tz=='"Asia/Bangkok'?'selected':''):''}}>(GMT+07:00)
                                        Bangkok, Hanoi, Jakarta
                                    </option>
                                    <option value="Asia/Krasnoyarsk" {{$tz?($tz=='Asia/Krasnoyarsk'?'selected':''):''}}>
                                        (GMT+07:00)
                                        Krasnoyarsk
                                    </option>
                                    <option value="Asia/Hong_Kong" {{$tz?($tz=='Asia/Hong_Kong'?'selected':''):''}}>
                                        (GMT+08:00)
                                        Beijing, Chongqing, Hong Kong, Urumqi
                                    </option>
                                    <option value="Asia/Kuala_Lumpur" {{$tz?($tz=='Asia/Kuala_Lumpur'?'selected':''):''}}>
                                        (GMT+08:00) Kuala Lumpur, Singapore
                                    </option>
                                    <option value="Asia/Irkutsk" {{$tz?($tz=='Asia/Irkutsk'?'selected':''):''}}>(GMT+08:00)
                                        Irkutsk,
                                        Ulaan Bataar
                                    </option>
                                    <option value="Australia/Perth" {{$tz?($tz=='Australia/Perth'?'selected':''):''}}>
                                        (GMT+08:00)
                                        Perth
                                    </option>
                                    <option value="Asia/Taipei" {{$tz?($tz=='Asia/Taipei'?'selected':''):''}}>(GMT+08:00)
                                        Taipei
                                    </option>
                                    <option value="Asia/Tokyo" {{$tz?($tz=='Asia/Tokyo'?'selected':''):''}}>(GMT+09:00)
                                        Osaka,
                                        Sapporo, Tokyo
                                    </option>
                                    <option value="Asia/Seoul" {{$tz?($tz=='Asia/Seoul'?'selected':''):''}}>(GMT+09:00)
                                        Seoul
                                    </option>
                                    <option value="Asia/Yakutsk" {{$tz?($tz=='Asia/Yakutsk'?'selected':''):''}}>(GMT+09:00)
                                        Yakutsk
                                    </option>
                                    <option value="Australia/Adelaide" {{$tz?($tz=='Australia/Adelaide'?'selected':''):''}}>
                                        (GMT+09:30) Adelaide
                                    </option>
                                    <option value="Australia/Darwin" {{$tz?($tz=='Australia/Darwin'?'selected':''):''}}>
                                        (GMT+09:30)
                                        Darwin
                                    </option>
                                    <option value="Australia/Brisbane" {{$tz?($tz=='Australia/Brisbane'?'selected':''):''}}>
                                        (GMT+10:00) Brisbane
                                    </option>
                                    <option value="Australia/Canberra" {{$tz?($tz=='Australia/Canberra'?'selected':''):''}}>
                                        (GMT+10:00) Canberra, Melbourne, Sydney
                                    </option>
                                    <option value="Australia/Hobart" {{$tz?($tz=='Australia/Hobart'?'selected':''):''}}>
                                        (GMT+10:00)
                                        Hobart
                                    </option>
                                    <option value="Pacific/Guam" {{$tz?($tz=='Pacific/Guam'?'selected':''):''}}>(GMT+10:00)
                                        Guam,
                                        Port Moresby
                                    </option>
                                    <option value="Asia/Vladivostok" {{$tz?($tz=='Asia/Vladivostok'?'selected':''):''}}>
                                        (GMT+10:00)
                                        Vladivostok
                                    </option>
                                    <option value="Asia/Magadan" {{$tz?($tz=='Asia/Magadan'?'selected':''):''}}>(GMT+11:00)
                                        Magadan,
                                        Solomon Is., New Caledonia
                                    </option>
                                    <option value="Pacific/Auckland" {{$tz?($tz=='Pacific/Auckland'?'selected':''):''}}>
                                        (GMT+12:00)
                                        Auckland, Wellington
                                    </option>
                                    <option value="Pacific/Fiji" {{$tz?($tz=='Pacific/Fiji'?'selected':''):''}}>(GMT+12:00)
                                        Fiji,
                                        Kamchatka, Marshall Is.
                                    </option>
                                    <option value="Pacific/Tongatapu" {{$tz?($tz=='Pacific/Tongatapu'?'selected':''):''}}>
                                        (GMT+13:00) Nuku'alofa
                                    </option>
                                </select>
                            </div>
                        </div>

                        @php($cc=\App\Model\BusinessSetting::where('type','country_code')->first())
                        @php($cc=$cc?$cc->value:0)
                        <div class="col-md-4 d-none">
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('country')}} </label>
                                <select id="country" name="country" class="form-control  js-select2-custom">
                                    <option value="AF" {{ $cc?($cc=='AF'?'selected':''):'' }} >Afghanistan</option>
                                    <option value="AX" {{ $cc?($cc=='AX'?'selected':''):'' }} >Åland Islands</option>
                                    <option value="AL" {{ $cc?($cc=='AL'?'selected':''):'' }} >Albania</option>
                                    <option value="DZ" {{ $cc?($cc=='DZ'?'selected':''):'' }}>Algeria</option>
                                    <option value="AS" {{ $cc?($cc=='AS'?'selected':''):'' }}>American Samoa</option>
                                    <option value="AD" {{ $cc?($cc=='AD'?'selected':''):'' }}>Andorra</option>
                                    <option value="AO" {{ $cc?($cc=='AO'?'selected':''):'' }}>Angola</option>
                                    <option value="AI" {{ $cc?($cc=='AI'?'selected':''):'' }}>Anguilla</option>
                                    <option value="AQ" {{ $cc?($cc=='AQ'?'selected':''):'' }}>Antarctica</option>
                                    <option value="AG" {{ $cc?($cc=='AG'?'selected':''):'' }}>Antigua and Barbuda</option>
                                    <option value="AR" {{ $cc?($cc=='AR'?'selected':''):'' }}>Argentina</option>
                                    <option value="AM" {{ $cc?($cc=='AM'?'selected':''):'' }}>Armenia</option>
                                    <option value="AW" {{ $cc?($cc=='AW'?'selected':''):'' }}>Aruba</option>
                                    <option value="AU" {{ $cc?($cc=='AU'?'selected':''):'' }}>Australia</option>
                                    <option value="AT" {{ $cc?($cc=='AT'?'selected':''):'' }}>Austria</option>
                                    <option value="AZ" {{ $cc?($cc=='AZ'?'selected':''):'' }}>Azerbaijan</option>
                                    <option value="BS" {{ $cc?($cc=='BS'?'selected':''):'' }}>Bahamas</option>
                                    <option value="BH" {{ $cc?($cc=='BH'?'selected':''):'' }}>Bahrain</option>
                                    <option value="BD" {{ $cc?($cc=='BD'?'selected':''):'' }}>Bangladesh</option>
                                    <option value="BB" {{ $cc?($cc=='BB'?'selected':''):'' }}>Barbados</option>
                                    <option value="BY" {{ $cc?($cc=='BY'?'selected':''):'' }}>Belarus</option>
                                    <option value="BE" {{ $cc?($cc=='BE'?'selected':''):'' }}>Belgium</option>
                                    <option value="BZ" {{ $cc?($cc=='BZ'?'selected':''):'' }}>Belize</option>
                                    <option value="BJ" {{ $cc?($cc=='BJ'?'selected':''):'' }}>Benin</option>
                                    <option value="BM" {{ $cc?($cc=='BM'?'selected':''):'' }}>Bermuda</option>
                                    <option value="BT" {{ $cc?($cc=='BT'?'selected':''):'' }}>Bhutan</option>
                                    <option value="BO" {{ $cc?($cc=='BO'?'selected':''):'' }}>Bolivia, Plurinational State
                                        of
                                    </option>
                                    <option value="BQ" {{ $cc?($cc=='BQ'?'selected':''):'' }}>Bonaire, Sint Eustatius and
                                        Saba
                                    </option>
                                    <option value="BA" {{ $cc?($cc=='BA'?'selected':''):'' }}>Bosnia and Herzegovina
                                    </option>
                                    <option value="BW" {{ $cc?($cc=='BW'?'selected':''):'' }}>Botswana</option>
                                    <option value="BV" {{ $cc?($cc=='BV'?'selected':''):'' }}>Bouvet Island</option>
                                    <option value="BR" {{ $cc?($cc=='BR'?'selected':''):'' }}>Brazil</option>
                                    <option value="IO" {{ $cc?($cc=='IO'?'selected':''):'' }}>British Indian Ocean
                                        Territory
                                    </option>
                                    <option value="BN" {{ $cc?($cc=='BN'?'selected':''):'' }}>Brunei Darussalam</option>
                                    <option value="BG" {{ $cc?($cc=='BG'?'selected':''):'' }}>Bulgaria</option>
                                    <option value="BF" {{ $cc?($cc=='BF'?'selected':''):'' }}>Burkina Faso</option>
                                    <option value="BI" {{ $cc?($cc=='BI'?'selected':''):'' }}>Burundi</option>
                                    <option value="KH" {{ $cc?($cc=='KH'?'selected':''):'' }}>Cambodia</option>
                                    <option value="CM" {{ $cc?($cc=='CM'?'selected':''):'' }}>Cameroon</option>
                                    <option value="CA" {{ $cc?($cc=='CA'?'selected':''):'' }}>Canada</option>
                                    <option value="CV" {{ $cc?($cc=='CV'?'selected':''):'' }}>Cape Verde</option>
                                    <option value="KY" {{ $cc?($cc=='KY'?'selected':''):'' }}>Cayman Islands</option>
                                    <option value="CF" {{ $cc?($cc=='CF'?'selected':''):'' }}>Central African Republic
                                    </option>
                                    <option value="TD" {{ $cc?($cc=='TD'?'selected':''):'' }}>Chad</option>
                                    <option value="CL" {{ $cc?($cc=='CL'?'selected':''):'' }}>Chile</option>
                                    <option value="CN" {{ $cc?($cc=='CN'?'selected':''):'' }}>China</option>
                                    <option value="CX" {{ $cc?($cc=='CX'?'selected':''):'' }}>Christmas Island</option>
                                    <option value="CC" {{ $cc?($cc=='CC'?'selected':''):'' }}>Cocos (Keeling) Islands
                                    </option>
                                    <option value="CO" {{ $cc?($cc=='CO'?'selected':''):'' }}>Colombia</option>
                                    <option value="KM" {{ $cc?($cc=='KM'?'selected':''):'' }}>Comoros</option>
                                    <option value="CG" {{ $cc?($cc=='CG'?'selected':''):'' }}>Congo</option>
                                    <option value="CD" {{ $cc?($cc=='CD'?'selected':''):'' }}>Congo, the Democratic Republic
                                        of the
                                    </option>
                                    <option value="CK" {{ $cc?($cc=='CK'?'selected':''):'' }}>Cook Islands</option>
                                    <option value="CR" {{ $cc?($cc=='CR'?'selected':''):'' }}>Costa Rica</option>
                                    <option value="CI" {{ $cc?($cc=='CI'?'selected':''):'' }}>Côte d'Ivoire</option>
                                    <option value="HR" {{ $cc?($cc=='HR'?'selected':''):'' }}>Croatia</option>
                                    <option value="CU" {{ $cc?($cc=='CU'?'selected':''):'' }}>Cuba</option>
                                    <option value="CW" {{ $cc?($cc=='CW'?'selected':''):'' }}>Curaçao</option>
                                    <option value="CY" {{ $cc?($cc=='CY'?'selected':''):'' }}>Cyprus</option>
                                    <option value="CZ" {{ $cc?($cc=='CZ'?'selected':''):'' }}>Czech Republic</option>
                                    <option value="DK" {{ $cc?($cc=='DK'?'selected':''):'' }}>Denmark</option>
                                    <option value="DJ" {{ $cc?($cc=='DJ'?'selected':''):'' }}>Djibouti</option>
                                    <option value="DM" {{ $cc?($cc=='DM'?'selected':''):'' }}>Dominica</option>
                                    <option value="DO" {{ $cc?($cc=='DO'?'selected':''):'' }}>Dominican Republic</option>
                                    <option value="EC" {{ $cc?($cc=='EC'?'selected':''):'' }}>Ecuador</option>
                                    <option value="EG" {{ $cc?($cc=='EG'?'selected':''):'' }}>Egypt</option>
                                    <option value="SV" {{ $cc?($cc=='SV'?'selected':''):'' }}>El Salvador</option>
                                    <option value="GQ" {{ $cc?($cc=='GQ'?'selected':''):'' }}>Equatorial Guinea</option>
                                    <option value="ER" {{ $cc?($cc=='ER'?'selected':''):'' }}>Eritrea</option>
                                    <option value="EE" {{ $cc?($cc=='EE'?'selected':''):'' }}>Estonia</option>
                                    <option value="ET" {{ $cc?($cc=='ET'?'selected':''):'' }}>Ethiopia</option>
                                    <option value="FK" {{ $cc?($cc=='FK'?'selected':''):'' }}>Falkland Islands (Malvinas)
                                    </option>
                                    <option value="FO" {{ $cc?($cc=='FO'?'selected':''):'' }}>Faroe Islands</option>
                                    <option value="FJ" {{ $cc?($cc=='FJ'?'selected':''):'' }}>Fiji</option>
                                    <option value="FI" {{ $cc?($cc=='FI'?'selected':''):'' }}>Finland</option>
                                    <option value="FR" {{ $cc?($cc=='FR'?'selected':''):'' }}>France</option>
                                    <option value="GF" {{ $cc?($cc=='GF'?'selected':''):'' }}>French Guiana</option>
                                    <option value="PF" {{ $cc?($cc=='PF'?'selected':''):'' }}>French Polynesia</option>
                                    <option value="TF" {{ $cc?($cc=='TF'?'selected':''):'' }}>French Southern Territories
                                    </option>
                                    <option value="GA" {{ $cc?($cc=='GA'?'selected':''):'' }}>Gabon</option>
                                    <option value="GM" {{ $cc?($cc=='GM'?'selected':''):'' }}>Gambia</option>
                                    <option value="GE" {{ $cc?($cc=='GE'?'selected':''):'' }}>Georgia</option>
                                    <option value="DE" {{ $cc?($cc=='DE'?'selected':''):'' }}>Germany</option>
                                    <option value="GH" {{ $cc?($cc=='GH'?'selected':''):'' }}>Ghana</option>
                                    <option value="GI" {{ $cc?($cc=='GI'?'selected':''):'' }}>Gibraltar</option>
                                    <option value="GR" {{ $cc?($cc=='GR'?'selected':''):'' }}>Greece</option>
                                    <option value="GL" {{ $cc?($cc=='GL'?'selected':''):'' }}>Greenland</option>
                                    <option value="GD" {{ $cc?($cc=='GD'?'selected':''):'' }}>Grenada</option>
                                    <option value="GP" {{ $cc?($cc=='GP'?'selected':''):'' }}>Guadeloupe</option>
                                    <option value="GU" {{ $cc?($cc=='GU'?'selected':''):'' }}>Guam</option>
                                    <option value="GT" {{ $cc?($cc=='GT'?'selected':''):'' }}>Guatemala</option>
                                    <option value="GG" {{ $cc?($cc=='GG'?'selected':''):'' }}>Guernsey</option>
                                    <option value="GN" {{ $cc?($cc=='GN'?'selected':''):'' }}>Guinea</option>
                                    <option value="GW" {{ $cc?($cc=='GW'?'selected':''):'' }}>Guinea-Bissau</option>
                                    <option value="GY" {{ $cc?($cc=='GY'?'selected':''):'' }}>Guyana</option>
                                    <option value="HT" {{ $cc?($cc=='HT'?'selected':''):'' }}>Haiti</option>
                                    <option value="HM" {{ $cc?($cc=='HM'?'selected':''):'' }}>Heard Island and McDonald
                                        Islands
                                    </option>
                                    <option value="VA" {{ $cc?($cc=='VA'?'selected':''):'' }}>Holy See (Vatican City
                                        State)
                                    </option>
                                    <option value="HN" {{ $cc?($cc=='HN'?'selected':''):'' }}>Honduras</option>
                                    <option value="HK" {{ $cc?($cc=='HK'?'selected':''):'' }}>Hong Kong</option>
                                    <option value="HU" {{ $cc?($cc=='HU'?'selected':''):'' }}>Hungary</option>
                                    <option value="IS" {{ $cc?($cc=='IS'?'selected':''):'' }}>Iceland</option>
                                    <option value="IN" {{ $cc?($cc=='IN'?'selected':''):'' }}>India</option>
                                    <option value="ID" {{ $cc?($cc=='ID'?'selected':''):'' }}>Indonesia</option>
                                    <option value="IR" {{ $cc?($cc=='IR'?'selected':''):'' }}>Iran, Islamic Republic of
                                    </option>
                                    <option value="IQ" {{ $cc?($cc=='IQ'?'selected':''):'' }}>Iraq</option>
                                    <option value="IE" {{ $cc?($cc=='IE'?'selected':''):'' }}>Ireland</option>
                                    <option value="IM" {{ $cc?($cc=='IM'?'selected':''):'' }}>Isle of Man</option>
                                    <option value="IL" {{ $cc?($cc=='IL'?'selected':''):'' }}>Israel</option>
                                    <option value="IT" {{ $cc?($cc=='IT'?'selected':''):'' }}>Italy</option>
                                    <option value="JM" {{ $cc?($cc=='JM'?'selected':''):'' }}>Jamaica</option>
                                    <option value="JP" {{ $cc?($cc=='JP'?'selected':''):'' }}>Japan</option>
                                    <option value="JE" {{ $cc?($cc=='JE'?'selected':''):'' }}>Jersey</option>
                                    <option value="JO" {{ $cc?($cc=='JO'?'selected':''):'' }}>Jordan</option>
                                    <option value="KZ" {{ $cc?($cc=='KZ'?'selected':''):'' }}>Kazakhstan</option>
                                    <option value="KE" {{ $cc?($cc=='KE'?'selected':''):'' }}>Kenya</option>
                                    <option value="KI" {{ $cc?($cc=='KI'?'selected':''):'' }}>Kiribati</option>
                                    <option value="KP" {{ $cc?($cc=='KP'?'selected':''):'' }}>Korea, Democratic People's
                                        Republic of
                                    </option>
                                    <option value="KR" {{ $cc?($cc=='KR'?'selected':''):'' }}>Korea, Republic of</option>
                                    <option value="KW" {{ $cc?($cc=='KW'?'selected':''):'' }}>Kuwait</option>
                                    <option value="KG" {{ $cc?($cc=='KG'?'selected':''):'' }}>Kyrgyzstan</option>
                                    <option value="LA" {{ $cc?($cc=='LA'?'selected':''):'' }}>Lao People's Democratic
                                        Republic
                                    </option>
                                    <option value="LV" {{ $cc?($cc=='LV'?'selected':''):'' }}>Latvia</option>
                                    <option value="LB" {{ $cc?($cc=='LB'?'selected':''):'' }}>Lebanon</option>
                                    <option value="LS" {{ $cc?($cc=='LS'?'selected':''):'' }}>Lesotho</option>
                                    <option value="LR" {{ $cc?($cc=='LR'?'selected':''):'' }}>Liberia</option>
                                    <option value="LY" {{ $cc?($cc=='LY'?'selected':''):'' }}>Libya</option>
                                    <option value="LI" {{ $cc?($cc=='LI'?'selected':''):'' }}>Liechtenstein</option>
                                    <option value="LT" {{ $cc?($cc=='LT'?'selected':''):'' }}>Lithuania</option>
                                    <option value="LU" {{ $cc?($cc=='LU'?'selected':''):'' }}>Luxembourg</option>
                                    <option value="MO" {{ $cc?($cc=='MO'?'selected':''):'' }}>Macao</option>
                                    <option value="MK" {{ $cc?($cc=='MK'?'selected':''):'' }}>Macedonia, the former Yugoslav
                                        Republic of
                                    </option>
                                    <option value="MG" {{ $cc?($cc=='MG'?'selected':''):'' }}>Madagascar</option>
                                    <option value="MW" {{ $cc?($cc=='MW'?'selected':''):'' }}>Malawi</option>
                                    <option value="MY" {{ $cc?($cc=='MY'?'selected':''):'' }}>Malaysia</option>
                                    <option value="MV" {{ $cc?($cc=='MV'?'selected':''):'' }}>Maldives</option>
                                    <option value="ML" {{ $cc?($cc=='ML'?'selected':''):'' }}>Mali</option>
                                    <option value="MT" {{ $cc?($cc=='MT'?'selected':''):'' }}>Malta</option>
                                    <option value="MH" {{ $cc?($cc=='MH'?'selected':''):'' }}>Marshall Islands</option>
                                    <option value="MQ" {{ $cc?($cc=='MQ'?'selected':''):'' }}>Martinique</option>
                                    <option value="MR" {{ $cc?($cc=='MR'?'selected':''):'' }}>Mauritania</option>
                                    <option value="MU" {{ $cc?($cc=='MU'?'selected':''):'' }}>Mauritius</option>
                                    <option value="YT" {{ $cc?($cc=='YT'?'selected':''):'' }}>Mayotte</option>
                                    <option value="MX" {{ $cc?($cc=='MX'?'selected':''):'' }}>Mexico</option>
                                    <option value="FM" {{ $cc?($cc=='FM'?'selected':''):'' }}>Micronesia, Federated States
                                        of
                                    </option>
                                    <option value="MD" {{ $cc?($cc=='MD'?'selected':''):'' }}>Moldova, Republic of</option>
                                    <option value="MC" {{ $cc?($cc=='MC'?'selected':''):'' }}>Monaco</option>
                                    <option value="MN" {{ $cc?($cc=='MN'?'selected':''):'' }}>Mongolia</option>
                                    <option value="ME" {{ $cc?($cc=='ME'?'selected':''):'' }}>Montenegro</option>
                                    <option value="MS" {{ $cc?($cc=='MS'?'selected':''):'' }}>Montserrat</option>
                                    <option value="MA" {{ $cc?($cc=='MA'?'selected':''):'' }}>Morocco</option>
                                    <option value="MZ" {{ $cc?($cc=='MZ'?'selected':''):'' }}>Mozambique</option>
                                    <option value="MM" {{ $cc?($cc=='MM'?'selected':''):'' }}>Myanmar</option>
                                    <option value="NA" {{ $cc?($cc=='NA'?'selected':''):'' }}>Namibia</option>
                                    <option value="NR" {{ $cc?($cc=='NR'?'selected':''):'' }}>Nauru</option>
                                    <option value="NP" {{ $cc?($cc=='NP'?'selected':''):'' }}>Nepal</option>
                                    <option value="NL" {{ $cc?($cc=='NL'?'selected':''):'' }}>Netherlands</option>
                                    <option value="NC" {{ $cc?($cc=='NC'?'selected':''):'' }}>New Caledonia</option>
                                    <option value="NZ" {{ $cc?($cc=='NZ'?'selected':''):'' }}>New Zealand</option>
                                    <option value="NI" {{ $cc?($cc=='NI'?'selected':''):'' }}>Nicaragua</option>
                                    <option value="NE" {{ $cc?($cc=='NE'?'selected':''):'' }}>Niger</option>
                                    <option value="NG" {{ $cc?($cc=='NG'?'selected':''):'' }}>Nigeria</option>
                                    <option value="NU" {{ $cc?($cc=='NU'?'selected':''):'' }}>Niue</option>
                                    <option value="NF" {{ $cc?($cc=='NF'?'selected':''):'' }}>Norfolk Island</option>
                                    <option value="MP" {{ $cc?($cc=='MP'?'selected':''):'' }}>Northern Mariana Islands
                                    </option>
                                    <option value="NO" {{ $cc?($cc=='NO'?'selected':''):'' }}>Norway</option>
                                    <option value="OM" {{ $cc?($cc=='OM'?'selected':''):'' }}>Oman</option>
                                    <option value="PK" {{ $cc?($cc=='PK'?'selected':''):'' }}>Pakistan</option>
                                    <option value="PW" {{ $cc?($cc=='PW'?'selected':''):'' }}>Palau</option>
                                    <option value="PS" {{ $cc?($cc=='PS'?'selected':''):'' }}>Palestinian Territory,
                                        Occupied
                                    </option>
                                    <option value="PA" {{ $cc?($cc=='PA'?'selected':''):'' }}>Panama</option>
                                    <option value="PG" {{ $cc?($cc=='PG'?'selected':''):'' }}>Papua New Guinea</option>
                                    <option value="PY" {{ $cc?($cc=='PY'?'selected':''):'' }}>Paraguay</option>
                                    <option value="PE" {{ $cc?($cc=='PE'?'selected':''):'' }}>Peru</option>
                                    <option value="PH" {{ $cc?($cc=='PH'?'selected':''):'' }}>Philippines</option>
                                    <option value="PN" {{ $cc?($cc=='PN'?'selected':''):'' }}>Pitcairn</option>
                                    <option value="PL" {{ $cc?($cc=='PL'?'selected':''):'' }}>Poland</option>
                                    <option value="PT" {{ $cc?($cc=='PT'?'selected':''):'' }}>Portugal</option>
                                    <option value="PR" {{ $cc?($cc=='PR'?'selected':''):'' }}>Puerto Rico</option>
                                    <option value="QA" {{ $cc?($cc=='QA'?'selected':''):'' }}>Qatar</option>
                                    <option value="RE" {{ $cc?($cc=='RE'?'selected':''):'' }}>Réunion</option>
                                    <option value="RO" {{ $cc?($cc=='RO'?'selected':''):'' }}>Romania</option>
                                    <option value="RU" {{ $cc?($cc=='RU'?'selected':''):'' }}>Russian Federation</option>
                                    <option value="RW" {{ $cc?($cc=='RW'?'selected':''):'' }}>Rwanda</option>
                                    <option value="BL" {{ $cc?($cc=='BL'?'selected':''):'' }}>Saint Barthélemy</option>
                                    <option value="SH" {{ $cc?($cc=='SH'?'selected':''):'' }}>Saint Helena, Ascension and
                                        Tristan da Cunha
                                    </option>
                                    <option value="KN" {{ $cc?($cc=='KN'?'selected':''):'' }}>Saint Kitts and Nevis</option>
                                    <option value="LC" {{ $cc?($cc=='LC'?'selected':''):'' }}>Saint Lucia</option>
                                    <option value="MF" {{ $cc?($cc=='MF'?'selected':''):'' }}>Saint Martin (French part)
                                    </option>
                                    <option value="PM" {{ $cc?($cc=='PM'?'selected':''):'' }}>Saint Pierre and Miquelon
                                    </option>
                                    <option value="VC" {{ $cc?($cc=='VC'?'selected':''):'' }}>Saint Vincent and the
                                        Grenadines
                                    </option>
                                    <option value="WS" {{ $cc?($cc=='WS'?'selected':''):'' }}>Samoa</option>
                                    <option value="SM" {{ $cc?($cc=='SM'?'selected':''):'' }}>San Marino</option>
                                    <option value="ST" {{ $cc?($cc=='ST'?'selected':''):'' }}>Sao Tome and Principe</option>
                                    <option value="SA" {{ $cc?($cc=='SA'?'selected':''):'' }}>Saudi Arabia</option>
                                    <option value="SN" {{ $cc?($cc=='SN'?'selected':''):'' }}>Senegal</option>
                                    <option value="RS" {{ $cc?($cc=='RS'?'selected':''):'' }}>Serbia</option>
                                    <option value="SC" {{ $cc?($cc=='SC'?'selected':''):'' }}>Seychelles</option>
                                    <option value="SL" {{ $cc?($cc=='SL'?'selected':''):'' }}>Sierra Leone</option>
                                    <option value="SG" {{ $cc?($cc=='SG'?'selected':''):'' }}>Singapore</option>
                                    <option value="SX" {{ $cc?($cc=='SX'?'selected':''):'' }}>Sint Maarten (Dutch part)
                                    </option>
                                    <option value="SK" {{ $cc?($cc=='SK'?'selected':''):'' }}>Slovakia</option>
                                    <option value="SI" {{ $cc?($cc=='SI'?'selected':''):'' }}>Slovenia</option>
                                    <option value="SB" {{ $cc?($cc=='SB'?'selected':''):'' }}>Solomon Islands</option>
                                    <option value="SO" {{ $cc?($cc=='SO'?'selected':''):'' }}>Somalia</option>
                                    <option value="ZA" {{ $cc?($cc=='ZA'?'selected':''):'' }}>South Africa</option>
                                    <option value="GS" {{ $cc?($cc=='GS'?'selected':''):'' }}>South Georgia and the South
                                        Sandwich Islands
                                    </option>
                                    <option value="SS" {{ $cc?($cc=='SS'?'selected':''):'' }}>South Sudan</option>
                                    <option value="ES" {{ $cc?($cc=='ES'?'selected':''):'' }}>Spain</option>
                                    <option value="LK" {{ $cc?($cc=='LK'?'selected':''):'' }}>Sri Lanka</option>
                                    <option value="SD" {{ $cc?($cc=='SD'?'selected':''):'' }}>Sudan</option>
                                    <option value="SR" {{ $cc?($cc=='SR'?'selected':''):'' }}>Suriname</option>
                                    <option value="SJ" {{ $cc?($cc=='SJ'?'selected':''):'' }}>Svalbard and Jan Mayen
                                    </option>
                                    <option value="SZ" {{ $cc?($cc=='SZ'?'selected':''):'' }}>Swaziland</option>
                                    <option value="SE" {{ $cc?($cc=='SE'?'selected':''):'' }}>Sweden</option>
                                    <option value="CH" {{ $cc?($cc=='CH'?'selected':''):'' }}>Switzerland</option>
                                    <option value="SY" {{ $cc?($cc=='SY'?'selected':''):'' }}>Syrian Arab Republic</option>
                                    <option value="TW" {{ $cc?($cc=='TW'?'selected':''):'' }}>Taiwan, Province of China
                                    </option>
                                    <option value="TJ" {{ $cc?($cc=='TJ'?'selected':''):'' }}>Tajikistan</option>
                                    <option value="TZ" {{ $cc?($cc=='TZ'?'selected':''):'' }}>Tanzania, United Republic of
                                    </option>
                                    <option value="TH" {{ $cc?($cc=='TH'?'selected':''):'' }}>Thailand</option>
                                    <option value="TL" {{ $cc?($cc=='TL'?'selected':''):'' }}>Timor-Leste</option>
                                    <option value="TG" {{ $cc?($cc=='TG'?'selected':''):'' }}>Togo</option>
                                    <option value="TK" {{ $cc?($cc=='TK'?'selected':''):'' }}>Tokelau</option>
                                    <option value="TO" {{ $cc?($cc=='TO'?'selected':''):'' }}>Tonga</option>
                                    <option value="TT" {{ $cc?($cc=='TT'?'selected':''):'' }}>Trinidad and Tobago</option>
                                    <option value="TN" {{ $cc?($cc=='TN'?'selected':''):'' }}>Tunisia</option>
                                    <option value="TR" {{ $cc?($cc=='TR'?'selected':''):'' }}>Turkey</option>
                                    <option value="TM" {{ $cc?($cc=='TM'?'selected':''):'' }}>Turkmenistan</option>
                                    <option value="TC" {{ $cc?($cc=='TC'?'selected':''):'' }}>Turks and Caicos Islands
                                    </option>
                                    <option value="TV" {{ $cc?($cc=='TV'?'selected':''):'' }}>Tuvalu</option>
                                    <option value="UG" {{ $cc?($cc=='UG'?'selected':''):'' }}>Uganda</option>
                                    <option value="UA" {{ $cc?($cc=='UA'?'selected':''):'' }}>Ukraine</option>
                                    <option value="AE" {{ $cc?($cc=='AE'?'selected':''):'' }}>United Arab Emirates</option>
                                    <option value="GB" {{ $cc?($cc=='GB'?'selected':''):'' }}>United Kingdom</option>
                                    <option value="US" {{ $cc?($cc=='US'?'selected':''):'' }}>United States</option>
                                    <option value="UM" {{ $cc?($cc=='UM'?'selected':''):'' }}>United States Minor Outlying
                                        Islands
                                    </option>
                                    <option value="UY" {{ $cc?($cc=='UY'?'selected':''):'' }}>Uruguay</option>
                                    <option value="UZ" {{ $cc?($cc=='UZ'?'selected':''):'' }}>Uzbekistan</option>
                                    <option value="VU" {{ $cc?($cc=='VU'?'selected':''):'' }}>Vanuatu</option>
                                    <option value="VE" {{ $cc?($cc=='VE'?'selected':''):'' }}>Venezuela, Bolivarian Republic
                                        of
                                    </option>
                                    <option value="VN" {{ $cc?($cc=='VN'?'selected':''):'' }}>Viet Nam</option>
                                    <option value="VG" {{ $cc?($cc=='VG'?'selected':''):'' }}>Virgin Islands, British
                                    </option>
                                    <option value="VI" {{ $cc?($cc=='VI'?'selected':''):'' }}>Virgin Islands, U.S.</option>
                                    <option value="WF" {{ $cc?($cc=='WF'?'selected':''):'' }}>Wallis and Futuna</option>
                                    <option value="EH" {{ $cc?($cc=='EH'?'selected':''):'' }}>Western Sahara</option>
                                    <option value="YE" {{ $cc?($cc=='YE'?'selected':''):'' }}>Yemen</option>
                                    <option value="ZM" {{ $cc?($cc=='ZM'?'selected':''):'' }}>Zambia</option>
                                    <option value="ZW" {{ $cc?($cc=='ZW'?'selected':''):'' }}>Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-1">
                        <i class="tio-briefcase"></i>
                        {{\App\CPU\translate('business_Information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 d-none">
                            @php($config=\App\CPU\Helpers::get_business_settings('currency_symbol_position'))

                            <label class="title-color d-flex">{{\App\CPU\translate('Currency')}} {{\App\CPU\translate('Position')}}</label>
                            <div class="form-row">
                                <div class="col-sm mb-2 mb-sm-0">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio custom-radio-reverse"
                                            onclick="currency_symbol_position('{{route('admin.business-settings.web-config.currency-symbol-position',['left'])}}')">
                                            <input type="radio" class="custom-control-input"
                                                name="projectViewNewProjectTypeRadio"
                                                id="projectViewNewProjectTypeRadio1" {{(isset($config) && $config=='left')?'checked':''}}>
                                            <label class="custom-control-label media align-items-center"
                                                for="projectViewNewProjectTypeRadio1">
                                                <i class="tio-agenda-view-outlined text-muted mr-2"></i>
                                                <span class="media-body">
                                                {{\App\CPU\BackEndHelper::currency_symbol()}} {{\App\CPU\translate('Left')}}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>

                                <div class="col-sm mb-2 mb-sm-0">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio custom-radio-reverse"
                                            onclick="currency_symbol_position('{{route('admin.business-settings.web-config.currency-symbol-position',['right'])}}')">
                                            <input type="radio" class="custom-control-input"
                                                name="projectViewNewProjectTypeRadio"
                                                id="projectViewNewProjectTypeRadio2" {{(isset($config) && $config=='right')?'checked':''}}>
                                            <label class="custom-control-label media align-items-center"
                                                for="projectViewNewProjectTypeRadio2">
                                                <i class="tio-table text-muted mr-2"></i>
                                                <span
                                                    class="media-body">{{\App\CPU\translate('Right')}} {{\App\CPU\BackEndHelper::currency_symbol()}}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>

                        @php($fpv=\App\CPU\Helpers::get_business_settings('forgot_password_verification'))
                        <div class="col-md-4 ">
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('forgot_password_verification_by')}} </label>
                                <select name="forgot_password_verification" class="form-control  js-select2-custom">
                                    <option value="email" {{ isset($fpv)?($fpv=='email'?'selected':''):'' }} >Email</option>
                                    <option value="phone" {{ isset($fpv)?($fpv=='phone'?'selected':''):'' }} >Phone</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            @php($pv=\App\CPU\Helpers::get_business_settings('phone_verification'))
                            <div class="form-group">
                                <label class="title-color d-flex gap-1">{{\App\CPU\translate('OTP')}} {{\App\CPU\translate('verification')}}<span class="text-danger">*</span></label>

                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1"
                                                name="phone_verification"
                                                id="phone_verification_on" {{(isset($pv) && $pv==1)?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="phone_verification_on">{{\App\CPU\translate('on')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0"
                                                name="phone_verification"
                                                id="phone_verification_off" {{(isset($pv) && $pv==0)?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="phone_verification_off">{{\App\CPU\translate('off')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            @php($ev=\App\CPU\Helpers::get_business_settings('email_verification'))
                            <div class="form-group">
                                <label class="title-color d-flex gap-1">{{\App\CPU\translate('email')}} {{\App\CPU\translate('verification')}}<span class="text-danger">*</span></label>

                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1"
                                                name="email_verification"
                                                id="email_verification_on" {{$ev==1?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="email_verification_on">{{\App\CPU\translate('on')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0"
                                                name="email_verification"
                                                id="email_verification_off" {{$ev==0?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="email_verification_off">{{\App\CPU\translate('off')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            @php($order_verification=\App\CPU\Helpers::get_business_settings('order_verification'))
                            <div class="form-group">
                                <label class="title-color d-flex gap-1">{{\App\CPU\translate('order')}} {{\App\CPU\translate('verification')}}<span class="text-danger">*</span></label>

                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1"
                                                name="order_verification"
                                                id="order_verification1" {{$order_verification==1?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="order_verification1">{{\App\CPU\translate('on')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0"
                                                name="order_verification"
                                                id="order_verification2" {{$order_verification==0?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="order_verification2">{{\App\CPU\translate('off')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($company_copyright_text=\App\Model\BusinessSetting::where('type','company_copyright_text')->first())
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('Company_Copyright_Text')}}</label>
                                <input class="form-control" type="text" name="company_copyright_text"
                                    value="{{ $company_copyright_text->value?$company_copyright_text->value:" " }}"
                                    placeholder="{{\App\CPU\translate('company_copyright_text')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($pagination_limit=\App\CPU\Helpers::get_business_settings('pagination_limit'))
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{\App\CPU\translate('pagination')}} {{\App\CPU\translate('settings')}}</label>
                                <input type="number" value="{{$pagination_limit}}"
                                    name="pagination_limit" class="form-control" placeholder="25">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @php($decimal_point=\App\Model\BusinessSetting::where('type','decimal_point_settings')->first())
                            <div class="form-group">
                                <label
                                    class="input-label text-capitalize">{{\App\CPU\translate('digit_after_decimal_point')}}({{\App\CPU\translate(' ex: 0.00')}})</label>
                                <input type="number" value="{{$decimal_point->value}}"
                                       name="decimal_point_settings" class="form-control" min="0" placeholder="{{\App\CPU\translate('4')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3 d-none">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center gap-2">
                                <img src="{{asset('/assets/back-end/img/website-color.png')}}" alt="">
                                {{\App\CPU\translate('Website Color')}}
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-wrap gap-4">
                            @php($colors=\App\Model\BusinessSetting::where(['type'=>'colors'])->first())
                            @if(isset($colors))
                                @php($data=json_decode($colors['value']))
                            @else
                                @php(\Illuminate\Support\Facades\DB::table('business_settings')->insert([
                                        'type'=>'colors',
                                        'value'=>json_encode(
                                            [
                                                'primary'=>null,
                                                'secondary'=>null,
                                            ])
                                    ]))
                                @php($colors=\App\Model\BusinessSetting::where(['type'=>'colors'])->first())
                                @php($data=json_decode($colors['value']))
                            @endif
                            <div class="form-group">
                                <input type="color" name="primary" value="{{ $data->primary }}"
                                class="form-control form-control_color">
                                <div class="text-center">
                                    <div class="title-color mb-4 mt-3">#0071DC</div>
                                    <label class="title-color text-capitalize">{{\App\CPU\translate('Primary_Color')}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="color" name="secondary" value="{{ $data->secondary }}"
                                class="form-control form-control_color">
                                <div class="text-center">
                                    <div class="title-color mb-4 mt-3">#334257</div>
                                    <label class="title-color text-capitalize">
                                        {{\App\CPU\translate('Secondary Color')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/assets/back-end/img/header-logo.png')}}" alt="">
                                {{\App\CPU\translate('Website Header Logo')}}
                            </h5>
                            <span class="badge badge-soft-info">( 250x60 px )</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerWL"
                                        onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->pluck('value')[0]}}">
                            </center>
                            <div class="mt-4 position-relative">
                                <input type="file" name="company_web_logo" id="customFileUploadWL"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadWL">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{\App\CPU\translate('Website_Footer_Logo')}}
                            </h5>
                            <span class="badge badge-soft-info">( {{\App\CPU\translate('250x60 px')}})</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerWFL"
                                    onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_footer_logo'])->pluck('value')[0]}}">
                            </center>
                            <div class="position-relative mt-4">
                                <input type="file" name="company_footer_logo" id="customFileUploadWFL"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadWFL">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{\App\CPU\translate('Website Favicon')}}
                            </h5>
                            <span class="badge badge-soft-info">( {{\App\CPU\translate('ratio 1:1 ')}})</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerFI"
                                        onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_fav_icon'])->pluck('value')[0]}}">
                            </center>
                            <div class="position-relative mt-4">
                                <input type="file" name="company_fav_icon" id="customFileUploadFI"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadFI">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{\App\CPU\translate('Loading Gif')}}
                            </h5>
                            <span class="badge badge-soft-info">( {{\App\CPU\translate('ratio 1:1 ')}}  )</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerLoader"
                                        onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}">
                            </center>
                            <div class="position-relative mt-4">
                                <input type="file" name="loader_gif" id="customFileUploadLoader"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadLoader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{\App\CPU\translate('App_Logo')}}
                            </h5>
                            <span class="badge badge-soft-info">( 100X60 px )</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerML"
                                        onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_mobile_logo'])->pluck('value')[0]}}">
                            </center>
                            <div class="mt-4 position-relative">
                                <input type="file" name="company_mobile_logo" id="customFileUploadML"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadML">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="{{asset('assets/back-end')}}/js/tags-input.min.js"></script>
    <script src="{{ asset('assets/select2/js/select2.min.js')}}"></script>
    <script>

        $("#customFileUploadShop").change(function () {
            read_image(this, 'viewerShop');
        });

        $("#customFileUploadWL").change(function () {
            read_image(this, 'viewerWL');
        });

        $("#customFileUploadWFL").change(function () {
            read_image(this, 'viewerWFL');
        });

        $("#customFileUploadML").change(function () {
            read_image(this, 'viewerML');
        });

        $("#customFileUploadFI").change(function () {
            read_image(this, 'viewerFI');
        });

        $("#customFileUploadLoader").change(function () {
            read_image(this, 'viewerLoader');
        });

        function read_image(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

    </script>
    <script>
        $(document).ready(function () {
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>

    <script>
        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
        @php($language = $language->value ?? null)
        let language = {{$language}};
        $('#language').val(language);
    </script>

    <script>
        function maintenance_mode() {
            @if(env('APP_MODE')=='demo')
            call_demo();
            @else
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure')}}?',
                text: '{{\App\CPU\translate('Be careful before you turn on/off maintenance mode')}}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: '{{route('admin.maintenance-mode')}}',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            toastr.success(data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })
            @endif
        };

        function currency_symbol_position(route) {
            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success(data.message);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#phone_verification_on").click(function () {
                @if(env('APP_MODE')!='demo')
                if ($('#email_verification_on').prop("checked") == true) {
                    $('#email_verification_off').prop("checked", true);
                    $('#email_verification_on').prop("checked", false);
                    const message = "{{\App\CPU\translate('Both Phone & Email verification can not be active at a time')}}";
                    toastr.info(message);
                }
                @else
                call_demo();
                @endif
            });
            $("#email_verification_on").click(function () {
                if ($('#phone_verification_on').prop("checked") == true) {
                    $('#phone_verification_off').prop("checked", true);
                    $('#phone_verification_on').prop("checked", false);
                    const message = "{{\App\CPU\translate('Both Phone & Email verification can not be active at a time')}}";
                    toastr.info(message);
                }
            });
        });
    </script>
@endpush
