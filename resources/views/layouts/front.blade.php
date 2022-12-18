<!DOCTYPE html>
<html lang="en"><!-- Basic -->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
   
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
     <!-- Site Metas -->
    <title>TechBiz Restaurant Listing</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Site Icons -->
    <link rel="shortcut icon" href="frontend/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="frontend/images/apple-touch-icon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css')}}">    
	<!-- Site CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css')}}">
    <!-- Frontend Dropdown CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">    
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css')}}">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css')}}">
    <!-- SpinToWin CSS -->
    <link href='https://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('SpinToWin/css/style.css')}}">
    <!-- Spin Wheel CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css')}}" type="text/css" >
    <script type="text/javascript" src="{{ asset('frontend/js/Winwheel.js')}}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <!-- cookery files -->
    <link href="{{ asset('frontend/cookery/css/bootstrap.css')}}" rel="stylesheet" type="text/css" media="all" />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{ asset('frontend/cookery/js/jquery.min.js')}}"></script>
<!-- Custom Theme files -->
<!--theme-style-->
<link href="{{ asset('frontend/cookery/css/style.css')}}" rel="stylesheet" type="text/css" media="all" />	
<!--//theme-style-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Cookery Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!---->
<link href='//fonts.googleapis.com/css?family=Raleway:400,200,100,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
<link href="{{ asset('frontend/cookery/css/styles.css')}}" rel="stylesheet">
<!-- animation-effect -->
<link href="{{ asset('frontend/cookery/css/animate.min.css')}}" rel="stylesheet"> 
<script src="{{ asset('frontend/cookery/js/wow.min.js')}}"></script>
<script>
 new WOW().init();
</script>
<!-- //animation-effect -->
    <!-- end cookery-->

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
	

	@yield('content')


<!-- Start Contact info -->
	<div class="contact-imfo-box">
		<div class="container">
			<div class="row">
				<div class="col-md-4 arrow-right">
					<i class="fa fa-volume-control-phone"></i>
					<div class="overflow-hidden">
						<h4>Phone</h4>
						<p class="lead">
							{{ $restaurant->Restaurant_phone}}
						</p>
					</div>
				</div>
				<div class="col-md-4 arrow-right">
					<i class="fa fa-envelope"></i>
					<div class="overflow-hidden">
						<h4>Email</h4>
						<p class="lead">
							{{ $restaurant->Restaurant_email}}
						</p>
					</div>
				</div>
				<div class="col-md-4">
					<i class="fa fa-map-marker"></i>
					<div class="overflow-hidden">
						<h4>Location</h4>
						<p class="lead">
							{{ $restaurant->Restaurant_address}}
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Contact info -->
	
	
	
	<a href="#" id="back-to-top" title="Back to top" style="display: none;"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></a>

	<!-- ALL JS FILES -->
	<script src="{{ asset('frontend/js/jquery-3.2.1.min.js')}}"></script>
	<script src="{{ asset('frontend/js/popper.min.js')}}"></script>
	<script src="{{ asset('frontend/js/bootstrap.min.js')}}"></script>
    <!-- ALL PLUGINS -->
	<script src="{{ asset('frontend/js/jquery.superslides.min.js')}}"></script>
	<script src="{{ asset('frontend/js/images-loded.min.js')}}"></script>
	<script src="{{ asset('frontend/js/isotope.min.js')}}"></script>
	<script src="{{ asset('frontend/js/baguetteBox.min.js')}}"></script>
	<script src="{{ asset('frontend/js/form-validator.min.js')}}"></script>
    <script src="{{ asset('frontend/js/contact-form-script.js')}}"></script>
    <!-- 
    <script src="{{ asset('frontend/js/custom.js')}}"></script>-->

    <!-- AdminLte jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes 
<script src="{{ asset('dist/js/demo.js')}}"></script>-->
<!-- SpinToWin Scripts -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/utils/Draggable.min.js'></script>
<script src="{{ asset('SpinToWin/js/ThrowPropsPlugin.min.js')}}"></script>
<script src="{{ asset('SpinToWin/js/Spin2WinWheel.js')}}"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/plugins/TextPlugin.min.js'></script>
<script src="{{ asset('SpinToWin/js/index.js')}}"></script>

</body>
</html>	