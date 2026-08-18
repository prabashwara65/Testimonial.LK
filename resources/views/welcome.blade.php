@extends('layouts.frontend')

@section('content')

    {{-- Top Banner --}}
    <div id="top-content" class="container-fluid">
        <img src="{{ asset('assets/images/slider.jpg') }}" alt="topBackground">
    </div>
    {{--/ Top Banner --}}

    <div class="features container-fluid" id="aboutUs">
        <div class="container">
            <div class="row rtl-row">
                <div class="col-sm-12">
                    <div class="feature-info" style="max-width: 1200px;">
                        <div class="feature-title">Get video testimonials from your customers with ease...</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="img-holder">
                        <img src="{{ asset('assets/web/images/feature2.png') }}" alt="">
                    </div>
                </div>
                <div class="col-sm-7 def-aligned">
                    <div class="feature-info">
                        <div class="feature-title">Our Story</div>
                        <div class="feature-text">We started in 2021 to provide the best technology to capture the testimonials with ease by providing value additions to businesses to support in their business journey.</div>
                        <div class="feature-text">We believe that excellent customer service and experience is the key to succeeding in every business. We partner with businesses of all sizes to build, improve, and scale products with disruptive technologies and combining design, engineering, and innovation to make our clients successful.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="feature-info">
                        <div class="feature-title">Our Vision</div>
                        <div class="feature-text">To make world-class video testimonials effortless and help companies build the trust they need to grow faster.</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="feature-info">
                        <div class="feature-title">Our Mission</div>
                        <div class="feature-text">To provide technological support to help businesses achieve market competitiveness, with enhanced customer experiences, and operational excellence.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="img-holder">
                        <img src="{{ asset('assets/web/images/feature3.png') }}" alt="">
                    </div>
                </div>
                <div class="col-sm-7 def-aligned">
                    <div class="feature-info">
                        <div class="feature-title">Benefit</div>
                        <div class="feature-text">
                            <ul>
                                <li>The all-in-one platform to power your customer testimonials</li>
                                <li>Capture Video Testimonials 100% Remotely.</li>
                                <li>Easily Create Stunning Video Testimonials</li>
                                <li>A dashboard to manage all testimonials</li>
                                <li>Understand how video testimonials are performing</li>
                                <li>Embed your testimonials on any platform</li>
                                <li>A dedicated landing page</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="row-title">To prove the value of what you have to offer, why not let your happy customers do the talking?</div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-photo-sc container-fluid pl-0 pr-0" id="howItWorks">
        <div class="w-100 blue-bg">
            <div class="row m-0">
                <div class="col-md-6 photo-holder photo1" style="height: 562.067px;"></div>
                <div class="col-md-6 text-holder text1">
                    <div class="text-box">
                        <h4>How Testimonial Works</h4>
                        <p>Testimonials provide potential customers with a positive view of your product or service. Putting a customer success story in a video testimonial will help your business stand out in competitive markets, boost sales, and strengthen customer relationships.</p>
                        <p>Regardless of the type of testimonial you like to use, letting people know exactly what benefits your product or service can offer them in the words of people who are (or were) just like them is a sure-fire way to convince your target market that you’re worth their time and money.</p>
                        <p>Customer testimonials are more effective than paid marketing copy as they take the spotlight away from the seller to shine it on the customers. Anyone looking at your testimonials page can see things from a like-minded point of view, as all of your current and previous clients were once potential customers, just like them.</p>
                        <p>The best testimonials are authentic. This is not a place for copywriters to fake it ‘till you make it… Your customer testimonials should be just that.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-photo-sc container-fluid darkblue-bg">
        <div class="row rtl-row">
            <div class="col-md-6 photo-holder photo2" style="height: 562.067px;"></div>
            <div class="col-md-6 text-holder text2 opposite">
                <div class="text-box">
                    <h4>Types of Testimonials</h4>
                    <p>You would think that testimonials are all quotes or written reviews. However, there are loads of different types of testimonials you can use in your marketing. From video testimonials to a Q&A with an influencer in your industry, there are loads of ways you can improve your brand perception without relying on one form of content alone.</p>
                    <p>Multi-channel review collection</p>
                    <p>Reach your customers wherever they are with any of our versatile testimonial collection methods. All feedback templates are pre-optimized for high response rates, whether you need embedded email surveys, web intercept or in-app. Showcase feedback for every customer touchpoint.</p>

                    <h4>Video Testimonials</h4>
                    <p>In a world where everyone is carrying around a smart phone with a high resolution camera in their pocket, it's no surprise that video has become one of the most popular ways to consume content.</p>
                    <p>Video testimonials have become one of the most persuasive marketing tools in recent years. Entertaining, visual, engaging, and hard to fake, videos can be a highly effective business tool. Due to the compact size, it’s easy to include a quote testimonial almost anywhere in your content without making it too distracting</p>

                    <h4>Audio Testimonials</h4>
                    <p>Audio recording from your customer explaining how they have been successful with your product or service is extremely compelling. Audio is a medium that easily expresses emotion because it creates a direct connection between the speaker and a listener.</p>

                    <h4>Image Testimonials</h4>

                    <h4>Quote Testimonials</h4>
                    <p>When you think of testimonials, you’re probably picturing the traditional quote type of testimonial. These short stories are powerful, credible, and highly effective. Particularly when you add the customer’s name, company, and image.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Us --}}
    <div class="contact-us container-fluid" id="contactUs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-xs-12 text-left">
                            <div class="row-title">Contact Us</div>
                        </div>
                    </div>

                    <div class="form-holder">
                        <form>
                            <div class="form-row">
                                <div class="col-xs-12 col-sm-6">
                                    <label for="inputName">Name</label>
                                    <input id="inputName" type="text" class="form-control" required>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <label for="inputEmail">Email</label>
                                    <input id="inputEmail" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xs-12">
                                    <label for="inputMessage1">Your message</label>
                                    <textarea id="inputMessage1" class="form-control" required></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-xs-12">
                                    <div class="g-recaptcha brochure__form__captcha" id="rcaptcha"  data-sitekey="6Lev9PsgAAAAAAUTiKX8B2O8zq-y3viIwkeMzrxE"></div>
                                    <span id="captcha" style="color:red"> </span> <!-- this will show captcha errors -->
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-xs-12">
                                    <div class="submit-holder">
                                        <button type="submit" class="hbtn hbtn-blue">Send</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-5">
                    <!-- Google Map -->
                    <div class="google-map">
                        <iframe class="contact-map-size"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.647430852111!2d79.84156891529392!3d6.932675420193364!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25925ab4dc561%3A0xeb35822a3ace0857!2sWorld%20Trade%20Center!5e0!3m2!1sen!2slk!4v1644916117637!5m2!1sen!2slk"
                                allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                    <!-- Google Map -->

                    <div class="social-details">
                        <p class="custom-text text-left">Follow Us On</p>
                        <ul class="social">
                            <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row mt-20 pt-20">
                <div class="col-md-4">
                    <a href="tel:+94779968924" class="d-flex align-items-center">
                        <span class="mr-10"><i class="fas fa-phone blue-color f22"></i></span>
                        <p class="m-0 custom-text text-left fw-500">+94 77 996 8924</p>
                    </a>
                </div>
                <div class="col-md-4 mt-xs-10">
                    <a href="mailto:info@testimonial.lk" target="_blank" class="d-flex align-items-center">
                        <span class="mr-10"><i class="fas fa-envelope blue-color f22"></i></span>
                        <p class="m-0 custom-text text-left fw-500">info@testimonial.lk</p>
                    </a>
                </div>
                <div class="col-md-4 mt-xs-10">
                    <a href="#" target="_blank" class="d-flex align-items-center">
                        <span class="mr-10"><i class="fas fa-map-marker-alt blue-color f22"></i></span>
                        <p class="m-0 custom-text text-left fw-500">42/4A, Jubillee Mawatha, Colombo 15</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{--/ Contact Us --}}
@endsection

{{--@if (Route::has('login'))--}}
{{--    <div class="top-right links">--}}
{{--        @auth--}}
{{--            <a href="{{ url('/home') }}">Home</a>--}}
{{--        @else--}}
{{--            <a href="{{ route('login') }}">Login</a>--}}

{{--            @if (Route::has('register'))--}}
{{--                <a href="{{ route('register') }}">Register</a>--}}
{{--            @endif--}}
{{--        @endauth--}}
{{--    </div>--}}
{{--@endif--}}
