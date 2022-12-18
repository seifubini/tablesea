<!doctype html>
<html lang="en">

<!-- Head Starts -->
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('Frontend/css/style.css') }}" >
<!-- SweetAlert2
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">-->
<!-- Toastr
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">-->
<!-- SweetAlert2
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>-->
<!-- Toastr
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>-->
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Async script executes immediately and must be after any DOM elements used in callback.
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJcH6UEQwwVwyhQsys_LzOSewv4kOtGUE&callback=initMap&libraries=&v=weekly" async></script>-->

    <!-- MomentJS -->
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="https://jacoblett.github.io/bootstrap4-latest/bootstrap-4-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js" ></script>
    <script src="{{ asset('Frontend/js/restaurant_scripts.js') }}" ></script>

    <title> TableSea Restaurant Reservation </title>

    <style type="text/css">
        #cover-spin {
            position:fixed;
            width:100%;
            left:0;right:0;top:0;bottom:0;
            background-color: rgba(255,255,255,0.7);
            z-index:9999;
            display:flex;
        }

        @-webkit-keyframes spin {
            from {-webkit-transform:rotate(0deg);}
            to {-webkit-transform:rotate(360deg);}
        }

        @keyframes spin {
            from {transform:rotate(0deg);}
            to {transform:rotate(360deg);}
        }

        #cover-spin::after {
            content:'';
            display:block;
            position:absolute;
            left:48%;top:40%;
            width:40px;height:40px;
            border-style:solid;
            border-color:#0065A3;
            border-top-color:transparent;
            border-width: 4px;
            border-radius:50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }

        .time_btn_style {
            width:100%;
            background-color: #0065A3;
            color: #fff;
            border-radius: 10px;
        }

        form .error {
            color: #ffffff;
            font-weight: bold;
            border-radius: 10px;
        }

    </style>

</head>
<!-- Head Ends -->

<!-- Header Starts -->

<header class="header">
    <nav class="navbar fixed-top navbar-expand-lg  p-md-3">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img width="65px" height="81px" src="{{ asset('Frontend/Images/logo_blue.jpg') }}">
            </a>
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right"><span
                    style="color: #fff; "><i class="fa fa-bars"></i></span></button>

            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <div class="offcanvas-header mt-3">
                    <button class="btn btn-danger btn-close float-right btn-sm"><b> &times</b> </button>
                    <br><br>
                </div>
                @if(Auth::check())
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a href="{{ route('booking_history', Auth::user()->id) }}" style="text-decoration: none;">
                                <button type="button" class="btn btn_style1" >My Bookings</button>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" >
                            @csrf
                            <!-- --><button type="submit" class="btn btn_style2 nav_spacer">
                                    {{ __('Logout') }}</button>
                            </form>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item">
                            <button type="button" class="btn btn_style1" data-toggle="modal" id="signup_btn" data-target="#signupModal">Sign up</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn_style2 nav_spacer" data-toggle="modal" id="login_btn" data-target="#loginModal">Sign in</button>
                        </li>

                    </ul>
                @endif
            </div>

        </div>
    </nav>
</header>

<!-- Header Ends -->


<!-- Body Starts -->


<body>

<div id="cover-spin" class="align-items-center"></div>

