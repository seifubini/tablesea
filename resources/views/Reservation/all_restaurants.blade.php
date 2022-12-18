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
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<!-- <link rel="stylesheet" href="{{ asset('Frontend/css/bootstrap-offcanvas/bootstrap.offcanvas.min.css') }}"/>-->
    <link rel="stylesheet" href="{{ asset('Frontend/css/style.css') }}" >
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Autocomplete CSS
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">-->
    <link rel="stylesheet" href="{{ asset('css/autocomplete.min.css') }}">
    <link rel="stylesheet" href="{{ asset('autocomplete/easy-autocomplete.min.css') }}">
    <link rel="stylesheet" href="{{ asset('autocomplete/easy-autocomplete.themes.min.css') }}">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- <script src="{{ asset('Frontend/js/timepicker.min.js') }}"></script>
    <script src="{{ asset('Frontend/js/bootstrap-offcanvas/bootstrap.offcanvas.js') }}"></script>-->
    <!-- MomentJS -->
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <!-- Autocomplete on Search Plugin -->
    <script src="{{ asset('js/autocomplete.js') }}"></script>
    <script src="{{ asset('js/autocomplete.min.js') }}"></script>
    <script src="{{ asset('autocomplete/jquery.easy-autocomplete.min.js') }}"></script>
    <script src="{{ asset('Frontend/js/script.js') }}" ></script>
    <script src="https://jacoblett.github.io/bootstrap4-latest/bootstrap-4-latest.min.js"></script>


    <title> TableSea Restaurant Reservation </title>
    
    <style type="text/css">
        #cover-spin {
            position:fixed;
            width:100%;
            left:0;right:0;top:0;bottom:0;
            background-color: rgba(255,255,255,0.7);
            z-index:9999;
            display:none;
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
    </style>
    
</head>
<!-- Head Ends -->

<!-- Header Starts -->

<header class="header">
    <nav class="navbar fixed-top navbar-expand-lg  p-md-3">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img width="65px" height="81px" src="{{ asset('Frontend/Images/logo_blue.jpg') }}" alt="TableSea Logo">
            </a>
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right">
                <span style="color: #fff; "><i class="fa fa-bars"></i></span></button>

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
                            <button type="button" class="btn btn_style1" data-toggle="modal" data-target="#signupModal">Sign up</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn_style2 nav_spacer" data-toggle="modal" data-target="#loginModal">Sign in</button>
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
         url({{ asset('images/TableSeaHomePage.jpg') }}); background-size:cover;
         display: block; background-position: 50% 50%; height: 40%;" height="40%" >

    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-5">
                <div style="text-align: center;"><h1><b><span style="font-family: 'Lato', sans-serif; color: #fff; ">Table booking made easy</span></b> </h1></div><br>
            </div>
        </div>

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

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Success!</h5>
                <p>{{ $message }}</p>
            </div>
    @endif


        <div class="row">

            <div class="col-sm-2 col-12 col-md-3 col-lg-3">
            </div>

            <div class="col-sm-12 col-12 col-md-6 col-lg-6 ">


                <form action="javascript:void(0);">
                    
                    <!--Customized bootstrap alert with icons starts-->
                    <div class="form-row" id="toast" aria-atomic="true" data-delay="5000"
                         style="justify-content: center; background-color: #0664A4; margin-left: 1%; display: none; margin-bottom: 5%;">
    
                        <div class="toast-body">
                            <strong style="color: #fff; text-align: center;" id="toast_message"></strong>
                        </div>
    
                    </div>
                    <!--Customized bootstrap alert with icons Ends-->

                    <input type="hidden" style="display: none;" id="_token" value="{{ csrf_token() }}">

                    <div class="form-row">
                        <div class="form-group col-md-6 col-6 col-sm-6">

                            <input type="date" class="form-control input_style" id="reservation_date" name="date" >
                        </div>

                        <div class="form-group col-md-6 col-6 col-sm-6">

                            <i id="ios-clock" class="fa fa-clock-o"></i>
                            <select class="form-control2 input_style form-control" name="time_of_reservation" id="reservation_time">
                                @foreach($working_hours as $available_hour)
                                    <option value="{{ $available_hour }}">{{ date('h:i A', strtotime($available_hour)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row" style="margin-top:-8px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <i id="ios-user" class="fa fa-user-o"></i>
                                <select class="form-control2 input_style form-control" id="number_of_people" name="number_of_people" required>
                                    <option value="" selected disabled>Number of persons</option>
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


                    <div class="form-row" style="margin-top:-8px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <i class="fa fa-search"></i>
                                <input type="text" class="form-control2 input_style form-control" name="Restaurant_name" id="search-form"
                                       placeholder="Location, Restaurant or Cuisine">
                            <ul class="form-control2 input_style form-control" id="searchResult" style="color: #000000; display: none;"></ul>
                            </div>
                            
                        </div>
                    </div>


                </form>

                <div class="form-row" style="margin-top:-8px; margin-left:2px; margin-right:2px;">

                    <button type="button" class="btn btn_style btn-block" style="height:40px; border-radius:10px; font-size:14px;" id="check_button">
                        <b>CHECK AVAILABILITY</b></button>

                </div>

            </div>

        </div>
    </div >


</div>



<!--card content starts-->
<div class="container">
    <div class="row">
        <div class="col-9 col-sm-10 col-md-10 col-lg-10 col-xl-11">
            <h3><b>Restaurants</b></h3>
        </div>
    </div>

    <hr>

    <div class="row no-gutters">

        @foreach($restaurants as $restaurant)

        <div class="col-sm-6 col-12 col-md-6 col-lg-4 col-xl-3" >

            <div class="card card_blue all_res_card" style="border-radius:10px;margin:10px;">
                <img class="card-img" src="{{ asset('images/restaurant')}}/{{$restaurant->Restaurant_photo}}" height="200px"
                     alt="Card image cap" style="border-top-left-radius:10px;border-top-right-radius:10px;">

                <div class="row">
                    <div class="col-sm-6 col-4 col-md-4 col-lg-6 col-xl-5" >
                        <div class="card-img-overlay  d-flex flex-column justify-content-center card_image_overlay">
                            <div class="card-title bg-white" style="border-radius:8px;">
                                <i class="fa fa-star rating_style"></i> <span class="rating_value_style"> 4.5</span></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-6 col-md-5 col-lg-5 col-xl-5" >
                        <div class="card-img-overlay  d-flex flex-column justify-content-center card_image_overlay">
                            <div class="card-title bg-success verify_open ">
                                <span class="verify_fa_style"><small>
                                        <b><i class="fa fa-check"> </i> VERIFIED OPEN</b>
                                    </small></span></div>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                    <h5 class="card-title text-light" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <b>{{ $restaurant->Restaurant_name }}</b>
                    </h5>

                    <p class="card-text text-light small" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $restaurant->Restaurant_address}}
                    </p>
                    <p class="card-text text-light less_spacing_input small" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $restaurant->Restaurant_Country}}, {{ $restaurant->Restaurant_City}}
                    </p>
                    <p class="card-text text-light less_spacing_input small" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        Casual Dining . {{$restaurant->Restaurant_price_range}}
                    </p>
                    <a href="{{ route('reserve_restaurant', $restaurant->id) }}" class="text-black">
                        <button type="button" class="btn btn-light btn-sm" >BOOK TABLE</button></a>
                </div>
            </div>

        </div>
        @endforeach

    </div>
    <br><br>
