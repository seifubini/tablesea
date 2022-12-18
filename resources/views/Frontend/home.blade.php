 @extends('layouts.home')
 @section('content')

<!--<div class="container">
 What do you want to eat Today 
<div class="container" id="content-down">-->
	<div class="content-top-top">
		<div class="container">
			<!-- <div class="col-md-12 blog-header">
		<div class=" blog-head">-->
				<div class="col-md-12 burger-background content-left animated wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="500ms">
					<br>
				<div class="col-md-8">
					<h3 style="color: #fff; font-family: lato;">What do you want to <br> have today?</h3>	
					
					<br>
					<a href="{{ url('/location')}}">
					<button class="btn btn-rounded btn-default options-button">
					See options
					</button>
					</a>
					<br>
					
				</div>
				<div class="col-md-4 content-right burger-image animated wow fadeInRight image-responsive" data-wow-duration="1000ms" data-wow-delay="500ms">
					<img src="{{ asset('images/burger_pic.PNG')}}" class="img-responsive" height="200px" width="300px" alt="" style="float: right;">
				</div>
				</div>
				<!-- <div class="col-md-4 content-right animated wow fadeInRight" data-wow-duration="1000ms" data-wow-delay="500ms">
					
				</div>-->
				
			</div>
		</div>
			<!-- 
		</div>
	</div>
</div>-->

<div class="col-md-12 blog-header">
		<div class=" blog-head">
			<div class="col-md-4 content-left animated wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="500ms">
					<a class="featured-link" href="#">Featured Restaurants in your area</a>
					
				</div>
				<div class="col-md-8 restaurant-content-right animated wow fadeInRight" data-wow-duration="1000ms" data-wow-delay="500ms">
					<a class="see-more-link" href="#">see more options</a>
				</div>
				<div class="clearfix"> </div>
				<br>
			@foreach($restaurants as $restaurant)
			<div class="col-md-3 blog-top">
				<div class="blog-in">
					<a href="single.html">
						<img class="img-responsive" style="border-radius: 10px;" src="{{ asset('images/restaurant')}}/{{$restaurant->Restaurant_photo}}" alt=" " height="100%"></a>
						<div style="float: left;">
							<a href="{{ route('restaurants.show', $restaurant->id) }}" style="font-family: lato black; color: #000;">
							{{$restaurant->Restaurant_name}}
							<br></a>
							<span><i class="glyphicon glyphicon-comment" style="float: left;"> </i>5 Comment</span>
							<br>
							<a href="#" style="color: #000; text-decoration: underline; float: left; font-family: lato;"> see more options </a>
						    </div>
							<div style="float: right;">
								<button style="background-color: #000; border-radius: 5px; margin-top: 10%;">
									<a href="#" style="color: #fff; font-family: lato; font-weight: bold;">Order<br>now</a>
								</button>
							</div>
					<div class="blog-grid">
					    
						<div class="date">
					        
							<div class="clearfix"> </div>
						</div>
					</div>					
				</div>
			</div>
			@endforeach
			<div class="clearfix"> </div>
		</div>
		</div>
</div>


<div class="col-md-12 blog-header">
		<div class=" blog-head">
			<div class='pics_in_a_row'>
			
			<div class="col-md-4 blog-top">
				<div class="blog-in img1">
					
						<img class="img-responsive" src="{{ asset('images/create_account_croped.jpg')}}" style="border-radius: 10px;">
					
						<div style="float: left; font-family: myriad pro bold; font-weight: 900px; font-size: 25px;">
								<h1>There is more options...</h1>
							<br>
							<a href="#" style="color: #000; text-decoration: underline; font-family: lato; float: left;">
								Create an account and access<br> exclusive deals
							</a>
						</div>
										
				</div>
				
			</div>
			<div class="col-md-4 blog-top">
				<div class="blog-in img2">
					
						<div class="content-item">
							<img class="img-responsive" src="{{ asset('images/own_restaurant_croped.jpg')}}" style="border-radius: 10px;">	
							</div>
							<div style="float: left; font-family: lato black; font-weight: bold;">
								<h2>Own a restaurant?</h2>
							<br>
							<a href="#" style="color: #000; text-decoration: underline; font-family: lato; float: left;">
								Create an account and access features<br> that will increase your revenue
							</a>
						</div>
										
				</div>
				<br>
			</div>
			<div class="col-md-4 blog-top">
				<div class="blog-in img3">
					
						<div class="content-item">
							<img class="img-responsive" src="{{ asset('images/play_and_win_croped.jpg')}}" style="border-radius: 10px;">	
							</div>
							<div style="float: left; font-family: lato black; font-weight: bold;">
								<h2>Play and win</h2>
							<br>
							<a href="#" style="color: #000; text-decoration: underline; font-family: lato; float: left;">
								Get a chance to win free stuff<br> and deals
							</a>
						</div>
										
				</div>
				<br>
			</div>
			
			<div class="clearfix"> </div>
		</div>
	    </div>
		</div>
		<br>

		<div class="col-md-12 blog-header">
		<div class=" blog-head" style="border-radius: 10px; height: 250px;">
			
			<h3 style="float: left; color: #000; font-family: lato black; font-weight: bold;">Places near me</h3> 
			<a href="#" style="color: #000; text-decoration: underline; float: right; font-family: lato;"> View all</a>
			<div class="map animated wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="500ms">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d37494223.23909492!2d103!3d55!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x453c569a896724fb%3A0x1409fdf86611f613!2sRussia!5e0!3m2!1sen!2sin!4v1415776049771"></iframe>

			</div>
			
			<div class="clearfix"> </div>
		</div>
		</div>

</div>

<br>
<br>
	<div class="restaurants">
		<div class="container">
			
			<div class="row contact-map">

			
			
			</div>

        </div>
    </div>
<br>
<br>


@endsection