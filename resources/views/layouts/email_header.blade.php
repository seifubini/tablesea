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
    <link rel="stylesheet" href="{{ asset('Frontend/css/style.css') }}" >

    <title> TableSea Restaurant Reservation</title>
</head>
<!-- Head Ends -->

<!-- Header Starts -->

<header class="header">
    <nav class="navbar fixed-top navbar-expand-lg  p-md-3">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img width="65px" height="81px" src="{{ asset('Frontend/Images/logo_blue.jpg') }}">
            </a>
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right"><font color="#fff" size="5px"><i class="fa fa-bars"></font></i></button>

            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <div class="offcanvas-header mt-3">
                    <button class="btn btn-danger btn-close float-right btn-sm"><b> &times</b> </button>
                    <br><br>
                </div>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <button type="button" class="btn btn_style1" data-toggle="modal" data-target="#signupModal">Sign up</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn_style2 nav_spacer" data-toggle="modal" data-target="#loginModal">Sign in</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


<!-- Header Ends -->


<!-- Body Starts -->


<body>


<div class="jumbotron shadow-none text-white jumbotron-image shadow"
     style="background-image: url({{ asset('Frontend/Images/bg6.jpg') }});" height="40%" >

    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-5">
                <div style="text-align: center;"><h1><b><span style="font-family: 'Lato', sans-serif; color: #fff; ">Reservation Update</span></b> </h1></div><br>
            </div>
        </div>
    </div >


</div>

<section class="content">

    @yield('content')

</section>

<footer class="container-fluid footer-bg py-5">
    <div class="container ">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="row">

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

                    <div class="col-md-4 col-4 text-light">
                        <h6> Business </h6>
                        <button type="button" class="btn btn_style2 margin_top_btn_on_sm">Sign up as restaurant</button><br>
                        <button type="button" class="btn btn_style2 mt-1 " >&nbsp;&nbsp;Sign up as dinner&nbsp;&nbsp;   </button>
                        <h6 class="mt-3"> JOIN US ON </h6>
                        <ul class="social_footer_ul">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul>
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

        <p style="margin-left:-4px; font-size:14px" class="text-light"> TableSea is part of Booking Holdings, the world leader in online travel and related services.</p>

        <ul class="foote_bottom_ul_amrc" style="margin-bottom: -25px !important;">
            <li><img src="{{ asset('Frontend/Images/footerImg.PNG') }}"  width="100px" style="margin-left:15px;"/></li>
            <li><img src="{{ asset('Frontend/Images/footerImg2.png') }}"  width="100px" style="margin-left:15px;"/></li>


        </ul>



    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="{{ asset('Frontend/js/script.js') }}" ></script>
<script src="{{ asset('Frontend/js/bootstrap-offcanvas/bootstrap.offcanvas.js') }}"></script>

<!-- Footer Ends -->

</body>
</html>
