<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <style>#loader{transition:all .3s ease-in-out;opacity:0;visibility:visible;position:fixed;height:100vh;width:100%;background:transparent;z-index:90000}#loader.fadeOut{opacity:0;visibility:hidden}.spinner{width:40px;height:40px;position:absolute;top:calc(50% - 20px);left:calc(50% - 20px);background-color:#333;border-radius:100%;-webkit-animation:sk-scaleout 1s infinite ease-in-out;animation:sk-scaleout 1s infinite ease-in-out}@-webkit-keyframes sk-scaleout{0%{-webkit-transform:scale(0)}100%{-webkit-transform:scale(1);opacity:0}}@keyframes sk-scaleout{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}</style>
    <link href="{{ asset('assets/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/custom/css/custom.css') }}" rel="stylesheet">
    {{-- theme color customize styles --}}
    <link href="{{ asset('assets/custom/css/theme.css') }}" rel="stylesheet">
    {{-- Noty.js Notification popup plugin --}}
    <link href="{{ asset('assets/libs/noty/noty.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/noty/metroui.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.8/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.2.0/main.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap@4.2.0/main.min.css" />

    {{-- Icon Moon --}}
    <link href="{{ asset('assets/custom/css/icomoon/styles.min.css') }}" rel="stylesheet">

    {{-- Custom Style --}}
    <link href="{{ asset('assets/custom/css/custom-style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/custom/css/custom-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/custom/css/colors.min.css') }}" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            setTimeout(() => {
                loader.classList.add('fadeOut');
            }, 300);
        });
    </script>
</head>
<body class="app">
<div id="loader">
    <div class="spinner"></div>
