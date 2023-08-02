<div class="col-lg-4">
    <!-- Card -->
    <div class="card h-100 d-flex justify-content-center align-items-center">
        <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
            <img width="48" class="mb-2" src="{{asset('/assets/back-end/img/inhouse-earning.png')}}" alt="">
            <h3 class="for-card-count mb-0 fz-24">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['inhouse_earning']))}}</h3>
            <div class="text-capitalize mb-30">
                {{\App\CPU\translate('in-house_earning')}}
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<div class="col-lg-8">
    <div class="row g-2">
        <div class="col-md-6">
            <div class="card card-body h-100 justify-content-center">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column align-items-start">
                        <h3 class="mb-1 fz-24">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['commission_earned']))}}</h3>
                        <div class="text-capitalize mb-0">{{\App\CPU\translate('commission_earned')}}</div>
                    </div>
                    <div>
                        <img width="40" class="mb-2" src="{{asset('/assets/back-end/img/ce.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body h-100 justify-content-center">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column align-items-start">
                        <h3 class="mb-1 fz-24">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['delivery_charge_earned']))}}</h3>
                        <div class="text-capitalize mb-0">{{\App\CPU\translate('delivery_charge_earned')}}</div>
                    </div>
                    <div>
                        <img width="40" class="mb-2" src="{{asset('/assets/back-end/img/dce.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body h-100 justify-content-center">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column align-items-start">
                        <h3 class="mb-1 fz-24">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['total_tax_collected']))}}</h3>
                        <div class="text-capitalize mb-0">{{\App\CPU\translate('total_tax_collected')}}</div>
                    </div>
                    <div>
                        <img width="40" class="mb-2" src="{{asset('/assets/back-end/img/ttc.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body h-100 justify-content-center">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column align-items-start">
                        <h3 class="mb-1 fz-24">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['pending_amount']))}}</h3>
                        <div class="text-capitalize mb-0">{{\App\CPU\translate('pending_amount')}}</div>
                    </div>
                    <div>
                        <img width="40" class="mb-2" src="{{asset('/assets/back-end/img/pa.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