<div class="jumbotron shadow-none text-white jumbotron-image shadow"
     style="background-image: linear-gradient(to top, rgba(21, 21, 21, 0.08) ,rgba(21, 21, 21, 0.96)),
         url(' {{ asset('images/restaurant')}}/{{$restaurant->Restaurant_photo}} '); background-size:cover;
         display: block; background-position: 50% 50%;" height="40%" >

    <div class="container mt-5">

        <div class="row">
            <div class="col-sm-12 " >
                <div style="text-align: center;">
                    <h1><b>
                            <span style="font-family: 'Lato', sans-serif;  color: #fff; "> {{$restaurant->Restaurant_name}} </span>
                        </b></h1>
                </div><br>
            </div>
        </div>

        <div class="row">

            <div class="col-sm-2 col-12 col-md-3 col-lg-3">
            </div>

            <div class="col-sm-12 col-12 col-md-6 col-lg-6 ">

                <form action="javascript:void(0);" id="reserve_restaurant_form">

                    <!--Customized bootstrap alert with icons starts-->
                    <div class="form-row" id="toaster" style="justify-content: center; display: flex;">
                    </div>
                    <!--Customized bootstrap alert with icons Ends-->

                    <input type="hidden" style="display: none;" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" value="{{$restaurant->id}}" id="restaurant_id" style="display: none;" name="restaurant_id">

                    <div class="form-row" style="margin-top:10px;">
                        <div class="form-group col-md-6 col-6 col-sm-6">

                            <input type="date" class="form-control input_style" id="reservation_date" name="date_of_reservation" required>
                        </div>

                        <div class="form-group col-md-6 col-6 col-sm-6">

                            <i id="ios-clock" class="fa fa-clock-o"></i>
                            @if($hour_count > 0)
                                <select class="form-control2 input_style form-control" name="time_of_reservation" id="reservation_time" required>
                                    <option id="time_selector" value="" style="display:none;">Select time</option>
                                    @for($i = 0; $i < $hour_count; $i++)
                                        <option value="{{$working_hours[$i]['hour']}}">
                                            {{ date('h:i A', strtotime($working_hours[$i]['hour']))}} - {{$working_hours[$i]['hour_name'] }}</option>
                                    @endfor
                                </select>
                            @else
                                <select class="form-control2 input_style form-control" name="time_of_reservation" id="reservation_time" required>
                                    <option id="time_selector" value="" style="display:none;">Select time</option>
                                    @foreach($free_hours as $free_hour)
                                        <option value="{{$free_hour}}">{{ date('h:i A', strtotime($free_hour)) }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div>
                    </div>

                    <div class="form-row" style="margin-top:-8px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <i id="ios-user" class="fa fa-user-o"></i>
                                    <select class="form-control2 input_style form-control" name="number_of_people" id="number_of_people" required>
                                        <option value="" selected disabled style="padding-left:-20px;">Number of persons</option>
                                        <option value="1">1 Person</option>
                                        <option value="2">2 People</option>
                                        <option value="3">3 People</option>
                                        <option value="4">4 People</option>
                                        <option value="5">5 People</option>
                                        <option value="6">6 People</option>
                                        <option value="7">7 People</option>
                                        <option value="8">8 People</option>
                                        <option value="9">9 People</option>
                                        <option value="10">10 People</option>
                                        <option value="11">11 People</option>
                                        <option value="12">12 People</option>
                                        <option value="13">13 People</option>
                                        <option value="14">14 People</option>
                                        <option value="15">15 People</option>
                                        <option value="16">16 People</option>
                                        <option value="17">17 People</option>
                                        <option value="18">18 People</option>
                                        <option value="19">19 People</option>
                                        <option value="20">20 Persons</option>
                                        <option value="large_party">Large Party</option>

                                    </select>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div id="available_times" hidden class="row form-group">

                    </div>

                    <div class="form-row" id="user_inputs" style="display: none; margin-top:-8px;">
                        @if (Auth::check())
                            <div class="d-block col-lg-4 col-md-6 form-group" style="margin-bottom: 2%;">
                                <input type="email" value="{{Auth::user()->email}}" class="form-control input_style" name="reserver_email"
                                       style="display: none;" id="reserver_email" required>
                            </div>
                            <div class="d-block col-lg-4 col-md-6 form-group" style="margin-bottom: 2%;">
                                <input type="text" value="{{Auth::user()->name}}" class="form-control input_style" name="user_full_name"
                                       style="display: none;" id="user_full_name" placeholder="Full Name" required>
                            </div>
                            <div class="d-block col-lg-4 col-md-6 form-group">
                                <input type="text" value="{{Auth::user()->user_phone_number}}" class="form-control input_style" name="phone_number"
                                       style="display: none;" id="phone_number" minlength="10" maxlength="13" title="10 to 13 characters" placeholder="Phone Number" required>
                            </div>
                        @else
                            <div class="d-block col-lg-4 col-md-6 form-group" style="margin-bottom: 2%;">
                                <input type="email" class="form-control input_style" name="reserver_email" style="display: none;" id="reserver_email" placeholder="Email" required>
                            </div>
                            <div class="d-block col-lg-4 col-md-6 form-group" style="margin-bottom: 2%;">
                                <input type="text" class="form-control input_style" name="user_full_name" style="display: none;" id="user_full_name" placeholder="Full Name" required>
                            </div>
                            <div class="d-block col-lg-4 col-md-6 form-group">
                                <input type="text" class="form-control input_style" name="phone_number" minlength="10" maxlength="13" pattern=".{10,13}" title="10 to 13 characters"
                                       style="display: none;" id="phone_number" placeholder="Phone Number" required>
                            </div>
                        @endif
                    </div>

                    <div class="form-row" id="additional_message_area" style="display: none; margin-top:-8px; margin-bottom: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control1 input_style form-control" id="reserver_additional_message" maxlength="150" placeholder="Special Request"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-row" style="margin-top:-8px; margin-left:2px; margin-right:2px;">

                        <button type="submit" class="btn btn_style btn-block" style="height:40px; border-radius:10px; font-size:14px;" id="check_button">
                            <b>CHECK AVAILABILITY</b></button>

                    </div>

                    <div class="form-row" style="margin-top:-8px; margin-left:2px; margin-right:2px;">

                        <button type="submit" class="btn btn_style btn-block" style="height:40px; border-radius:10px; font-size:14px; display: none;"
                                id="book_table">
                            <b>BOOK TABLE</b></button>

                    </div>

                    <div class="form-row" style="margin-top:-8px; margin-left:2px; margin-right:2px;">

                        <button type="submit" class="btn btn_style btn-block" style="height:40px; border-radius:10px; font-size:14px; display: none;"
                                id="book_waitlist">
                            <b>BOOK TABLE</b></button>

                    </div>

                </form>

            </div>


        </div>

    </div >


</div>


<!-- Restaurant Details starts here -->

<div class="container">

    <h1 class="text_underline"><b>{{$restaurant->Restaurant_name}}</b></h1>
    <div class="row">

        <div class="col-sm-12 col-12 col-md-12 col-lg-6 col-xl-6">

            <h3 class="mt-5"><b>Details</b></h3><br>

            <div class="row">
                <div class="col-4 col-lg-4">
                    <h5>Open Hours</h5>
                </div>
                <div class="col-8 col-lg-8">
                    <h5><span style="color: #0065A3; ">From
                            {{ date('h:i A', strtotime($restaurant->restaurant_opening_hour)) }}
                             - {{ date('h:i A', strtotime($restaurant->restaurant_closing_hour)) }}
                    </span></h5>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-4">
                    <h5>Country</h5>
                </div>
                <div class="col-lg-8 col-8">
                    <h5><span style="color: #0065A3; ">{{$restaurant->Restaurant_Country}}</span></h5>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-4">
                    <h5>Location</h5>
                </div>
                <div class="col-lg-8 col-8">
                    <h5><span style="color: #0065A3; ">{{$restaurant->Restaurant_address}}</span></h5>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-4">
                    <h5>Phone</h5>
                </div>
                <div class="col-lg-8 col-8">
                    <h5><span style="color: #0065A3; ">{{$restaurant->Restaurant_phone}}</span></h5>
                </div>
            </div>


        </div>
        <div class="col-sm-12 col-12 col-md-12 col-lg-6 col-xl-6">
            <h3 class="mt-5"><b>Description</b></h3><br>
            <p style="font-size:18px;">{{ $restaurant->Restaurant_description }}</p>
        </div>

    </div>

    <br>
    <br>
    <br>

    <section class="container">

        <div class="row">

            <div class="col-8 col-lg-11 col-xl-11 col-sm-10 col-md-11">
                <h3 ><b>Gallery</b> </h3>
            </div>
            <div class="col-4 col-lg-1 col-xl-1 col-sm-2 col-md-1">
                <div class="btn-group">
                    <button type="submit" class="owlprev btn btn-info form-control btn-sm "><i style="color:#fff;" class="fa fa-chevron-left"></i></button>
                    <button type="submit" class="owlnext btn btn-info form-control btn-sm" style="margin-left:10px;"><i style="color:#fff;" class="fa fa-chevron-right"></i></button>
                </div>
            </div>



            <div class="basic-carousel owl-carousel owl-theme mt-3 add_margin_sm">
            @foreach($restaurant_images as $image)
                <!-- Item 2 -->
                    <div class="item">
                        <div class="card border-light">
                            <img src="{{ asset('images/gallery') }}/{{$image->restaurant_image_path}}" class="card-img-top cit rounded-top" height="300px"
                                 alt="profile-image">
                        </div>
                    </div>
                @endforeach

            </div>


        </div>

    </section>

    <!-- <h4><b>Map</b></h4>
    <div id="map"></div>-->
    <br><br>
</div>


<!-- Login Modal -->
<div id="loginModal" class="modal hide" >
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#f1f1f1;">
            <div class="modal-header" >
                <h4 class="modal-title text-center" style="font-size:16px;"><b id="welcome_message">
                        LOGIN TO YOUR ACCOUNT</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_login">
                    <span aria-hidden="true"><span style="color: red; ">&times;</span></span>
                </button>
            </div>
            <div class="modal-body" style="margin:15px;">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> <strong>Whoops!</strong><br><br>
                            There were some problems with your input.</h5>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif

                <form id="LoginForm" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-row" >
                        <label class="control-label">Email:</label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-envelope-o"></i>
                                <input type="text" class="form-control form-control2 input_style " id="email" name="email"
                                       placeholder=" Email" required>

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-9">
                                <label class="control-label">Password:</label>
                            </div>
                            <div class="col-3">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">{{ __('Forgot?') }}</a>
                                @endif

                            </div>
                        </div>


                        <div class="form-row" >
                            <div class="col-md-12">

                                <div class="form-group">

                                    <i class="fa fa-key"></i>
                                    <input type="password" class="form-control form-control2 input_style " id="password1" name="password"
                                           placeholder="Password" required>

                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="form-group">
                        <div>

                            <button type="submit" class="btn btn_style btn-lg" style="border-radius:15px;"> <b>Log in</b></button>
                        </div>
                    </div>
                </form>

            </div>

            <div class="modal-footer text-muted mr-auto" style="margin-left:15px;margin-top:-15px;">
                Don't have an account yet?<br> <a href="#">Sign up </a>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Signup Modal -->
<div id="signupModal" class="modal hide"  >
    <div class="modal-dialog">
        <div class="modal-content"style="background-color:#f1f1f1;">
            <div class="modal-header">
                <h4 class="modal-title text-center" style="font-size:16px;">
                    <b id="signup_thank_you">SIGN UP FOR NEW ACCOUNT</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_signup">
                    <span aria-hidden="true"><span style="color: red; ">&times;</span></span>
                </button>
            </div>
            <div class="modal-body" style="margin:15px;">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> <strong>Whoops!</strong><br><br>
                            There were some problems with your input.</h5>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf


                    <div class="form-row" id="register_name">
                        <label class="control-label">Full Name: </label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-user-o"></i>
                                <input type="text" class="form-control form-control2 input_style " id="full_name_register"
                                       name="name" :value="old('name')"   placeholder=" Full Name" required>

                            </div>
                        </div>
                    </div>

                    <div class="form-row" id="register_email">
                        <label class="control-label">Email:</label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-envelope-o"></i>
                                <input type="text" class="form-control form-control2 input_style" id="email_register" name="email" :value="old('email')"
                                       placeholder=" Email" required>

                            </div>
                        </div>
                    </div>

                    <div class="form-row" id="register_phone">
                        <label class="control-label">Phone Number: </label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-mobile-phone"></i>
                                <input type="text" class="form-control form-control2 input_style " id="phone_register"
                                       name="user_phone_number" :value="old('user_phone_number')" placeholder="Phone Number">

                            </div>
                        </div>
                    </div>

                    <div class="form-row" >
                        <label class="control-label">Password:</label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-key"></i>
                                <input type="password" class="form-control form-control2 input_style " id="password" name="password"
                                       placeholder=" Password" required>

                            </div>
                        </div>
                    </div>

                    <div class="form-row" >
                        <label class="control-label">Confirm Password:</label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-key"></i>
                                <input type="password" class="form-control form-control2 input_style " id="confirm_password"
                                       name="password_confirmation"  placeholder=" Confirm Password" required>

                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_type" value="Client" style="display: none;">

                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn_style btn-lg" style="border-radius:15px;"><b>Sign up</b></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer mr-auto"  style="margin-left:15px;margin-top:-15px;">
                Already have account? <a href="#">Login </a>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Welcome back Message modal  -->
<div class="modal fade" id="modal-default-welcome">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="logged_user_message"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal end here -->

@if($restaurant->Reservation_update_type == 'Automatic')
    <!-- Wait list modal  -->
    <div class="modal hide" id="modal-default-wait_list">
        <div class="modal-dialog modal-default">
            <div class="modal-content">
                <div class="modal-header">

                    <p class="modal-title">
                        <strong>Restaurant is fully booked!</strong>
                        <br>Try available times. Or get into the waiting list.
                    </p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p class="modal-title">Select Time</p>
                    <br>

                    <div class="row form-group" id="available_hours">
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12 col-md-12 col-sm-12">
                            <button type="button" class="btn btn_style"
                                    style="height:40px; border-radius:10px; margin-bottom: 5px; font-size:14px; width: 100%; background-color: #363636;"
                                    id="waiting_list_btn">
                                <b>Add me to the wait list</b>
                            </button>
                        </div>

                        <div class="col-md-12 col-md-12 col-sm-12">
                            <button type="button" class="btn btn_style"
                                    style="height:40px; border-radius:10px; margin-bottom: 5px; font-size:14px; width: 100%; background-color: #363636;"
                                    id="other_time_btn">
                                <b>Book a different time</b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal end here -->
@else
    <!-- Wait list modal  -->
    <div class="modal hide" id="modal-default-wait_list">
        <div class="modal-dialog modal-default">
            <div class="modal-content">
                <div class="modal-header">

                    <p class="modal-title">
                        <strong>Restaurant is fully booked!</strong>
                        <br>Try available times. Or get into the waiting list.
                    </p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row form-group">
                        <div class="col-md-12 col-md-12 col-sm-12">
                            <button type="button" class="btn btn_style"
                                    style="height:40px; border-radius:10px; margin-bottom: 5px; font-size:14px; width: 100%; background-color: #363636;"
                                    id="waiting_list_btn">
                                <b>Add me to the wait list</b>
                            </button>
                        </div>

                        <div class="col-md-12 col-md-12 col-sm-12">
                            <button type="button" class="btn btn_style"
                                    style="height:40px; border-radius:10px; margin-bottom: 5px; font-size:14px; width: 100%; background-color: #363636;"
                                    id="other_time_btn">
                                <b>Book a different time</b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal end here -->
@endif

</body>

<!-- Footer Starts -->

<footer class="container-fluid footer-bg py-5">
    <div class="container ">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="row">
                    <!-- <div class="col-md-6 col-6 ">
                        <div class="logo-part text-light">
                            <h6>DISCOVER</h6>
                            <a href="#" class="btn-footer"> Dining Rewards </a><br>
                            <a href="#" class="btn-footer"> Private Dining</a><br>
                            <a href="#" class="btn-footer"> Reserve for Others </a><br>
                            <a href="#" class="btn-footer"> Cuisines Near Me</a><br>
                            <a href="#" class="btn-footer"> Restaurants Near Me </a><br>
                            <a href="#" class="btn-footer"> Delivery Near Me</a><br>
                            <a href="#" class="btn-footer"> Cuisines </a><br>
                            <a href="#" class="btn-footer"> Restaurants Open Now</a>
                        </div>
                    </div>-->

                    <div class="col-md-4 col-4 text-light">
                        <h6> Sign Up </h6>
                        <a href="https://restaurants.tablesea.com/" style="text-decoration: none;" target="_blank">
                            <button type="button" class="btn btn_style2 margin_top_btn_on_sm">Sign up as restaurant</button></a><br>
                        <button type="button" class="btn btn_style2 mt-1 " >&nbsp;&nbsp;Sign up as dinner&nbsp;&nbsp; &nbsp;   </button>
                        <h6 class="mt-3"> JOIN US ON </h6>
                        <ul class="social_footer_ul">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>

                    <div class="col-md-4 col-4">
                        <div class="logo-part text-light">
                            <h6>TableSea</h6>
                            <a href="#" class="btn-footer"> About Us</a><br>
                            <a href="#" class="btn-footer"> Blog </a><br>
                            <a href="#" class="btn-footer"> Careers </a><br>
                            <a href="#" class="btn-footer"> Press</a><br>

                        </div>
                    </div>

                    <div class="col-md-4 col-4 text-light">
                        <h6> More </h6>

                        <a href="#" class="btn-footer"> TableSea for ios </a><br>
                        <a href="#" class="btn-footer"> TableSea for Android</a><br>
                        <a href="#" class="btn-footer"> Affiliate Program </a><br>
                        <a href="#" class="btn-footer"> Contact Us</a><br>
                    </div>

                </div>
            </div>

            <!--  <div class="col-md-6 col-6">

                 <div class="row top_margin_on_sm">
                     <div class="col-md-6 col-lg-6  col-6 col-sm-4 text-light">




                     </div>

                     <div class="col-md-6 col-lg-6 text-light top_margin_on_sm ">

                     </div>

                 </div>
             </div>-->
        </div>

        <ul class="foote_bottom_ul_amrc">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Use</a></li>
            <li><a href="#">Cookies</a></li>

        </ul>

        <p style="margin-left:-4px; font-size:14px" class="text-light"> Copyright © 2021 TableSea, Inc - All rights reserved. </p>


        <div class="row">
            <div class="col-md-12 col-lg-12">
                <hr style="background-color:#fff; margin-top:-3px;">
            </div>
        </div>



    <!-- <ul class="foote_bottom_ul_amrc" style="margin-bottom: -25px !important;">
            <li><img src="{{ asset('Frontend/Images/footerImg.PNG') }}"  width="100px" style="margin-left:15px;"/></li>


        </ul>-->


    </div>
</footer>


<input type="hidden" id="blue_logo" value="{{ asset('Frontend/Images/logo_blue.jpg') }}" style="display: none;">
<input type="hidden" id="white_logo" value="{{ asset('Frontend/Images/logo_white.jpg') }}" style="display: none;">
<input type="hidden" id="check_url" value="{{ route('check_restaurant')}}" style="display: none;">
<input type="hidden" id="book_url" value="{{ route('book_restaurant')}}" style="display: none;">


@if(session('reserve_data') != "")
    @php
        $data = session('reserve_data');
        foreach($data as $value)
        {
        $reserved_date = $value['reserved_date'];
        $reserved_time = $value['reserved_time'];
        $number_of_people = $value['number_of_people'];
        }
    @endphp

    <input type="hidden" id="reserve_data" value="set" style="display: none;">
    <input type="hidden" id="reserved_date" value="{{$reserved_date}}" style="display: none;">
    <input type="hidden" id="reserved_time" value="{{$reserved_time}}" style="display: none;">
    <input type="hidden" id="num_of_people" value="{{$number_of_people}}" style="display: none;">
@else
    <input type="hidden" id="reserve_data" value="not_set" style="display: none;">
@endif


<script>

    $(document).ready(function() {

        setTimeout(function(){
            $('#cover-spin').css('display', 'none');
        }, 1000);
        
        
        $("#signup_btn").click( function(){
            $("#signupModal").show();
        });
        
        $("#login_btn").click( function(){
            $("#loginModal").show();
        });

    });

    function isIOSDevice(){
        return !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
    }
    if(isIOSDevice()){
        var myobj = document.getElementById("ios-user");
        myobj.remove();

        var myobj2 = document.getElementById("ios-clock");
        myobj2.remove();


        var styles = `

        select.form-control {
          text-indent: 8px;
          }

        input[type=date]{
           text-indent: -26px;
        }

          `

        var styleSheet = document.createElement("style")
        styleSheet.type = "text/css"
        styleSheet.innerText = styles
        document.head.appendChild(styleSheet)
    }
    else{


    }

</script>

<!-- Footer Ends -->


</html>

