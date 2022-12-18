@extends('layouts.front')	

<!-- Start header -->
	<header class="top-navbar">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="#">
					<img src="{{ asset('images/restaurant/logo') }}/{{ $restaurant->Restaurant_logo}}" height="100px" width="150px" alt="" />
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbars-rs-food">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item"><a class="nav-link" href="{{ url('/home')}}">Home</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Menu</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>
	<!-- End header -->


<!-- Start All Pages -->
	<div class="all-page-title page-breadcrumb" style="background-image: url('{{ asset('images/restaurant') }}/{{ $restaurant->Restaurant_photo}}');">
		<div class="container text-center">
			<div class="row">
				<div class="col-lg-12">
					<h1>{{ $restaurant->Restaurant_name }}</h1>
				</div>
			</div>
		</div>
	</div>
	<!-- End All Pages -->


	<!-- Start Gallery -->
	<div class="gallery-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-title text-center">
						<h2>Flip Menu</h2>
						<p>Go through the listed menus to order a service.</p>
					</div>
				</div>
			</div>
			<div class="tz-gallery">
				<div class="row">
					@foreach($menus as $menu)
					<div class="col-sm-6 col-md-6 col-lg-6">
						<a class="lightbox" href="{{ asset('images/menu') }}/{{ $menu->menu_picture}}" height="120px" width="100px">
							<img class="img-fluid" src="{{ asset('images/menu') }}/{{ $menu->menu_picture}}" alt="Gallery Images">
						</a>
					</div>
					@endforeach 
					
				</div>
			</div>
		</div>
	</div>
	<!-- End Gallery -->