</div>

<!-- pagination code starts here-->

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
</nav>

<!-- pagination code ends here-->


<!-- Login Modal -->
<div id="loginModal" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#f1f1f1;">
            <div class="modal-header" >
                <h4 class="modal-title text-center" style="font-size:16px;"><b>LOGIN TO YOUR ACCOUNT</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                <input type="text" class="form-control form-control2 input_style " id="email1" name="email"
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
                                    <input type="password" class="form-control form-control2 input_style " id="password" name="password"
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
<div id="signupModal" class="modal fade"  >
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#f1f1f1;">
            <div class="modal-header">
                <h4 class="modal-title text-center" style="font-size:16px;"><b>SIGN UP FOR NEW ACCOUNT</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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


                    <div class="form-row" >
                        <label class="control-label">Full Name: </label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-user-o"></i>
                                <input type="text" class="form-control form-control2 input_style " id="firstname"
                                       name="name" :value="old('name')"   placeholder=" Full Name" required>

                            </div>
                        </div>
                    </div>

                    <div class="form-row" >
                        <label class="control-label">Email:</label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-envelope-o"></i>
                                <input type="text" class="form-control form-control2 input_style " id="email" name="email" :value="old('email')"
                                       placeholder=" Email" required>

                            </div>
                        </div>
                    </div>

                    <div class="form-row" >
                        <label class="control-label">Phone Number: </label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-mobile-phone"></i>
                                <input type="text" class="form-control form-control2 input_style " id="lastname"
                                       name="user_phone_number" :value="old('user_phone_number')" placeholder="Phone Number">

                            </div>
                        </div>
                    </div>

                    <div class="form-row" >
                        <label class="control-label">Password:</label>
                        <div class="col-md-12">

                            <div class="form-group">

                                <i class="fa fa-key"></i>
                                <input type="password" class="form-control form-control2 input_style " id="password1" name="password"
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
                    <input type="hidden" name="user_type" value="Client">

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

</body>

<!-- Body Ends -->



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
<input type="hidden" id="get_all_restaurants" value="{{ route('get_all_restaurants') }}" style="display: none;">
<input type="hidden" id="check_url" value="{{ route('check_availability')}}" style="display: none;">

<script>

    $(document).ready(function() {
        document.getElementById('cover-spin').style.display = "flex";

        setTimeout(function(){
            $('#cover-spin').css('display', 'none');
        }, 3000);

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
