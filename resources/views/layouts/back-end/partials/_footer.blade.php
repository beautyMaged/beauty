

<div class="footer">
    <div class="row justify-content-between align-items-center">
        <div class="col-lg-4 mb-3 mb-lg-0">
            <p class="font-size-sm mb-0 title-color text-center text-lg-left">
                &copy; {{\App\Model\BusinessSetting::where(['type'=>'company_name'])->first()->value}}. <span
                    class="d-none d-sm-inline-block">{{\App\Model\BusinessSetting::where(['type'=>'company_copyright_text'])->first()->value}}</span>
            </p>
        </div>
        <div class="col-lg-8">
            <div class="d-flex justify-content-center justify-content-lg-end">
                <!-- List Dot -->
                <ul class="list-inline list-footer-icon justify-content-center justify-content-lg-start mb-0">
                    <li class="list-inline-item">
                        <a class="list-separator-link" href="{{route('admin.business-settings.web-config.index')}}">
                            <i class="tio-settings"></i>
                            {{\App\CPU\translate('Business Setup')}}
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <a class="list-separator-link"href="{{route('admin.profile.update',auth('admin')->user()->id)}}">
                            <i class="tio-user"></i>
                            {{\App\CPU\translate('Profile')}}
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <a class="list-separator-link" href="{{route('admin.dashboard.index')}}">
                            <i class="tio-home-outlined"></i>
                            {{\App\CPU\translate('Home')}}
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <label class="badge badge-soft-version text-capitalize">
                            {{\App\CPU\translate('Software version')}} {{ env('SOFTWARE_VERSION') }}
                        </label>
                    </li>
                </ul>
                <!-- End List Dot -->
            </div>
        </div>
    </div>
</div>
