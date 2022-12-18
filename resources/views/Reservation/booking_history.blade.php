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
                            <form action="{{ route('logout') }}" method="POST" >
                            @csrf
                            <!-- --><button type="submit" class="btn btn_style2">
                                    {{ __('Logout') }}</button>
                            </form>
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
                <div style="text-align: center;"><h1>
                        <b><span style="font-family: 'Lato', sans-serif; color: #fff; ">Table booking history</span></b>
                    </h1></div><br>
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


            </div>

        </div>
    </div >


</div>


<!--card content starts-->
<div class="container">
    <div class="row">
        <div class="col-9 col-sm-10 col-md-10 col-lg-10 col-xl-11">
            <h3><b>Booking History</b></h3>
        </div>
    </div>

    <hr>

    <div class="row">

        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>No</th>
                <th>Reservation Code</th>
                <th>Restaurant Name</th>
                <th>Reservation Date</th>
                <th>Reservation Time</th>
                <th>Number of People</th>
                <th>Reservation Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                @foreach ($reservations as $reservation)
                    <td>{{ ++$i }}</td>
                    <td>{{ $reservation->reservation_code }}</td>
                    <td>{{ $reservation->Restaurant_name }}</td>
                    <td>{{ $reservation->date_of_reservation }}</td>
                    <td>{{ $reservation->time_of_reservation }}</td>
                    <td>{{ $reservation->number_of_people }}</td>
                    <td>{{ $reservation->reservation_status}}</td>
            </tr>
            @endforeach

            </tbody>
            <tfoot>
            <tr>
                <th>No</th>
                <th>Reservation Code</th>
                <th>Restaurant Name</th>
                <th>Reservation Date</th>
                <th>Reservation Time</th>
                <th>Number of People</th>
                <th>Reservation Status</th>
            </tr>
            </tfoot>
        </table>

    </div>
    <br><br>
</div>

<!-- pagination code starts here

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
</nav>-->

<!-- pagination code ends here-->

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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Footer Ends -->


</html>

<script>

    $(document).ready(function() {
        document.getElementById('cover-spin').style.display = "flex";

        setTimeout(function(){
            $('#cover-spin').css('display', 'none');
        }, 3000);

    });
    
</script>
