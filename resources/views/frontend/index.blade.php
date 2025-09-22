<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from themephi.net/template/aidoodleh/aidoodle/# by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 Jan 2025 09:04:01 GMT -->

<head>
    <meta charset="UTF-8">
    <meta name="description" content="html template">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="Asad">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ai agent</title>


    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/backtotop.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">
    <style>
        /* ---------- Container safety on small screens ---------- */
        .custom-container-1 {
            width: 100%;
            margin-inline: auto;
            padding-inline: 16px;
        }

        @media (min-width:576px) {
            .custom-container-1 {
                padding-inline: 20px
            }
        }

        @media (min-width:768px) {
            .custom-container-1 {
                max-width: 720px
            }
        }

        @media (min-width:992px) {
            .custom-container-1 {
                max-width: 960px
            }
        }

        @media (min-width:1200px) {
            .custom-container-1 {
                max-width: 1140px
            }
        }

        @media (min-width:1400px) {
            .custom-container-1 {
                max-width: 1320px
            }
        }

        /* ---------- Header ---------- */
        .header-main .row {
            row-gap: 12px
        }

        .header-logo img {
            max-height: 40px;
            height: auto;
            display: block
        }

        @media (min-width:576px) {
            .header-logo img {
                max-height: 46px
            }
        }

        /* Hide center nav < lg even if theme CSS fights it */
        @media (max-width:991.98px) {
            .h5_header-menu {
                display: none !important
            }
        }

        /* Keep desktop Join AI visible ≥576px (matches your d-none d-sm-flex) */
        .header-action.d-none.d-sm-flex {
            display: none !important
        }

        @media (min-width:576px) {
            .header-action.d-none.d-sm-flex {
                display: flex !important
            }
        }

        /* ---------- Mobile drawer (uses your .sidebar-info/.offcanvas-overlay) ---------- */
        .sidebar-info.side-info {
            position: fixed;
            top: 0;
            right: -100%;
            width: 88%;
            max-width: 360px;
            height: 100vh;
            background: #fff;
            z-index: 1040;
            transition: right .25s ease;
            padding: 18px 16px;
            box-shadow: -8px 0 24px rgba(0, 0, 0, .08);
            overflow-y: auto;
        }

        .sidebar-info.side-info.open {
            right: 0
        }

        .offcanvas-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            opacity: 0;
            visibility: hidden;
            transition: .2s ease;
            z-index: 1030;
        }

        .offcanvas-overlay.show {
            opacity: 1;
            visibility: visible
        }

        /* Mobile menu vertical layout */
        .sidebar-info .mobile-menu>ul {
            list-style: none;
            margin: 0;
            padding: 0
        }

        .sidebar-info .mobile-menu li {
            position: relative
        }

        .sidebar-info .mobile-menu>ul>li>a {
            display: block;
            padding: 12px 6px;
            color: #1f2937;
            text-decoration: none;
        }

        .sidebar-info .mobile-menu .menu-has-child>a {
            font-weight: 600
        }

        .sidebar-info .mobile-menu .submenu {
            list-style: none;
            margin: 0 0 8px 0;
            padding-left: 12px;
            border-left: 2px solid #eef2f7;
        }

        .sidebar-info .mobile-menu .submenu li a {
            display: block;
            padding: 10px 6px;
            color: #4b5563
        }

        /* little open/close indicator */
        .sidebar-info .mobile-menu .menu-has-child.is-open>a::after {
            content: "–";
            float: right;
            font-weight: 700
        }

        .sidebar-info .mobile-menu .menu-has-child:not(.is-open)>a::after {
            content: "+";
            float: right;
            font-weight: 700
        }

        /* Mobile drawer CTA */
        .sidebar-info .mobile-cta {
            margin-top: 12px;
            padding-top: 0px;
            border-top: 1px solid #eef2f7
        }

        .sidebar-info .mobile-cta .header-action-btn {
            display: block;
            padding: 0px 16px;
            border-radius: 999px;
            background: #ea5a2b;
            color: #fff;
            text-align: center;
            text-decoration: none;
        }

        /* ---------- Banner fixes (prevent overlap on tiny screens) ---------- */
        .banner-area {
            padding-block: 24px
        }

        .banner-single {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            align-items: center
        }

        .banner-content-title {
            font-size: clamp(28px, 5vw, 52px);
            line-height: 1.15;
            margin: 8px 0 12px
        }

        .banner-content p {
            margin: 0 0 16px;
            font-size: clamp(14px, 2.8vw, 18px)
        }

        .banner-content-btn {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap
        }

        @media (min-width:992px) {
            .banner-area {
                padding-block: 72px
            }

            .banner-single {
                grid-template-columns: 1.1fr .9fr;
                gap: 40px
            }
        }

        /* ---------- Feature strip responsive grid ---------- */
        .feature-top {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px
        }

        @media (min-width:768px) {
            .feature-top {
                grid-template-columns: repeat(3, 1fr);
                gap: 24px
            }
        }

        .feature-item {
            padding: 18px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            height: 100%
        }

        /* pricing & testimonials small spacing safety */
        .price-switch-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            text-align: center;
            margin-bottom: 24px
        }

        .wrapper-full .row {
            row-gap: 24px
        }

        .testimonial-active,
        .testimonial-active-2 {
            overflow: hidden
        }

        /* Footer spacing on mobile */
        .footer-top .row {
            row-gap: 20px
        }

        .footer-bottom .row {
            row-gap: 12px
        }

        .footer-bottom-menu ul {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            justify-content: center
        }
    </style>
    <style>
        /* 1) Absolutely prevent horizontal overflow everywhere */
        html,
        body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        #smooth-wrapper,
        #smooth-content,
        .body-wrapper {
            overflow-x: clip;
        }

        /* better than hidden for transforms */

        /* 2) Any animated/slider sections that could overflow during translate() */
        .banner-area,
        .choose-area,
        .feature-area,
        .price-area,
        .testimonial-area,
        .footer-area {
            overflow-x: clip;
        }

        /* 3) Swiper + rolling wrappers sometimes add a few px */
        .swiper,
        .swiper-wrapper,
        .roll-slider {
            overflow: hidden;
        }

        /* 4) Images/SVGs can overflow if parent uses transforms */
        img,
        svg,
        video {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* 5) Bootstrap row gutters create negative margins; make sure the container clips them */
        .container,
        .custom-container-1 {
            overflow-x: clip;
        }

        /* 6) Back-to-top button & sticky header shouldn’t create width beyond viewport */
        .progress-wrap {
            right: 20px;
            bottom: 20px;
        }

        .header-main {
            left: 0;
            right: 0;
            width: 100%;
        }

        /* 7) Safety for any 100vw usages in vendor CSS (treat as full width without scrollbar width) */
        [class*="vw"],
        [style*="100vw"] {
            max-width: 100%;
        }
    </style>
    <link rel="icon" href="{{ asset('assets/img/ai agent favicon.svg') }}" type="image/x-icon">


    <script src="{{ asset('chat/script/main.js?api_key=XiEErW4upbO4CPXDfBuY') }}" defer></script>

    <meta property="og:title" content="Ai agent" />
    <meta property="og:description"
        content="Ai agents are highly scalable and efficient, making them ideal for businesses looking to enhance customer service, automate workflows, and improve operational efficiency. Additionally, they are available 24/7, ensuring that tasks are performed consistently and without human errors." />
    <meta property="og:image"
        content="https://mosabbirrahman.github.io/ai-agent//assets/img/http://www.w3.org/2000/svg" />
    <meta property="og:url" content="https://mosabbirrahman.github.io/ai-agent/" />
    <meta property="og:type" content="article" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:site_name" content="Ai-agent" />

