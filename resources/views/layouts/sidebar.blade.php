<!-- ========== Left Sidebar Start ========== -->
@php
    if(LOGIN_USER_TYPE=='company'){
        $user = Auth::guard('company')->user();
        $company_user = true;
        $first_segment = 'company';
    }
    else{
        $user = Auth::guard('admin')->user();
        $company_user = false;
        $first_segment = 'admin';
    }
@endphp
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">@lang('translation.Menu')</li>

                <li class="{{ (Route::current()->uri() == 'admin/dashboard') ? 'active' : ''  }}">
                    <a href="{{ url($first_segment.'/dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">@lang('translation.Dashboards')</span>
                    </a>
                </li>

                <li class="{{ (Route::current()->uri() == 'admin/dashboard') ? 'active' : ''  }}">
                    <a href="{{ url($first_segment.'/files') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">File Manager</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-ecommerce">User Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if(@$user->can('view_rider'))
                        <li class="{{ (Route::current()->uri() == 'admin/rider') ? 'active' : ''  }}"><a href="{{ url('admin/rider') }}">Riders</a></li>
                        @endif
                        @if($company_user || @$user->can('view_driver'))
                        <li class="{{ (Route::current()->uri() == $first_segment.'/driver') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/driver') }}">Drivers</a></li>
                        @endif
                        <li><a href="#">Partners</a></li>
                        <li><a href="#">Staff</a></li>
                        <li><a href="#">Admin</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-bus"></i>
                        <span key="t-ecommerce">Vehicle Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if(@$user->can('manage_vehicle_type'))
                        <li><a href="{{ url('admin/vehicle_type') }}" key="t-products">Vehicle Type</a></li>
					    @endif
                        @if($company_user || @$user->can('manage_vehicle'))
                        <li><a href="{{ url($first_segment.'/vehicle') }}" key="t-orders">Vehicles</a></li>
					    @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-contact"></i>
                        <span key="t-ecommerce">Communications</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if(@$user->can('manage_email_settings') || @$user->can('manage_send_email'))
                        @if(@$user->can('manage_email_settings'))
                        <li><a href="{{ url('admin/email_settings') }}" key="t-products">Email Settings</a></li>
                        @endif
                        @if(@$user->can('manage_send_email'))
                        <li><a href="{{ url('admin/send_email') }}" key="t-products">Send Email</a></li>
                        @endif
                        @endif
                        @if($company_user || @$user->can('manage_send_message'))
                        <li><a href="{{ url($first_segment.'/send_message') }}" key="t-orders">Messaging</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-adjust'></i>
                        <span key="t-ecommerce">Marketing</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products" key="t-products">Promos</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-lock"></i>
                        <span key="t-ecommerce">Bookings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products" key="t-products">Reservations</a></li>
                        @if($company_user || @$user->can('manage_trips'))
                        <li><a href="{{ url($first_segment.'/trips') }}" key="t-products">Trips</a></li>
					    @endif
                        @if($company_user || @$user->can('manage_map') || @$user->can('manage_heat_map'))
                        {{-- <li class="{{ (Route::current()->uri() == $first_segment.'/map') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/map') }}"><i class="fa fa-circle-o"></i><span>Map View</span></a></li> --}}
                        <li><a href="{{ url($first_segment.'/map') }}" key="t-products">Show Maps</a></li>
                        <li><a href="{{ url($first_segment.'/heat-map') }}" key="t-products">Show Maps</a></li>
                        {{-- <li class="{{ (Route::current()->uri() == $first_segment.'/heat-map') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/heat-map') }}"><i class="fa fa-circle-o"></i><span>HeatMap</span></a></li> --}}
			            @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-eject"></i>
                        <span key="t-ecommerce">Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products" key="t-products">Site Settings</a></li>
                        <li><a href="ecommerce-products" key="t-products">App Settings</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-apps">@lang('translation.Apps')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fab fa-accusoft"></i>
                        <span key="t-ecommerce">Front Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li><a href="javascript: void(0);" class="has-arrow waves-effect">
                            <span key="t-ecommerce">Pages</span>
                        </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ url('admin/banner') }}" key="t-products">Banner</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
{{-- added links  --}}
                @if(@$user->can('view_vehicle_make'))
                    <li class="{{ (Route::current()->uri() == 'admin/vehicle_make' || Route::current()->uri() == 'admin/add-vehicle-make' || Route::current()->uri() == 'admin/edit-vehicle-make/{id}') ? 'active' : ''  }}">
                        <a href="{{ url('admin/vehicle_make') }}"><i class="fas fa-anchor"></i><span>Vehicle Make</span></a></li>
                @endif
                @if(@$user->can('view_vehicle_model'))
                    <li class="{{ (Route::current()->uri() == 'admin/view_vehicle_model' || Route::current()->uri() == 'admin/add-vehicle-make' || Route::current()->uri() == 'admin/edit-vehicle-make/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/vehicle_model') }}"><i class="fa fa fa-car"></i><span>Vehicle Model</span></a></li>
                @endif

                @if(@$user->can('view_manage_reason'))
                <li class="{{ (Route::current()->uri() == 'admin/cancel-reason') ? 'active' : ''  }}"><a href="{{ url('admin/cancel-reason') }}"><i class="fas fa-archway"></i><span>Manage Cancel Reason</span></a></li>
                @endif
    
                @if(@$user->can('manage_locations'))
                <li class="{{ (Route::current()->uri() == 'admin/locations') ? 'active' : ''  }}"><a href="{{ url('admin/locations') }}"><i class="fas fa-arrows-alt"></i><span>Manage Locations</span></a></li>
                @endif
    
                @if(@$user->can('manage_peak_based_fare'))
                <li class="{{ (Route::current()->uri() == 'admin/manage_fare') ? 'active' : ''  }}"><a href="{{ url('admin/manage_fare') }}"><i class="fab fa-affiliatetheme"></i><span>Manage Fare</span></a></li>
                @endif
    


                @if($company_user || @$user->can('manage_requests') || @$user->can('manage_trips') || @$user->can('manage_cancel_trips') || @$user->can('manage_payments') || @$user->can('manage_rating'))
                <li class="treeview {{ (Route::current()->uri() == $first_segment.'/request' || Route::current()->uri() == $first_segment.'/trips' || Route::current()->uri() == $first_segment.'/cancel_trips' || Route::current()->uri() == $first_segment.'/payments' || Route::current()->uri() == $first_segment.'/rating') ? 'active' : ''  }}">
                    <a href="#">
                        <i class="fab fa-app-store-ios"></i>
                        <span> Manage Trips </span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($company_user || @$user->can('manage_requests'))
                        <li class="{{ (Route::current()->uri() == $first_segment.'/request') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/request') }}"><i class="fa fa-paper-plane-o"></i><span>Manage Ride Requests</span></a></li>
                        @endif

                        @if($company_user || @$user->can('manage_trips'))
                        <li class="{{ (Route::current()->uri() == $first_segment.'/trips') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/trips') }}"><i class="fa fa-taxi"></i><span> Manage Trips</span></a></li>
                        @endif

                        @if($company_user || @$user->can('manage_cancel_trips'))
                        <li class="{{ (Route::current()->uri() == $first_segment.'/cancel_trips') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/cancel_trips') }}"><i class="fa fa-chain-broken"></i><span>Manage Canceled Trips</span></a></li>
                        @endif
                        
                        @if($company_user || @$user->can('manage_payments'))
                        <li class="{{ (Route::current()->uri() == $first_segment.'/payments') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/payments') }}"><i class="fa fa-usd"></i><span>Manage Payments</span></a></li>
                        @endif
                        
                        @if($company_user || @$user->can('manage_rating'))
                        <li class="{{ (Route::current()->uri() == $first_segment.'/rating') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/rating') }}"><i class="fa fa-star"></i><span>Ratings</span></a></li>
                        @endif



                    </ul>
                </li>
                @endif

                @if($company_user || @$user->can('manage_driver_payments') || @$user->can('manage_company_payments'))
                <li class="treeview {{ (Route::current()->uri() == 'admin/payout/overall' || Route::current()->uri() == 'admin/payout/company/overall' || Route::current()->uri() == 'company/payout/overall') ? 'active' : ''  }}">
                    <a href="#">
                        <i class="fab fa-airbnb"></i> <span>Manage Payouts</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($company_user || @$user->can('manage_driver_payments'))
                        <li class="{{ (Route::current()->uri() == $first_segment.'/payout/overall') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/payout/overall') }}"><i class="fa fa-circle-o"></i><span>Driver Payouts</span></a></li>
                        @endif
                    </ul>
                </li>
                @endif

                @if($company_user ||  @$user->can('manage_statements'))
			<li class="treeview {{ (Route::current()->uri() == $first_segment.'/statements/{type}') ? 'active' : ''  }}">
				<a href="#">
					<i class="fab fa-artstation"></i><span>Manage Statements</span> <i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class="{{ (Route::current()->uri() == $first_segment.'/statements/overall') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/statements/overall') }}"><i class="fa fa-circle-o"></i><span>Overall Ride Statments</span></a></li>
					<li class="{{ (Route::current()->uri() == $first_segment.'/statements/driver') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/statements/driver') }}"><i class="fa fa-circle-o"></i><span>Driver Statement</span></a></li>
				</ul>
			</li>
			@endif

            @if(@$user->can('manage_wallet') || @$user->can('manage_promo_code'))
			<li class="treeview {{ (Route::current()->uri() == 'admin/wallet/{user_type}' || Route::current()->uri() == 'admin/promo_code') ? 'active' : ''  }}">
				<a href="#">
                    <i class="fas fa-award"></i> <span>Manage Wallet & Promo</span> <i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if($company_user || @$user->can('manage_wallet'))
					<li class="treeview {{ (@$navigation == 'manage_wallet') ? 'active' : ''  }}">
						<a href="{{ route('wallet',['user_type' => 'Rider']) }}"><i class="fa fa-circle-o"></i>
							<span> Manage Wallet Amount </span>
						</a>
					</li>
					@endif
					@if(@$user->can('manage_promo_code'))
					<li class="{{ (Route::current()->uri() == 'admin/promo_code') ? 'active' : ''  }}"><a href="{{ url('admin/promo_code') }}"><i class="fa fa-circle-o"></i><span>Manage Promo Code</span></a></li>
					@endif
				</ul>
			</li>
			@endif

            @if(@$user->can('manage_rider_referrals') || @$user->can('manage_driver_referrals'))
			<li class="treeview {{ (Route::current()->uri() == 'admin/referrals/rider' || Route::current()->uri() == 'admin/referrals/driver') ? 'active' : ''  }}">
				<a href="#">
					<i class="fa fa-users"></i>
					<span>Referrals</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(@$user->can('manage_rider_referrals'))
					<li class="{{ (Route::current()->uri() == 'admin/referrals/rider') ? 'active' : ''  }}">
						<a href="{{ url('admin/referrals/rider') }}"><i class="fa fa-circle-o"></i>
							<span> Riders </span>
						</a>
					</li>
					@endif
					@if(@$user->can('manage_driver_referrals'))
					<li class="{{ (Route::current()->uri() == 'admin/referrals/driver') ? 'active' : ''  }}">
						<a href="{{ url('admin/referrals/driver') }}"><i class="fa fa-circle-o"></i>
							<span> Drivers </span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			@endif

            @if($company_user || @$user->can('manage_map') || @$user->can('manage_heat_map'))
			<li class="treeview {{ (Route::current()->uri() == $first_segment.'/map' || Route::current()->uri() == $first_segment.'/heat-map') ? 'active' : ''  }}">
				<a href="#">
					<i class="fa fa-map-marker" aria-hidden="true"></i> <span>Manage Map</span> <i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class="{{ (Route::current()->uri() == $first_segment.'/map') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/map') }}"><i class="fa fa-circle-o"></i><span>Map View</span></a></li>
					<li class="{{ (Route::current()->uri() == $first_segment.'/heat-map') ? 'active' : ''  }}"><a href="{{ url($first_segment.'/heat-map') }}"><i class="fa fa-circle-o"></i><span>HeatMap</span></a></li>
				</ul>
			</li>
			@endif

            @if(@$user->can('manage_api_credentials'))
			<li class="{{ (Route::current()->uri() == 'admin/api_credentials') ? 'active' : ''  }}"><a href="{{ url('admin/api_credentials') }}"><i class="fas fa-bahai"></i><span>Api Credentials</span></a></li>
			@endif
			@if(@$user->can('manage_payment_gateway'))
			<li class="{{ (Route::current()->uri() == 'admin/payment_gateway') ? 'active' : ''  }}"><a href="{{ url('admin/payment_gateway') }}"><i class="fab fa-bandcamp"></i><span>Payment Gateway</span></a></li>
			@endif

            @if(@$user->can('manage_additional_tax') || @$user->can('manage_fees'))
            <li class="treeview {{ (Route::current()->uri() == 'admin/additional_tax' || Route::current()->uri() == 'admin/fees') ? 'active' : ''  }}">
                <a href="#">
                    <i class="fas fa-band-aid"></i>
                    <span>Manage Fees</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @if(@$user->can('manage_fees'))
                        <li class="{{ (Route::current()->uri() == 'admin/fees') ? 'active' : ''  }}">
                            <a href="{{ url('admin/fees') }}"><i class="fa fa-circle-o"></i>
                                <span>Fees Management</span>
                            </a>
                        </li>
                    @endif
                    @if(@$user->can('manage_additional_tax'))
                        <li class="{{ (Route::current()->uri() == 'admin/additional_tax') ? 'active' : ''  }}">
                            <a href="{{ url('admin/additional_tax') }}"><i class="fa fa-circle-o"></i>
                                <span>Additional Tax Management</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(@$user->can('manage_referral_settings'))
			<li class="{{ (Route::current()->uri() == 'admin/referral_settings') ? 'active' : ''  }}"><a href="{{ url('admin/referral_settings') }}"><i class="fab fa-battle-net"></i><span>Manage Referral Settings</span></a></li>
			@endif
			@if(@$user->can('manage_metas'))
			<li class="{{ (Route::current()->uri() == 'admin/metas') ? 'active' : ''  }}"><a href="{{ url('admin/metas') }}"><i class="fas fa-biohazard"></i><span>Manage Metas</span></a></li>
			@endif
			@if(@$user->can('manage_country'))
			<li class="{{ (Route::current()->uri() == 'admin/country') ? 'active' : ''  }}"><a href="{{ url('admin/country') }}"><i class="fa fa-globe"></i><span>Manage Country</span></a></li>
			@endif
			@if(@$user->can('manage_currency'))
			<li class="{{ (Route::current()->uri() == 'admin/currency') ? 'active' : ''  }}"><a href="{{ url('admin/currency') }}"><i class="fab fa-blackberry"></i><span>Manage Currency</span></a></li>
			@endif
			@if(@$user->can('manage_language'))
			<li class="{{ (Route::current()->uri() == 'admin/language') ? 'active' : ''  }}"><a href="{{ url('admin/language') }}"><i class="fa fa-language"></i><span>Manage Language</span></a></li>
			@endif
			@if(@$user->can('manage_static_pages'))
			<li class="{{ (Route::current()->uri() == 'admin/pages') ? 'active' : ''  }}"><a href="{{ url('admin/pages') }}"><i class="fab fa-bity"></i><span>Manage Static Pages</span></a></li>
			@endif


            @if(@$user->can('manage_help'))
			<li class="treeview {{ (Route::current()->uri() == 'admin/help' || Route::current()->uri() == 'admin/help_category' || Route::current()->uri() == 'admin/help_subcategory') ? 'active' : ''  }}">
				<a href="#">
					<i class="fas fa-brain"></i> <span>Manage Help</span> <i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class="{{ (Route::current()->uri() == 'admin/help') ? 'active' : ''  }}"><a href="{{ url('admin/help') }}"><i class="fa fa-circle-o"></i><span>Help</span></a></li>
					<li class="{{ (Route::current()->uri() == 'admin/help_category') ? 'active' : ''  }}"><a href="{{ url('admin/help_category') }}"><i class="fa fa-circle-o"></i><span>Category</span></a></li>
					<li class="{{ (Route::current()->uri() == 'admin/help_subcategory') ? 'active' : ''  }}"><a href="{{ url('admin/help_subcategory') }}"><i class="fa fa-circle-o"></i><span>Subcategory</span></a></li>
				</ul>
			</li>
			@endif
			@if(@$user->can('manage_join_us'))
			<li class="{{ (Route::current()->uri() == 'admin/join_us') ? 'active' : ''  }}"><a href="{{ url('admin/join_us') }}"><i class="fa fa-share-alt"></i><span>Join Us Links</span></a></li>
			@endif
			@if(@$user->can('manage_site_settings'))
			<li class="{{ (Route::current()->uri() == 'admin/site_setting') ? 'active' : ''  }}"><a href="{{ url('admin/site_setting') }}"><i class="fa fa-cogs"></i><span>Site Setting</span></a></li>
			@endif



