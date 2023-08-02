@extends('layouts.front-end.app')

@section('title','')

@push('css_or_js')

@endpush

@section('content')
    <!-- Page Title-->
    <div class="container __inline-45">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9">
                <div class="sidebar_heading">
                    <h1 class="h3  mb-0 folot-left headerTitle">{{\App\CPU\translate('ADDRESSES')}}</h1>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-p btn-b float-right" data-toggle="modal"
                            data-target="#exampleModal">{{\App\CPU\translate('Add New Address')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 __inline-45">
        <div class="row">
            <!-- Sidebar-->
            <div class="sidebarR col-lg-3">
                <!--Price Sidebar-->
                <div class="price_sidebar rounded-lg box-shadow-sm __mb-n-10" id="shop-sidebar">
                    <div class="box-shadow-sm">

                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL">
                            <h3 class="widget-title btnF font-bold">
                                <a href="{{ route('orderList') }}" class="__color-1B7FED">{{\App\CPU\translate('My Orders')}}</a>
                            </h3>
                            <div class="divider-role __inline-41"></div>
                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a href="{{ route('wishList') }}">
                                    {{\App\CPU\translate('Wish List')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a href=""> {{\App\CPU\translate('Chat With Sellers')}} </a>
                            </h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a href="{{ route('profile') }}">
                                    {{\App\CPU\translate('Profile Info')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a href="">{{\App\CPU\translate('Address')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="{{ route('support-ticket') }}">{{\App\CPU\translate('Support ticket')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a href="">{{\App\CPU\translate('Transaction history')}} </a>
                            </h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-1 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a href="">{{\App\CPU\translate('Payment method')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                </div>
            </div>

            <section class="col-lg-9 mt-3">

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="row">
                                    <div class="col-md-12"><h5 class="modal-title font-nameA ">{{\App\CPU\translate('Add a new address')}}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <form class="">
                                    <div class="col-md-12">
                                        <!-- Nav pills -->
                                        <ul class="donate-now">
                                            <li>
                                                <input type="radio" id="a25" name="amount"/>
                                                <label for="a25">{{\App\CPU\translate('permanent')}}</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="a50" name="amount"/>
                                                <label for="a50">{{\App\CPU\translate('Home')}}</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="a75" name="amount" checked="checked"/>
                                                <label for="a75">{{\App\CPU\translate('Office')}}</label>
                                            </li>

                                        </ul>
                                    </div>
                                    <!-- Tab panes -->


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{\App\CPU\translate('Contact person name')}}</label>
                                            <input type="text" class="form-control" id="firstName" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lastName">{{\App\CPU\translate('Floor,Suite')}}</label>
                                            <input type="text" class="form-control" id="lastName" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{\App\CPU\translate('City')}}</label>
                                            <input type="text" class="form-control" id="firstName" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lastName">{{\App\CPU\translate('Zip code')}}</label>
                                            <input type="text" class="form-control" id="lastName" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{\App\CPU\translate('State')}}</label>
                                            <input type="text" class="form-control" id="firstName" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lastName">{{\App\CPU\translate('Country')}}</label>
                                            <input type="text" class="form-control" id="lastName" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="firstName">{{\App\CPU\translate('Phone')}}</label>
                                            <input type="text" class="form-control" id="firstName" placeholder="">
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">

                                        </div>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="closeB" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                                <button type="button" class="btn btn-p"> {{\App\CPU\translate('Update Information')}}</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="row __inline-45">

        <div class="col-md-6">
            <div class="card cardColor">
                <div class="card-header">

                    <i class="fa fa-thumb-tack fa-2x iconHad" aria-hidden="true"></i>
                    <span class="namHad">{{\App\CPU\translate('Permanent Address')}}</span>
                    <span class="float-right iconSp">
                            <i class="fa fa-edit fa-lg"></i>
                            <i class="fa fa-trash fa-lg"></i>
                        </span>
                </div>
                <div class="card-body">
                    <div class="font-name"><span> {{\App\CPU\translate('Abdur Rahim')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('3rd Floor,D block')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('Dhaka 1100')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('Bangladesh')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('0088 01251548524')}}</span></div>

                </div>
            </div>
        </div>

        <div class="col-md-6 div-secon">
            <div class="card cardColor">
                <div class="card-header ">
                    <i class="fa fa-home fa-2x iconHad" aria-hidden="true"></i>
                    <span class="namHad">{{\App\CPU\translate('Home Address')}}</span>
                    <span class="float-right iconSp">
                                <i class="fa fa-edit fa-lg"></i>
                                <i class="fa fa-trash fa-lg"></i>
                            </span>
                </div>
                <div class="card-body">
                    <div class="font-name"><span> {{\App\CPU\translate('Abdur Rahim')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('3rd Floor,D block')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('Dhaka 1100')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('Bangladesh')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('0088 01251548524')}}</span></div>

                </div>
            </div>
        </div>

    </div>
    <br>
    <div class="row __inline-45">
        <div class="col-md-6">
            <div class="card cardColor">
                <div class="card-header">
                    <i class="fa fa-briefcase fa-2x iconHad"></i>
                    <span class="namHad"> {{\App\CPU\translate('Office Address')}}</span>
                    <span class="float-right iconSp">
                            <i class="fa fa-edit fa-lg"></i>
                            <i class="fa fa-trash fa-lg"></i>
                        </span>
                </div>
                <div class="card-body">
                    <div class="font-name"><span> {{\App\CPU\translate('Abdur Rahim')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('3rd Floor,D block')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('Dhaka 1100')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('Bangladesh')}}</span></div>
                    <div><span class="font-nameA">{{\App\CPU\translate('0088 01251548524')}}</span></div>

                </div>
            </div>
        </div>

    </div>

    </section>


    </div>


    </div>
    </div>

@endsection

