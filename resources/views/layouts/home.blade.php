<!--A Design by W3layouts 
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>TechBiz Restaurant Listing</title>
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
<script type="application/x-javascript"> 
addEventListener("load", function() 
{ setTimeout(hideURLbar, 0); 
}, false); function hideURLbar()
{ window.scrollTo(0,1); } 
</script>
<!---->
<link href='//fonts.googleapis.com/css?family=Raleway:400,200,100,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
<!-- start-smoth-scrolling -->
		<script type="text/javascript" src="{{ asset('frontend/cookery/js/move-top.js')}}"></script>
		<script type="text/javascript" src="{{ asset('frontend/cookery/js/easing.js')}}"></script>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
		</script>
	<!-- start-smoth-scrolling -->
<link href="{{ asset('frontend/cookery/css/styles.css')}}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend/cookery/css/component.css')}}" />
	<!-- animation-effect -->
<link href="{{ asset('frontend/cookery/css/animate.min.css')}}" rel="stylesheet"> 
<script src="{{ asset('frontend/cookery/js/wow.min.js')}}"></script>
<script>
 new WOW().init();
</script>
<!-- //animation-effect -->

</head>
<body>

 <div class="header" style="background:url('{{ asset('images/home_banner.jpg') }}') no-repeat left;
	width:100%;
	background-size:cover;
	min-height:750px;
	display:block;
	    padding-top: 1em;">
	<div class="container">
		<div class="logo animated wow pulse" data-wow-duration="1000ms" data-wow-delay="500ms">
			<h1><a href="index.html"><img src="images/my_only_menu.jpg" alt="" width="250px" height="100px"></a></h1>
		</div>
		<!-- 
		<div class="nav-icon">		
			<a href="#" class="navicon"></a>
				<div class="toggle">
					<ul class="toggle-menu">
						<li><a class="active" href="index.html">Home</a></li>
						<li><a  href="menu.html">Menu</a></li>
						<li><a  href="blog.html">Blog</a></li>
						<li><a  href="typo.html">Codes</a></li>
						<li><a  href="events.html">Events</a></li>
						<li><a  href="contact.html">Contact</a></li>
					</ul>
				</div>
			<script>
			$('.navicon').on('click', function (e) {
			  e.preventDefault();
			  $(this).toggleClass('navicon--active');
			  $('.toggle').toggleClass('toggle--active');
			});
			</script>
		</div>-->
		<div style="float: right;">
			<button class="login-button">Log In</button>
			<button class="signup-button">Sign Up</button>
		</div>
	<div class="clearfix"></div>
	</div>


	<!-- start search-->	
		<div class="banner">
			<h4 class="animated wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="500ms" style="font-family: lato;">
			Find your favourite place</h4>
			<label></label>
			<form>
			<input type="text" name="search" class="animated wow fadeInTop" data-wow-duration="1000ms" data-wow-delay="500ms" placeholder="Enter Restaurant Location or Restaurant name">
			<button>Time to eat</button>
			</form>
			<br>
			<h4 class="animated wow fadeInTop" data-wow-duration="1000ms" data-wow-delay="500ms">
				<a class="banner_link">Sign in <span style="font-style: italic;">for more</span> </a>
			</h4>
			<!-- 
			<a class="scroll down" href="#content-down"><img src="{{ asset('frontend/cookery/images/down.png')}}" alt=""></a>
			-->
		</div>
</div>
	
@yield('content')

<!--footer-->
	<div class="footer">
		<div class="container">
			<div class="footer-head" style="height: 300px;">
				<div class="col-md-4 footer-top animated wow fadeInRight" data-wow-duration="1000ms" data-wow-delay="500ms">
					<img src="images/my_only_menu.jpg" width="250px" height="100px">
					
				</div>
				<div class="col-md-4 footer-bottom  animated wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="500ms">
					<h2>About MyOnlyMenu</h2>

					<ul class=" in">
						<li></li>
						<li><a href="index.html">Home</a></li>
						<li><a  href="menu.html">Menu</a></li>
						<li><a  href="blog.html">Blog</a></li>
						<li><a  href="events.html">Events</a></li>
						<li><a  href="contact.html">Contact</a></li>
					</ul>

				</div>
				<div class="col-md-4 footer-bottom  animated wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="500ms">
					<h2>Get Help</h2>
					
					<ul class=" in">
						<li><a href="index.html">Home</a></li>
						<li><a  href="menu.html">Menu</a></li>
						<li><a  href="blog.html">Blog</a></li>
						<li><a  href="events.html">Events</a></li>
						<li><a  href="contact.html">Contact</a></li>
					</ul>

				</div>
			<div class="clearfix"> </div>
				<hr>	
			</div>
			<p class="footer-class animated wow bounce" data-wow-duration="1000ms" data-wow-delay="500ms">&copy; 2016 Cookery . All Rights Reserved | Design by  <a href="#" target="_blank">Biniam</a> </p>
		</div>
	</div>		
	<!--//footer-->
</body>
</html>