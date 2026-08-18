<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/web/css/custom-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom/css/icomoon/styles.min.css') }}">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.8/dist/css/bootstrap-select.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <div id="header-holder">
        <nav id="nav" class="navbar navbar-full">
            <div class="container-fluid">
                <div class=" container-nav">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="navbar-header">
                                <button aria-expanded="false" type="button" class="navbar-toggle collapsed"
                                    data-toggle="collapse" data-target="#bs">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand homeLink" href="{{ url('/') }}"><img
                                        src="{{ asset('assets/web/images/logo.jpg') }}" alt="logo"></a>
                            </div>
                            <div style="height: 1px;" role="main" aria-expanded="false"
                                class="navbar-collapse collapse navbar-collapse-centered" id="bs">
                                <ul class="nav navbar-nav navbar-nav-centered">
                                    <li class="nav-item"><a class="nav-link homeLink"
                                            href="{{ url('/') }}">Home</a></li>

                                    <li class="nav-item">
                                        <a class="nav-link aboutUsLink" href="">About Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link howItWorksLink" href="">How it works</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link contactUsLink" href="">Contact us</a>
                                    </li>
                                    <li class="nav-item {{ Request::is('customer-response*') ? 'active' : '' }}">
                                        <a class="nav-link"
                                            href="{{ route('customer-response.step-one') }}">Testimonial & Feedback</a>
                                    </li>
                                    @if (Auth::check())
                                        <li class="nav-item {{ Request::is('history') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('history') }}">My Testimonials &
                                                Feedback</a>
                                        </li>
                                    @endif
                                </ul>
                                <ul class="nav navbar-nav navbar-right other-navbar navbar-nav-centered">
                                    @if (Auth::check())
                                        <li class="nav-item dropdown">
                                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <span class="mr-10"><i class="fas fa-user-circle f24"></i></span>
                                                <span><i class="fas fa-caret-down"></i></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('profile') }}">My
                                                        Profile</a></li>
                                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                                        onclick="event.preventDefault();
                                                                        document.getElementById('logout-form').submit();">
                                                        {{ __('Logout') }}
                                                    </a>

                                                    <form id="logout-form" action="{{ route('logout') }}"
                                                        method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </li>
                                            </ul>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link btn-client-area" href="{{ route('login') }}">Login</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn-client-area signup-btn"
                                                href="{{ route('register') }}">SignUp</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    @yield('content')

    <div class="footer container-fluid">
        <a class="btn-go-top" href="#"><i class="hstb hstb-down-arrow"></i></a>
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                    <div class="footer-menu">
                        <a href="#">
                            <img class="w-140px" src="{{ asset('assets/web/images/logo.jpg') }}" alt="logo">
                        </a>
                        {{--                            <p class="mt-10 mb-0 f14 fw-400 footer-color">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p> --}}
                    </div>
                </div>

                <div class="col-xs-6 col-sm-3 col-md-4 col-lg-3">
                    <div class="footer-menu">
                        <h4>Links</h4>
                        <ul>
                            <li><a href="" class="aboutUsLink">About us</a></li>
                            <li><a href="" class="howItWorksLink">How It Works</a></li>
                            <li><a href="" class="contactUsLink">Contact Us</a></li>
                            <li><a href="#" data-toggle="modal"
                                    data-target="#loginForTestimonialModal">Testimonial & Feedback</a></li>
                            <li><a href="{{ url('/sales-rep-dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ url('/my-testimonials') }}">My Testimonials & Feedback</a></li>
                            <li><a href="{{ url('/testimonial-feedback-collection') }}">Testimonial & Feedback
                                    Collection</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                    <div class="footer-menu custom-footer-menu">
                        <h4>Contact us</h4>
                        <ul>
                            <li><a href="tel:+94779968924"> <span class="mr-5"><i class="fas fa-phone"></i></span>
                                    +94 77 996 8924</a></li>
                            <li><a href="mailto:info@testimonial.lk" target="_blank"> <span class="mr-5"><i
                                            class="fas fa-envelope"></i></span> info@testimonial.lk</a></li>
                            <li><a href="#" target="_blank"> <span class="mr-5"><i
                                            class="fas fa-map-marker-alt"></i></span> 42/4A, Jubillee Mawatha, Colombo
                                    15</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                    <div class="footer-menu custom-footer-menu">
                        <h4>Follow us</h4>
                        <ul class="social">
                            <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>\
                            <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sub-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-footer-menu">
                            <ul>
                                <li><a href="#">Sitemap</a></li>
                                <li><a href="#">Terms of Service</a></li>
                                <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="copyright">© Copyright 2022 ABC Technologies, All Rights Reserved</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Confirm Upload Files Modal --}}
    <div class="modal fade" id="loginForTestimonialModal" tabindex="-1" role="dialog"
        aria-labelledby="loginForTestimonialModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 pt-20 pb-20">
                            <p class="m-0 f16 fw-400 text-color">You need to login first if you want to send
                                testimonial or feedback.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="hbtn hbtn-prev" data-dismiss="modal">Close</a>
                    <a href="{{ url('/create-testimonials') }}" type="button" class="hbtn hbtn-blue">Ok</a>
                </div>
            </div>
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

    @if (isset($getData))
        <span class="get-data-holder" data-url="{{ route($getData['url']) }}"
            data-holder="{{ $getData['holder'] }}"></span>
    @endif

    <script src="{{ asset('assets/web/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/chart.js') }}"></script>
    <script src="{{ asset('assets/web/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/dataTables.bootstrap.min.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldr1KodAAAAAJc1Oa2aNFOpLca-UQzhdohudVgf"></script>
    <script src="{{ asset('assets/web/js/main.js') }}"></script>
    <script src="{{ asset('assets/web/js/custom.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('assets/libs/noty/noty.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.8/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ asset('assets/custom/js/custom.js') }}"></script>
    <script src="{{ asset('assets/custom/js/testimonial.js') }}"></script>

    <script>
        // updates a dependant dropdown based on the value selected in the parent drop down
        // use "data-target" and "data-url" attributes to define the data retrieve path and which dependant drop down to update
        // To update another dependant drop down at the same time use
        // "data-second-url" and "data-second-target" to define values as above
        $(document).on('change', '.load-data-on-change', function() {
            // remove placeholder option
            $(this).children('option:not([value])').remove();

            var url = $(this).data('url');
            if (url != null || url != undefined) {
                var selected_id = $(this).val();
                var target = $(this).data('target');
                loadDropdownOnParentChange(url, selected_id, target);
            }

            if (typeof $(this).data('second-url') !== 'undefined') {
                var secondUrl = $(this).data('second-url');
                if (secondUrl != null || secondUrl != undefined) {
                    var selected_id = $(this).val();
                    var target = $(this).data('second-target');
                    loadDropdownOnParentChange(secondUrl, selected_id, target);
                }
            }

            if (typeof $(this).data('third-url') !== 'undefined') {
                var secondUrl = $(this).data('third-url');
                if (secondUrl != null || secondUrl != undefined) {
                    var selected_id = $(this).val();
                    var target = $(this).data('third-target');
                    loadDropdownOnParentChange(secondUrl, selected_id, target);
                }
            }
        });

        function loadDropdownOnParentChange(url, selected_id, target) {
            $("#loader").removeClass("fadeOut");

            var data = {
                'selected_id': selected_id
            };
            var target = target; // $(this).data('target');
            var post = ajax(url, data, 'post');
            post.done(function(response) {
                $("#loader").addClass("fadeOut");

                if (response.status == 'success') {
                    if (response.notifyType == 'value') {
                        $(target).val(response.data);
                    } else {
                        $(target).html(response.data);
                        $('select').selectpicker('refresh');
                    }
                } else {
                    alert(response.message);
                }
            });
        }

        function ajax(url, data, method, hasFileUpload) {
            var processData = true;
            var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
            if (hasFileUpload !== undefined) {
                processData = false;
                contentType = false;
            }

            return $.ajax({
                'dataType': 'json',
                'type': method,
                'url': url,
                'data': data,
                'processData': processData,
                'contentType': contentType,
                beforeSend: function(xhr, type) {
                    if (!type.crossDomain) {
                        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                    }
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.homeLink').on('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            $('.aboutUsLink').on('click', function() {
                $(this).prop('href', '{{ url('/#aboutUs') }}');
                $('html, body').animate({
                    scrollTop: $("#aboutUs").offset().top
                }, 500);
            });

            $('.howItWorksLink').on('click', function() {
                $(this).attr('href', '{{ url('/#howItWorks') }}');
                $('html, body').animate({
                    scrollTop: $("#howItWorks").offset().top
                }, 500);
            });


            $('.contactUsLink').on('click', function() {
                $(this).attr('href', '{{ url('/#contactUs') }}');
                $('html, body').animate({
                    scrollTop: $("#contactUs").offset().top
                }, 500);
            });
        });
    </script>

    <script></script>

    @yield('script')
</body>

</html>