{{-- added pages end --}}

{{-- calender start  --}}
                {{-- <li>
                    <a href="calendar" class="waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span key="t-calendar">@lang('translation.Calendar')</span>
                    </a>
                </li>

                <li>
                    <a href="chat" class="waves-effect">
                        <i class="bx bx-chat"></i>
                        <span key="t-chat">@lang('translation.Chat')</span>
                    </a>
                </li>

                <li>
                    <a href="apps-filemanager" class="waves-effect">
                        <i class="bx bx-file"></i>
                        <span class="badge rounded-pill bg-success float-end"
                            key="t-new">@lang('translation.New')</span>
                        <span key="t-file-manager">@lang('translation.File_Manager')</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-store"></i>
                        <span key="t-ecommerce">@lang('translation.Ecommerce')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products" key="t-products">@lang('translation.Products')</a></li>
                        <li><a href="ecommerce-product-detail"
                                key="t-product-detail">@lang('translation.Product_Detail')</a></li>
                        <li><a href="ecommerce-orders" key="t-orders">@lang('translation.Orders')</a></li>
                        <li><a href="ecommerce-customers" key="t-customers">@lang('translation.Customers')</a></li>
                        <li><a href="ecommerce-cart" key="t-cart">@lang('translation.Cart')</a></li>
                        <li><a href="ecommerce-checkout" key="t-checkout">@lang('translation.Checkout')</a></li>
                        <li><a href="ecommerce-shops" key="t-shops">@lang('translation.Shops')</a></li>
                        <li><a href="ecommerce-add-product" key="t-add-product">@lang('translation.Add_Product')</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-bitcoin"></i>
                        <span key="t-crypto">@lang('translation.Crypto')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="crypto-wallet" key="t-wallet">@lang('translation.Wallet')</a></li>
                        <li><a href="crypto-buy-sell" key="t-buy">@lang('translation.Buy_Sell')</a></li>
                        <li><a href="crypto-exchange" key="t-exchange">@lang('translation.Exchange')</a></li>
                        <li><a href="crypto-lending" key="t-lending">@lang('translation.Lending')</a></li>
                        <li><a href="crypto-orders" key="t-orders">@lang('translation.Orders')</a></li>
                        <li><a href="crypto-kyc-application" key="t-kyc">@lang('translation.KYC_Application')</a></li>
                        <li><a href="crypto-ico-landing" key="t-ico">@lang('translation.ICO_Landing')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-envelope"></i>
                        <span key="t-email">@lang('translation.Email')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="email-inbox" key="t-inbox">@lang('translation.Inbox')</a></li>
                        <li><a href="email-read" key="t-read-email">@lang('translation.Read_Email')</a></li>
                        <li>
                            <a href="javascript: void(0);">
                                <span class="badge rounded-pill badge-soft-success float-end"
                                    key="t-new">@lang('translation.New')</span>
                                <span key="t-email-templates">@lang('translation.Templates')</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="email-template-basic"
                                        key="t-basic-action">@lang('translation.Basic_Action')</a></li>
                                <li><a href="email-template-alert"
                                        key="t-alert-email">@lang('translation.Alert_Email')</a></li>
                                <li><a href="email-template-billing"
                                        key="t-bill-email">@lang('translation.Billing_Email')</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-receipt"></i>
                        <span key="t-invoices">@lang('translation.Invoices')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="invoices-list" key="t-invoice-list">@lang('translation.Invoice_List')</a></li>
                        <li><a href="invoices-detail" key="t-invoice-detail">@lang('translation.Invoice_Detail')</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-briefcase-alt-2"></i>
                        <span key="t-projects">@lang('translation.Projects')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="projects-grid" key="t-p-grid">@lang('translation.Projects_Grid')</a></li>
                        <li><a href="projects-list" key="t-p-list">@lang('translation.Projects_List')</a></li>
                        <li><a href="projects-overview" key="t-p-overview">@lang('translation.Project_Overview')</a>
                        </li>
                        <li><a href="projects-create" key="t-create-new">@lang('translation.Create_New')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">@lang('translation.Tasks')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tasks-list" key="t-task-list">@lang('translation.Task_List')</a></li>
                        <li><a href="tasks-kanban" key="t-kanban-board">@lang('translation.Kanban_Board')</a></li>
                        <li><a href="tasks-create" key="t-create-task">@lang('translation.Create_Task')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-contacts">@lang('translation.Contacts')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="contacts-grid" key="t-user-grid">@lang('translation.User_Grid')</a></li>
                        <li><a href="contacts-list" key="t-user-list">@lang('translation.User_List')</a></li>
                        <li><a href="contacts-profile" key="t-profile">@lang('translation.Profile')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <span class="badge rounded-pill bg-success float-end"
                            key="t-new">@lang('translation.New')</span>
                        <i class="bx bx-detail"></i>
                        <span key="t-blog">@lang('translation.Blog')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="blog-list" key="t-blog-list">@lang('translation.Blog_List')</a></li>
                        <li><a href="blog-grid" key="t-blog-grid">@lang('translation.Blog_Grid')</a></li>
                        <li><a href="blog-details" key="t-blog-details">@lang('translation.Blog_Details')</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-pages">@lang('translation.Pages')</li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <span class="badge rounded-pill bg-success float-end"
                            key="t-new">@lang('translation.New')</span>
                        <i class="bx bx-user-circle"></i>
                        <span key="t-authentication">@lang('translation.Authentication')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="auth-login" key="t-login">@lang('translation.Login')</a></li>
                        <li><a href="auth-login-2" key="t-login-2">@lang('translation.Login') 2</a></li>
                        <li><a href="auth-register" key="t-register">@lang('translation.Register')</a></li>
                        <li><a href="auth-register-2" key="t-register-2">@lang('translation.Register') 2</a></li>
                        <li><a href="auth-recoverpw" key="t-recover-password">@lang('translation.Recover_Password')</a>
                        </li>
                        <li><a href="auth-recoverpw-2" key="t-recover-password-2">@lang('translation.Recover_Password')
                                2</a></li>
                        <li><a href="auth-lock-screen" key="t-lock-screen">@lang('translation.Lock_Screen')</a></li>
                        <li><a href="auth-lock-screen-2" key="t-lock-screen-2">@lang('translation.Lock_Screen') 2</a>
                        </li>
                        <li><a href="auth-confirm-mail" key="t-confirm-mail">@lang('translation.Confirm_Mail')</a></li>
                        <li><a href="auth-confirm-mail-2" key="t-confirm-mail-2">@lang('translation.Confirm_Mail') 2</a>
                        </li>
                        <li><a href="auth-email-verification"
                                key="t-email-verification">@lang('translation.Email_verification')</a></li>
                        <li><a href="auth-email-verification-2"
                                key="t-email-verification-2">@lang('translation.Email_verification') 2</a></li>
                        <li><a href="auth-two-step-verification"
                                key="t-two-step-verification">@lang('translation.Two_step_verification')</a></li>
                        <li><a href="auth-two-step-verification-2"
                                key="t-two-step-verification-2">@lang('translation.Two_step_verification') 2</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-utility">@lang('translation.Utility')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-starter" key="t-starter-page">@lang('translation.Starter_Page')</a></li>
                        <li><a href="pages-maintenance" key="t-maintenance">@lang('translation.Maintenance')</a></li>
                        <li><a href="pages-comingsoon" key="t-coming-soon">@lang('translation.Coming_Soon')</a></li>
                        <li><a href="pages-timeline" key="t-timeline">@lang('translation.Timeline')</a></li>
                        <li><a href="pages-faqs" key="t-faqs">@lang('translation.FAQs')</a></li>
                        <li><a href="pages-pricing" key="t-pricing">@lang('translation.Pricing')</a></li>
                        <li><a href="pages-404" key="t-error-404">@lang('translation.Error_404')</a></li>
                        <li><a href="pages-500" key="t-error-500">@lang('translation.Error_500')</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-components">@lang('translation.Components')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-tone"></i>
                        <span key="t-ui-elements">@lang('translation.UI_Elements')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ui-alerts" key="t-alerts">@lang('translation.Alerts')</a></li>
                        <li><a href="ui-buttons" key="t-buttons">@lang('translation.Buttons')</a></li>
                        <li><a href="ui-cards" key="t-cards">@lang('translation.Cards')</a></li>
                        <li><a href="ui-carousel" key="t-carousel">@lang('translation.Carousel')</a></li>
                        <li><a href="ui-dropdowns" key="t-dropdowns">@lang('translation.Dropdowns')</a></li>
                        <li><a href="ui-grid" key="t-grid">@lang('translation.Grid')</a></li>
                        <li><a href="ui-images" key="t-images">@lang('translation.Images')</a></li>
                        <li><a href="ui-lightbox" key="t-lightbox">@lang('translation.Lightbox')</a></li>
                        <li><a href="ui-modals" key="t-modals">@lang('translation.Modals')</a></li>
                        <li><a href="ui-rangeslider" key="t-range-slider">@lang('translation.Range_Slider')</a></li>
                        <li><a href="ui-session-timeout"
                                key="t-session-timeout">@lang('translation.Session_Timeout')</a></li>
                        <li><a href="ui-progressbars" key="t-progress-bars">@lang('translation.Progress_Bars')</a></li>
                        <li><a href="ui-sweet-alert" key="t-sweet-alert">@lang('translation.Sweet_Alert')</a></li>
                        <li><a href="ui-tabs-accordions"
                                key="t-tabs-accordions">@lang('translation.Tabs_&_Accordions')</a></li>
                        <li><a href="ui-typography" key="t-typography">@lang('translation.Typography')</a></li>
                        <li><a href="ui-video" key="t-video">@lang('translation.Video')</a></li>
                        <li><a href="ui-general" key="t-general">@lang('translation.General')</a></li>
                        <li><a href="ui-colors" key="t-colors">@lang('translation.Colors')</a></li>
                        <li><a href="ui-rating" key="t-rating">@lang('translation.Rating')</a></li>
                        <li><a href="ui-notifications" key="t-notifications">@lang('translation.Notifications')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="bx bxs-eraser"></i>
                        <span class="badge rounded-pill bg-danger float-end">10</span>
                        <span key="t-forms">@lang('translation.Forms')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="form-elements" key="t-form-elements">@lang('translation.Form_Elements')</a></li>
                        <li><a href="form-layouts" key="t-form-layouts">@lang('translation.Form_Layouts')</a></li>
                        <li><a href="form-validation" key="t-form-validation">@lang('translation.Form_Validation')</a>
                        </li>
                        <li><a href="form-advanced" key="t-form-advanced">@lang('translation.Form_Advanced')</a></li>
                        <li><a href="form-editors" key="t-form-editors">@lang('translation.Form_Editors')</a></li>
                        <li><a href="form-uploads" key="t-form-upload">@lang('translation.Form_File_Upload')</a></li>
                        <li><a href="form-xeditable" key="t-form-xeditable">@lang('translation.Form_Xeditable')</a></li>
                        <li><a href="form-repeater" key="t-form-repeater">@lang('translation.Form_Repeater')</a></li>
                        <li><a href="form-wizard" key="t-form-wizard">@lang('translation.Form_Wizard')</a></li>
                        <li><a href="form-mask" key="t-form-mask">@lang('translation.Form_Mask')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-tables">@lang('translation.Tables')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tables-basic" key="t-basic-tables">@lang('translation.Basic_Tables')</a></li>
                        <li><a href="tables-datatable" key="t-data-tables">@lang('translation.Data_Tables')</a></li>
                        <li><a href="tables-responsive"
                                key="t-responsive-table">@lang('translation.Responsive_Table')</a></li>
                        <li><a href="tables-editable" key="t-editable-table">@lang('translation.Editable_Table')</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-bar-chart-alt-2"></i>
                        <span key="t-charts">@lang('translation.Charts')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="charts-apex" key="t-apex-charts">@lang('translation.Apex_Charts')</a></li>
                        <li><a href="charts-echart" key="t-e-charts">@lang('translation.E_Charts')</a></li>
                        <li><a href="charts-chartjs" key="t-chartjs-charts">@lang('translation.Chartjs_Charts')</a></li>
                        <li><a href="charts-flot" key="t-flot-charts">@lang('translation.Flot_Charts')</a></li>
                        <li><a href="charts-tui" key="t-ui-charts">@lang('translation.Toast_UI_Charts')</a></li>
                        <li><a href="charts-knob" key="t-knob-charts">@lang('translation.Jquery_Knob_Charts')</a></li>
                        <li><a href="charts-sparkline"
                                key="t-sparkline-charts">@lang('translation.Sparkline_Charts')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-aperture"></i>
                        <span key="t-icons">@lang('translation.Icons')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="icons-boxicons" key="t-boxicons">@lang('translation.Boxicons')</a></li>
                        <li><a href="icons-materialdesign"
                                key="t-material-design">@lang('translation.Material_Design')</a></li>
                        <li><a href="icons-dripicons" key="t-dripicons">@lang('translation.Dripicons')</a></li>
                        <li><a href="icons-fontawesome" key="t-font-awesome">@lang('translation.Font_awesome')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-map"></i>
                        <span key="t-maps">@lang('translation.Maps')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="maps-google" key="t-g-maps">@lang('translation.Google_Maps')</a></li>
                        <li><a href="maps-vector" key="t-v-maps">@lang('translation.Vector_Maps')</a></li>
                        <li><a href="maps-leaflet" key="t-l-maps">@lang('translation.Leaflet_Maps')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-share-alt"></i>
                        <span key="t-multi-level">@lang('translation.Multi_Level')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="javascript: void(0);" key="t-level-1-1">@lang('translation.Level_1.1')</a></li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"
                                key="t-level-1-2">@lang('translation.Level_1.2')</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="javascript: void(0);" key="t-level-2-1">@lang('translation.Level_2.1')</a>
                                </li>
                                <li><a href="javascript: void(0);" key="t-level-2-2">@lang('translation.Level_2.2')</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