</div>
<div>
    <div class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-logo">
                <div class="peers ai-c fxw-nw">
                    <div class="peer peer-greed">
                        <a class="sidebar-link td-n" href="{{ route('home') }}">
                            <div class="peers ai-c fxw-nw justify-content-center">
                                <div class="peer">
                                    <div class="logo"><img src="{{ asset('assets/images/logo.png') }}" alt=""></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="peer">
                        <div class="mobile-toggle sidebar-toggle"><a href="" class="td-n"><i class="ti-arrow-circle-left"></i></a></div>
                    </div>
                </div>
            </div>
            <ul class="sidebar-menu scrollable pos-r">
                <li class="nav-item"><a class="sidebar-link {{ (Request::is('admin') || Request::is('vendor')) ? 'active' : '' }}" href="{{ Request::is('admin*') ? route('admin.dashboard') : route('vendor.dashboard')  }}"><span class="icon-holder"><i class="w-fade-text icon-grid6"></i> </span><span class="title">Dashboard</span></a></li>

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_USERS, \App\Http\Constants\Actions::VIEW_ROLES, \App\Http\Constants\Actions::VIEW_PERMISSIONS, \App\Http\Constants\Actions::VIEW_ACTION_LOG]))
                    <li class="nav-item dropdown {{ (Request::is('admin/users') || Request::is('admin/roles') || Request::is('admin/permissions') || Request::is('admin/action-log') || Request::is('vendor/admin/users') || Request::is('vendor/admin/roles') || Request::is('vendor/admin/action-log')) ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-users"></i> </span><span class="title">User Management</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_USERS))
                                <li class=""><a class="sidebar-link {{ (Request::is('admin/users*') || Request::is('vendor/admin/users*')) ? 'active' : '' }}" href="{{ Request::is('admin*') ? route('admin.users.index') : route('vendor.users.index')  }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Users</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_ROLES))
                                <li class=""><a class="sidebar-link {{ (Request::is('admin/roles*') || Request::is('vendor/admin/roles*')) ? 'active' : '' }}" href="{{ Request::is('admin*') ? route('admin.roles.index') : route('vendor.roles.index')  }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Roles</a></li>
                            @endif
                            @if(Auth::user()->hasRole('Super Admin'))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/permissions*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Permissions</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_ACTION_LOG))
                                <li class=""><a class="sidebar-link {{ (Request::is('admin/action-log*') || Request::is('vendor/admin/action-log*')) ? 'active' : '' }}" href="{{ Request::is('admin*') ? route('admin.action-log') : route('vendor.action-log')  }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Action Log</a></li>
                            @endif
                        </ul>
                    </li>
                @endif



                {{-- Admin Menu Area Start --}}
                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_VENDORS, \App\Http\Constants\Actions::VIEW_VENDOR_COMPANIES, \App\Http\Constants\Actions::VIEW_PAYMENT_RENEWALS]))
                    <li class="nav-item dropdown {{ Request::is('admin/vendors') || Request::is('admin/vendor-companies') || Request::is('admin/payment-renewals*') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-briefcase3"></i> </span><span class="title">Vendors Management</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_VENDOR_COMPANIES))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/vendor-companies*') ? 'active' : '' }}" href="{{ route('admin.vendor-companies.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Vendor Companies</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_VENDORS))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/vendors*') ? 'active' : '' }}" href="{{ route('admin.vendors.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Vendor Users</a></li>
                            @endif
                            @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_PAYMENT_RENEWALS]))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/payment-renewals*') ? 'active' : '' }}" href="{{ route('admin.payment-renewals.paid') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Payment Renewals</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK]))
                    <li class="nav-item dropdown {{ Request::is('admin/testimonials*') || Request::is('admin/feedbacks*') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-bubble-lines3"></i> </span><span class="title">Testimonial and Feedback</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/testimonials*') ? 'active' : '' }}" href="{{ route('admin.testimonials.approved') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Vendor Wise Testimonials</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_VENDOR_WISE_TESTIMONIAL_FEEDBACK))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/feedbacks*') ? 'active' : '' }}" href="{{ route('admin.feedbacks.approved') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Vendor Wise Feedbacks</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_TOTAL_SUMMARY_REPORT, \App\Http\Constants\Actions::VIEW_PRODUCT_REPORT]))
                    <li class="nav-item dropdown {{ Request::is('admin/total-summary-report') || Request::is('admin/product-report') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-copy"></i> </span><span class="title">Reports</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_TOTAL_SUMMARY_REPORT))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/total-summary-report*') ? 'active' : '' }}" href="{{ route('admin.total-summary-report') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Total Summary Report</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_PRODUCT_REPORT))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/product-report*') ? 'active' : '' }}" href="{{ route('admin.product-report') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Product Report</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_REGIONS, \App\Http\Constants\Actions::VIEW_COUNTRIES, \App\Http\Constants\Actions::VIEW_PROVINCES, \App\Http\Constants\Actions::VIEW_DISTRICTS]))
                    <li class="nav-item dropdown {{ Request::is('admin/regions') || Request::is('admin/countries') || Request::is('admin/provinces') || Request::is('admin/districts') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-earth"></i> </span><span class="title">Regions</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_REGIONS))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/regions*') ? 'active' : '' }}" href="{{ route('admin.regions.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Regions</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_COUNTRIES))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/countries*') ? 'active' : '' }}" href="{{ route('admin.countries.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Countries</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_PROVINCES))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/provinces*') ? 'active' : '' }}" href="{{ route('admin.provinces.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Provinces</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_DISTRICTS))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/districts*') ? 'active' : '' }}" href="{{ route('admin.districts.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Districts</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- Admin Menu Area End --}}



                {{-- Vendor Menu Area Start --}}
                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_COMPANY, \App\Http\Constants\Actions::VIEW_BRANCHES]))
                    <li class="nav-item dropdown {{ Request::is('vendor/admin/company') || Request::is('vendor/admin/branches') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-office"></i> </span><span class="title">Company Management</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_COMPANY))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/company*') ? 'active' : '' }}" href="{{ route('vendor.company.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Company Details</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_BRANCHES))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/branches*') ? 'active' : '' }}" href="{{ route('vendor.branches.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Branch Details</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_PRODUCTS, \App\Http\Constants\Actions::VIEW_SUBPRODUCTS]))
                    <li class="nav-item dropdown {{ Request::is('vendor/admin/products') || Request::is('vendor/admin/subproducts') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-package"></i> </span><span class="title">Product</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_PRODUCTS))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/products*') ? 'active' : '' }}" href="{{ route('vendor.products.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Products</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_SUBPRODUCTS))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/subproducts*') ? 'active' : '' }}" href="{{ route('vendor.subproducts.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Sub Products</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_TARGETS, \App\Http\Constants\Actions::VIEW_CAMPAIGNS]))
                    <li class="nav-item dropdown {{ Request::is('vendor/admin/targets') || Request::is('vendor/admin/campaigns') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-target2"></i> </span><span class="title">Campaign and Target</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_TARGETS))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/targets*') ? 'active' : '' }}" href="{{ route('vendor.targets.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Targets</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_CAMPAIGNS))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/campaigns*') ? 'active' : '' }}" href="{{ route('vendor.campaigns.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Campaigns</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_QUESTIONNAIRES]))
                    <li class="nav-item"><a class="sidebar-link {{ Request::is('vendor/admin/questionnaires') ? 'active' : '' }}" href="{{ route('vendor.questionnaires.index') }}"><span class="icon-holder"><i class="w-fade-text icon-question4"></i> </span><span class="title">Questionnaires</span></a></li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_TESTIMONIALS]))
                    <li class="nav-item dropdown {{ Request::is('vendor/admin/testimonials*') || Request::is('vendor/admin/feedbacks*') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-bubble-lines3"></i> </span><span class="title">Testimonial and Feedback</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_TESTIMONIALS))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/testimonials*') ? 'active' : '' }}" href="{{ route('vendor.testimonials.approved') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Testimonials</a></li>
                            @endif
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_TESTIMONIALS))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/feedbacks*') ? 'active' : '' }}" href="{{ route('vendor.feedbacks.approved') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Feedbacks</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_CUSTOMERS, \App\Http\Constants\Actions::VIEW_REWARDS]))
                    <li class="nav-item dropdown {{ Request::is('admin/customers*') || Request::is('vendor/admin/customers*') || Request::is('vendor/admin/rewards*') ? 'open' : '' }}">
                        <a class="dropdown-toggle" href="javascript:void(0);"><span class="icon-holder"><i class="w-fade-text icon-vcard"></i> </span><span class="title">Customer Management</span> <span class="arrow"><i class="ti-angle-right"></i></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_CUSTOMERS))
                                <li class=""><a class="sidebar-link {{ Request::is('admin/customers*') || Request::is('vendor/admin/customers*') ? 'active' : '' }}" href="{{ Request::is('admin*') ? route('admin.customers.index') : route('vendor.customers.index')  }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Customers</a></li>
                            @endif
                            @if(Request::is('vendor*') && Auth::user()->hasPermissionTo(\App\Http\Constants\Actions::VIEW_REWARDS))
                                <li class=""><a class="sidebar-link {{ Request::is('vendor/admin/rewards*') ? 'active' : '' }}" href="{{ route('vendor.rewards.index') }}"><span class="icon-holder"><i class="w-fade-text icon-meter-slow"></i> </span> Rewards</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_INCENTIVEPAYMENTS]))
                    <li class="nav-item"><a class="sidebar-link {{ Request::is('vendor/admin/incentives*') ? 'active' : '' }}" href="{{ route('vendor.incentives.paid') }}"><span class="icon-holder"><i class="w-fade-text icon-coins"></i> </span><span class="title">Incentive</span></a></li>
                @endif
                {{-- Vendor Menu Area End --}}

                @if(Auth::user()->hasAnyPermission([\App\Http\Constants\Actions::VIEW_SETTINGS]))
                    <li class="nav-item"><a class="sidebar-link {{ Request::is('admin/settings') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}"><span class="icon-holder"><i class="w-fade-text icon-cog"></i> </span><span class="title">Settings</span></a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="page-container">
        <div class="header navbar">
            <div class="header-container">
                <ul class="nav-left">
                    <li><a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);"><i class="ti-menu"></i></a></li>
                </ul>
                <ul class="nav-right">
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
{{--                            <div class="peer mR-10"><img class="w-2r bdrs-50p" src="{{ asset('assets/images/profile_pictures/default.jpg') }}" alt="" ></div>--}}
                            <div class="peer"><span class="loggedAccountName">{{Auth::user()->name . " " . Auth::user()->last_name}} <i class="fa fa-chevron-down" style="font-size: 8px"></i></span></div>
                        </a>
                        <ul class="dropdown-menu">
                            {{--<li ><a href="" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-settings mR-10"></i> <span>Setting</span></a></li>
                            <li><a href="" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-user mR-10"></i> <span>Profile</span></a></li>
                            <li><a href="email.html" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-email mR-10"></i> <span>Messages</span></a></li>
                            <li role="separator" class="divider"></li>--}}
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-power-off mR-10"></i>
                                    <span>
                                        {{ __('Logout') }}
                                    </span>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <main class="main-content bgc-grey-100">
            <div id="mainContent">
                @if(Session::has('flash_message'))
                    <div id="flash-message-container" data-type="info">
                        {!! session('flash_message') !!}
                    </div>
                @elseif(Session::has('flash_success_message'))
                    <div id="flash-message-container" data-type="success">
                        {!! session('flash_success_message') !!}
                    </div>
                @elseif(Session::has('flash_error_message'))
                    <div id="flash-message-container" data-type="error">
                        {!! session('flash_error_message') !!}
                    </div>
                @elseif(Session::has('flash_warning_message'))
                    <div id="flash-message-container" data-type="warning">
                        {!! session('flash_warning_message') !!}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
        <footer class="bdT ta-c p-20 lh-0 fsz-sm c-grey-600"><span>Copyright © {{date('Y')}} developed by <a href="#" target="_blank" title="ABC Technology">ABC Technology</a>. All rights reserved.</span></footer>
    </div>
</div>

<div class="modal fade modal-holder" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal fade xl-modal-holder" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="permission-denied-message-container" style="display: none;">
    <p class="text-center">
        <img src="{{ asset('assets/static/images/cancel.svg') }}" style="width: 50px; text-align: center" alt="">
    <h4 class="text-center">Permission Denied!</h4>
    </p>
</div>

@if(isset($getData))
    <span class="get-data-holder" data-url="{{route($getData['url'])}}" data-holder="{{$getData['holder']}}"></span>
@endif

<script src="{{ asset('assets/vendor.js') }}"></script>
<script src="{{ asset('assets/bundle.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('assets/libs/noty/noty.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.8/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

@if(Request::is('location-schedule'))
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.2.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@4.2.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap@4.2.0/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@fullcalendar/moment@4.2.0/main.min.js"></script>
@endif

<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
        integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
        crossorigin=""></script>
<script src="{{ asset('assets/custom/js/custom.js') }}"></script>
<script src="{{ asset('assets/custom/js/switchery.min.js') }}"></script>

@if(isset($scripts))
    @foreach($scripts as $script)
        <script src="{{ asset('assets/custom/js/'.$script) }}"></script>
    @endforeach
@endif

@yield('script')

</body>
</html>