</head>

<body>



    <!-- back to top start -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- back to top end -->

    <!-- sidebar-information-area-start -->
    <div class="sidebar-info side-info">
        <div class="sidebar-logo-wrapper mb-25">
            <div class="row align-items-center">
                <div class="col-xl-6 col-8">
                    <div class="sidebar-logo">
                        <a href="#"><img src="http://ai-agent.test/frontend/assets/img/ai agent logo.svg" alt="Image Not Found"></a>
                    </div>
                </div>
                <div class="col-xl-6 col-4">
                    <div class="sidebar-close-wrapper text-end">
                        <button class="sidebar-close side-info-close"><i class="fal fa-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-menu-wrapper fix">
            <div class="mobile-menu"></div>
        </div>
    </div>
    <div class="offcanvas-overlay"></div>
    <!-- sidebar-information-area-end -->



    <div class="has-smooth"></div>

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <div class="body-wrapper">

                <header class="header-area">

                    <div class="header-main header-sticky ">
                        <div class="container custom-container-1">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-2 col-lg-2 col-6 ">
                                    <div class="header-logo">
                                        <a href="#"><img
                                                src="{{ asset('frontend/assets/img/ai agent logo.svg') }}"
                                                alt="Image Not Found"></a>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-6 d-none d-lg-block text-center">
                                    <div class="h5_header-menu ">
                                        <nav class="h5_header-nav-menu" id="mobile-menu">
                                            <ul>
                                                <li class="menu-has-child">
                                                    <a href="#">Home</a>

                                                </li>
                                                <!-- <li><a href="about.html">About</a></li> -->
                                                <li class="menu-has-child">
                                                    <a href="#">Pages</a>
                                                    <ul class="submenu">

                                                        <li><a href="checkout.html">Checkout</a></li>
                                                        <li><a href="login.html">Login</a></li>
                                                        <!-- <li><a href="404.html">404</a></li> -->
                                                    </ul>
                                                </li>

                                                <li><a href="contact.html">Contact</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>


                                <div class="col-xl-3 col-lg-3 col-6">
                                    <div class="header-action-wrap d-flex align-items-center  justify-content-end ">
                                        <div class="header-action d-none d-sm-flex ">

                                            <a href="/login" class="header-action-btn">
                                                Join AI
                                            </a>
                                        </div>
                                        <div class="header-menu-bar d-lg-none ml-10">
                                            <span class="header-menu-bar-icon side-toggle">
                                                <i class="fa-light fa-bars"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main>
                    <!-- banner area start -->
                    <section class="banner-area">
                        <div class="container custom-container-1">
                            <div class="banner-single">
                                <div class="banner-content">
                                    <span class="banner-content-subtitle tp_fade_left">Today special offer</span>
                                    <h1 class="banner-content-title tp_has_text_reveal_anim">Best Ai-agent for Customer
                                        Service</h1>
                                    <p class="tp_desc_anim">An all-in-one platform to build and launch chatbots
                                        conversational <br> without coding.Powered by advanced AI, it enables businesses
                                        to automate customer interactions, enhance user engagement, and provide
                                        real-time support across multiple channels.</p>
                                    <div class="banner-content-btn">
                                        <a href="#" class="theme-btn tp_fade_bottom">Get Started For Free</a>
                                        <span class="tp_fade_bottom">
                                            <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.4555 0.0244961C16.2201 0.206689 15.2018 0.523653 14.0862 1.07023C12.6337 1.78153 11.3259 2.7424 10.0056 4.06517C8.07885 5.9969 6.49652 8.40034 5.42334 11.0359C5.33349 11.258 5.25612 11.4427 5.25113 11.4477C5.24863 11.4502 5.22118 11.3304 5.19123 11.1781C4.88425 9.64324 3.79359 8.26307 2.56067 7.84877C2.20128 7.72898 1.8369 7.70152 1.50745 7.77141C1.27784 7.81882 0.920944 8.00102 0.736256 8.16574C0.481686 8.38786 0.204654 8.80716 0.0524114 9.1965L0 9.32378L0.292007 9.34125C0.983338 9.37869 1.43258 9.58584 1.8943 10.0725C2.14388 10.3346 2.32357 10.6041 2.53322 11.0259C2.75534 11.4727 2.89511 11.8395 3.26448 12.9327C3.62887 14.0084 3.77362 14.3877 3.9658 14.7721C4.21288 15.2662 4.52485 15.683 4.80438 15.8952L4.94415 16L5.08391 15.9052C5.30853 15.7504 5.78273 15.2612 6.0348 14.9243C6.53645 14.2455 6.90084 13.6065 7.8717 11.6898C8.97733 9.50348 9.39912 8.71731 10.0231 7.68405C12.0621 4.30227 14.4106 1.90632 17.3157 0.24163C17.5279 0.121832 17.7076 0.0170088 17.7126 0.00952148C17.73 -0.00794792 17.6377 -0.000461578 17.4555 0.0244961Z"
                                                    fill="currentColor" />
                                            </svg>
                                            No credit card Required
                                        </span>
                                    </div>
                                </div>
                                <div class="banner-img tp_fade_left">
                                    <img src="{{ asset('frontend/assets/images/banner/home1/bg.png') }}"
                                        alt="Image Not Found">
                                </div>
                            </div>
                        </div>

                    </section>
                    <!-- banner area end -->

                    <!-- choose area start -->
                    <section class="choose-area pt-140">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="section-area text-center mb-50">
                                        <span class="section-subtitle tp_fade_left">Why Choose Us</span>
                                        <h2 class="section-title tp_title_slideup mb-0">Why Choose Ai-agent </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-4 col-md-6 tp_fade_left" data-fade-from="left">
                                    <div class="choose-item mb-30">
                                        <span class="choose-item-count">01</span>
                                        <div class="choose-item-img">
                                            <img src="{{ asset('frontend/assets/images/choose/1.png') }}"
                                                alt="Image Not Found">
                                        </div>
                                        <div class="choose-item-content">
                                            <h5 class="choose-item-content-title"><a href="#">Fast response</a>
                                            </h5>
                                            <p>Our AI-powered platform ensures lightning-fast responses, allowing
                                                chatbots to instantly understand queries and provide accurate answers in
                                                real time. </p>
                                            <a href="#" class="choose-item-content-btn">Learn More<i
                                                    class="fa-light fa-angle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 tp_fade_left" data-fade-from="left"
                                    data-delay=".8">
                                    <div class="choose-item mb-30">
                                        <span class="choose-item-count">02</span>
                                        <div class="choose-item-img">
                                            <img src="{{ asset('frontend/assets/images/choose/2.png') }}"
                                                alt="Image Not Found">
                                        </div>
                                        <div class="choose-item-content">
                                            <h5 class="choose-item-content-title"><a href="#">customize
                                                    widget</a></h5>
                                            <p>Easily design and integrate customized widgets to match your brand,
                                                enhance user experience, and provide seamless chatbot interactions.</p>
                                            <a href="#" class="choose-item-content-btn">Learn More<i
                                                    class="fa-light fa-angle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 tp_fade_left" data-fade-from="left"
                                    data-delay="1.1">
                                    <div class="choose-item mb-30">
                                        <span class="choose-item-count">03</span>
                                        <div class="choose-item-img">
                                            <img src="{{ asset('frontend/assets/images/choose/3.png') }}"
                                                alt="Image Not Found">
                                        </div>
                                        <div class="choose-item-content">
                                            <h5 class="choose-item-content-title"><a href="#">All
                                                    Language...</a></h5>
                                            <p>Support for multiple languages enables seamless communication, allowing
                                                chatbots to engage users worldwide in their preferred language.</p>
                                            <a href="#" class="choose-item-content-btn">Learn More<i
                                                    class="fa-light fa-angle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 tp_fade_left" data-fade-from="left"
                                    data-delay="1.3">
                                    <div class="choose-item mb-30">
                                        <span class="choose-item-count">04</span>
                                        <div class="choose-item-img">
                                            <img src="{{ asset('frontend/assets/images/choose/4.png') }}"
                                                alt="Image Not Found">
                                        </div>
                                        <div class="choose-item-content">
                                            <h5 class="choose-item-content-title"><a href="#">24/7 Hours
                                                    Support</a></h5>
                                            <p>Ensure uninterrupted assistance with 24/7 AI-powered support, providing
                                                instant responses and seamless user interactions anytime, anywhere.</p>
                                            <a href="#" class="choose-item-content-btn">Learn More<i
                                                    class="fa-light fa-angle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- choose area end -->

                    <!-- feature area start -->
                    <section class="feature-area pt-110 pb-110">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="section-area text-center mb-50">
                                        <span class="section-subtitle tp_fade_left">Our Features</span>
                                        <h2 class="section-title tp_title_slideup mb-0">What’s Included: Our Core
                                            Features</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-top mb-50 tp_fade_bottom">
                                <div class="feature-item feature-item-1">
                                    <h5 class="feature-item-title">Easy training</h5>
                                    <p>Train your chatbot effortlessly with a user-friendly interface, enabling quick
                                        learning and adaptation without any coding.</p>
                                </div>
                                <div class="feature-item feature-item-2">
                                    <h5 class="feature-item-title">Single page chating</h5>
                                    <p>Enjoy seamless conversations with a single-page chatting interface, ensuring
                                        fast, uninterrupted, and user-friendly interactions.</p>
                                </div>
                                <div class="feature-item feature-item-3">
                                    <h5 class="feature-item-title">In-enhance result</h5>
                                    <p>Get instant, in-hand results with real-time processing, ensuring fast and
                                        accurate responses for a seamless user experience</p>
                                </div>
                            </div>

                        </div>
                    </section>
                    <!-- feature area end -->

                    <!-- apps area start -->

                    <!-- apps area end -->

                    <!-- price area start -->
                    <section class="price-area price-tab pt-140 pb-140">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-xxl-6 col-xl-7 col-lg-9 col-md-10">
                                    <div class="section-area text-center mb-45">
                                        <span class="section-subtitle tp_fade_left">Pricing Plan</span>
                                        <h2
                                            class="section-title tp_title_slideup mb-0 section-title tp_title_slideup-big">
                                            Personalized Pricing Plans
                                            Suited to You.</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="price-switch-wrapper">
                                        <label class="toggler toggler--is-active" id="filt-monthly">Pay
                                            Monthly</label>
                                        <div class="toggle">
                                            <input type="checkbox" id="switcher" class="tp-check">
                                            <b class="switch"></b>
                                        </div>
                                        <label class="toggler yearly-pack" id="filt-yearly">Pay Yearly<span
                                                class="amount-offer">Save 34%</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="monthly" class="wrapper-full">
                                <div class="row align-items-end">
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left">
                                        <div class="price-item price-item-1">
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Free Trial</h5>
                                                <h2 class="price-item-amount-title">Free</h2>
                                                <p>No Need For Credit Card</p>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li class="not-abatable">Generate 1,000 AI Words / month</li>
                                                <li class="not-abatable">Google Docs style editor</li>
                                                <li class="not-abatable">Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left"
                                        data-delay=".8">
                                        <div class="price-item active">
                                            <div class="price-item-option">
                                                <span>Best Choice</span>
                                            </div>
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Standard Plan</h5>
                                                <div class="price-item-amount"><del>$85</del>
                                                    <h2 class="price-item-amount-title">$35<span>.8</span></h2><span
                                                        class="amount-offer">49.2%</span>
                                                </div>
                                                <p>/Month (annually billed)</p>
                                                <span class="price-item-offer">SAVE UP TO $54.2</span>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li>Generate 1,000 AI Words / month</li>
                                                <li class="not-abatable">Google Docs style editor</li>
                                                <li class="not-abatable">Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left"
                                        data-delay="1.1">
                                        <div class="price-item">
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Liter Plan</h5>
                                                <div class="price-item-amount"><del>$89</del>
                                                    <h2 class="price-item-amount-title">$56<span>.8</span></h2><span
                                                        class="amount-offer">30%</span>
                                                </div>
                                                <p>/Month (annually billed)</p>
                                                <span class="price-item-offer">SAVE UP TO $54.2</span>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li>Generate 1,000 AI Words / month</li>
                                                <li>Google Docs style editor</li>
                                                <li class="not-abatable">Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left"
                                        data-delay="1.4">
                                        <div class="price-item">
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Premium Plan</h5>
                                                <div class="price-item-amount"><del>$99</del>
                                                    <h2 class="price-item-amount-title">$89<span>.8</span></h2><span
                                                        class="amount-offer">30%</span>
                                                </div>
                                                <p>/Month (annually billed)</p>
                                                <span class="price-item-offer">SAVE UP TO $27.2</span>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li>Generate 1,000 AI Words / month</li>
                                                <li>Google Docs style editor</li>
                                                <li>Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="hourly" class="wrapper-full hide">
                                <div class="row align-items-end">
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left">
                                        <div class="price-item price-item-1">
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Free Trial</h5>
                                                <h2 class="price-item-amount-title">Free</h2>
                                                <p>No Need For Credit Card</p>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li class="not-abatable">Generate 1,000 AI Words / month</li>
                                                <li class="not-abatable">Google Docs style editor</li>
                                                <li class="not-abatable">Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left"
                                        data-delay=".8">
                                        <div class="price-item active">
                                            <div class="price-item-option">
                                                <span>Best Choice</span>
                                            </div>
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Standard Plan</h5>
                                                <div class="price-item-amount"><del>$95</del>
                                                    <h2 class="price-item-amount-title">$55<span>.8</span></h2><span
                                                        class="amount-offer">49.2%</span>
                                                </div>
                                                <p>/Month (annually billed)</p>
                                                <span class="price-item-offer">SAVE UP TO $54.2</span>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li>Generate 1,000 AI Words / month</li>
                                                <li class="not-abatable">Google Docs style editor</li>
                                                <li class="not-abatable">Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left"
                                        data-delay="1.1">
                                        <div class="price-item">
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Liter Plan</h5>
                                                <div class="price-item-amount"><del>$99</del>
                                                    <h2 class="price-item-amount-title">$76<span>.8</span></h2><span
                                                        class="amount-offer">30%</span>
                                                </div>
                                                <p>/Month (annually billed)</p>
                                                <span class="price-item-offer">SAVE UP TO $54.2</span>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li>Generate 1,000 AI Words / month</li>
                                                <li>Google Docs style editor</li>
                                                <li class="not-abatable">Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_left" data-fade-from="left"
                                        data-delay="1.4">
                                        <div class="price-item">
                                            <div class="price-item-head">
                                                <h5 class="price-item-title">Premium Plan</h5>
                                                <div class="price-item-amount"><del>$240</del>
                                                    <h2 class="price-item-amount-title">$99<span>.8</span></h2><span
                                                        class="amount-offer">30%</span>
                                                </div>
                                                <p>/Month (annually billed)</p>
                                                <span class="price-item-offer">SAVE UP TO $27.2</span>
                                                <a class="price-item-btn" href="#">Choose Plan<i
                                                        class="fa-light fa-angle-right"></i></a>
                                            </div>
                                            <ul class="price-item-list">
                                                <li>Priority email & chat support 0</li>
                                                <li>Access 12+ use-cases</li>
                                                <li>Generate 1,000 AI Words / month</li>
                                                <li>Google Docs style editor</li>
                                                <li>Compose & command features</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- price area end -->

                    <!-- testimonial area start -->
                    <section class="testimonial-area pb-40 fix">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-xl-7 col-lg-8">
                                    <div class="section-area text-center">
                                        <span class="section-subtitle tp_fade_left">Testimonials</span>
                                        <h2 class="section-title tp_title_slideup mb-0">Ai-agent 4.8/5 Stars in Over
                                            10,000+ Reviews.</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-active swiper pb-40 pt-50 tp_has_fade_anim" data-fade-from="bottom">
                            <div class="swiper-wrapper roll-slider">
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/1.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Hanson Deck</h6>
                                                <span>Blogger</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/2.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Nigel Nigel</h6>
                                                <span>President of Sales</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/3.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Max Conversion</h6>
                                                <span>SEO Contain Writer</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/4.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Nathaneal Down</h6>
                                                <span>Blogger</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-active-2 swiper pb-100 tp_has_fade_anim" data-fade-from="bottom"
                            data-delay=".8">
                            <div class="swiper-wrapper roll-slider">
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/5.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Russell Sprout</h6>
                                                <span>Blog Writer</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/6.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Hanson Deck</h6>
                                                <span>Blogger</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/7.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Nathaneal Down</h6>
                                                <span>Blogger</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-icon">
                                            <svg width="48" height="36" viewBox="0 0 48 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.06">
                                                    <path
                                                        d="M28.6981 18.7847L46.3344 34.5144C46.9789 35.0893 48 34.6318 48 33.7682V1C48 0.447715 47.5523 0 47 0H29.3637C28.8114 0 28.3637 0.447714 28.3637 0.999998V18.0384C28.3637 18.3235 28.4854 18.595 28.6981 18.7847Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M0.336773 18.7847L17.9731 34.5144C18.6176 35.0893 19.6387 34.6318 19.6387 33.7682V1C19.6387 0.447715 19.191 0 18.6387 0H1.00239C0.450104 0 0.00238991 0.447714 0.00238991 0.999998V18.0384C0.00238991 18.3235 0.124039 18.595 0.336773 18.7847Z"
                                                        fill="currentColor" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="testimonial-head">
                                            <div class="testimonial-head-img">
                                                <img src="{{ asset('frontend/assets/images/testimonial/8.png') }}"
                                                    alt="Image Not Found">
                                            </div>
                                            <div class="testimonial-head-info">
                                                <h6>Russell Sprout</h6>
                                                <span>Blog Writer</span>
                                            </div>
                                        </div>
                                        <ul class="testimonial-rating">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-thin fa-star"></i></li>
                                        </ul>
                                        <p>Maecenas eget ullamcorper dolor placerat ipsum. Aliquam dictum massa eu
                                            libero vehicula, id dapibus ligula vulputate. Donec arcu elit</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- testimonial area end -->

                </main>

                <!-- footer area start -->
                <footer class="footer-area">
                    <div class="container">
                        <div class="footer-top pt-30 ">
                            <div class="row justify-content-between">
                                <div class="col-xl-5 col-lg-4 tp_has_fade_anim" data-fade-from="left">
                                    <div class="footer-left ">
                                        <div class="footer-logo">
                                            <a href="#"><img
                                                    src="{{ asset('frontend/assets/img/ai agent logo.svg') }}"
                                                    alt="Image Not Found"></a>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-7 tp_has_fade_anim" data-fade-from="right"
                                    data-delay=".8">
                                    <div class="footer-right ">

                                        <div class="footer-widget-wrap">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="footer-bottom tp_fade_bottom_footer">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div
                                        class="footer-bottom-copyright d-flex justify-content-center justify-content-md-start">
                                        <p>&copy; 2025 Ai-agent All Rights Reserved by site</p>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div
                                        class="footer-bottom-menu d-flex justify-content-center justify-content-md-end">
                                        <ul>
                                            <li><a href="#">Privacy Policy</a></li>
                                            <li><a href="#">Term of Service</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- footer area end -->


            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const drawer = document.querySelector('.sidebar-info.side-info');
            const overlay = document.querySelector('.offcanvas-overlay');
            const openBtn = document.querySelector('.side-toggle');
            const closeBtn = document.querySelector('.side-info-close');
            const mobileSlot = document.querySelector('.sidebar-info .mobile-menu');
            const desktopNav = document.querySelector('.h5_header-nav-menu > ul');

            // Clone the desktop nav into the drawer and make it accordion-like on mobile
            if (mobileSlot && desktopNav && !mobileSlot.dataset.filled) {
                const clone = desktopNav.cloneNode(true);
                // collapse all submenus initially
                clone.querySelectorAll('.submenu').forEach(sm => sm.style.display = 'none');
                clone.querySelectorAll('.menu-has-child > a').forEach(a => {
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        const li = a.closest('li');
                        const sub = li.querySelector('.submenu');
                        const open = sub && sub.style.display !== 'none';
                        // optional: close others
                        clone.querySelectorAll('.submenu').forEach(s => s.style.display = 'none');
                        clone.querySelectorAll('.menu-has-child').forEach(m => m.classList.remove(
                            'is-open'));
                        if (sub) {
                            sub.style.display = open ? 'none' : 'block';
                            li.classList.toggle('is-open', !open);
                        }
                    });
                });

                // add Join AI to the drawer bottom
                const cta = document.createElement('div');
                cta.className = 'mobile-cta';
                cta.innerHTML = '<a href="/login" class="header-action-btn">Join AI</a>';

                mobileSlot.innerHTML = '';
                mobileSlot.appendChild(clone);
                mobileSlot.appendChild(cta);
                mobileSlot.dataset.filled = 'true';
            }

            function openDrawer() {
                drawer?.classList.add('open');
                overlay?.classList.add('show');
                body.style.overflow = 'hidden';
            }

            function closeDrawer() {
                drawer?.classList.remove('open');
                overlay?.classList.remove('show');
                body.style.overflow = '';
            }

            openBtn?.addEventListener('click', openDrawer);
            closeBtn?.addEventListener('click', closeDrawer);
            overlay?.addEventListener('click', closeDrawer);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeDrawer();
            });
        });
    </script>

</body>

<!-- Mirrored from themephi.net/template/aidoodleh/aidoodle/# by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 21 Jan 2025 09:04:43 GMT -->

</html>
