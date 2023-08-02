@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Home'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Page Title-->
    <div class="container __inline-45">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 folot-left headerTitle">{{\App\CPU\translate('ADDRESSES')}}</h1>
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
                            <h3 class="widget-title btnF font-bold"><a href="{{ route('orderList') }}" class="__color-1B7FED">{{\App\CPU\translate('My Orders')}}</a>
                            </h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="{{ route('wishList') }}"> {{\App\CPU\translate('Wish List')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href=""> {{\App\CPU\translate('Chat With Sellers')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="{{ route('profile') }}"> {{\App\CPU\translate('Profile Info')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="">{{\App\CPU\translate('Address')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="{{ route('support-ticket') }}">{{\App\CPU\translate('Support ticket')}} </a>
                            </h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="">{{\App\CPU\translate('Transaction history')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-1 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="">{{\App\CPU\translate('Payment method')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                </div>
            </div>

            <section class="col-lg-9 mt-3">
                <span class="__color-6A6A6A">{{\App\CPU\translate('No address found')}}.</span>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="row">
                                    <div class="col-md-12"><h5
                                            class="modal-title font-nameA ">{{\App\CPU\translate('Add a new address')}}</h5>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-body">
                                <form class="">

                                    <!-- Nav pills -->
                                    <ul class="nav nav-pills ml-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active btn-p" data-toggle="pill"
                                               href="#home">{{\App\CPU\translate('Permanent')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="pill"
                                               href="#menu1">{{\App\CPU\translate('Home')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="pill"
                                               href="#menu2">{{\App\CPU\translate('Office')}}</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div id="home" class="container tab-pane active"><br>


                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label
                                                        for="firstName">{{\App\CPU\translate('Contact person name')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lastName">{{\App\CPU\translate('Floor,Suite')}}</label>
                                                    <input type="text" class="form-control" id="lastName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="firstName">{{\App\CPU\translate('City')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lastName">{{\App\CPU\translate('Zip code')}}</label>
                                                    <input type="text" class="form-control" id="lastName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="firstName">{{\App\CPU\translate('State')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lastName">{{\App\CPU\translate('Country')}}</label>
                                                    <input type="text" class="form-control" id="lastName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="firstName">{{\App\CPU\translate('Phone')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">

                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class=" closeB"
                                                    data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                                            <button type="button"
                                                    class="btn btn-p"> {{\App\CPU\translate('Update Information')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-p btn-b float-right" data-toggle="modal" data-target="#exampleModal">
                {{\App\CPU\translate('Add New Address')}}
            </button>
        </div>
    </div>

@endsection

