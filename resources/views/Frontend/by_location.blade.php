@extends('layouts.main')

@section('content')

<!-- Content Header (Page header) -->
    <div id="content-slider-1" class="content-header">
      <div class="row" style="margin-left: 1%;">
        <div class="col-lg-12">
              
        	<ol class="breadcrumb">
                <li class="breadcrumb-img" style="padding-right: 3%;">
                    <figure class="figure">
                        <img src="{{ asset('images/slider_images/IMG_3773.PNG')}}" height="70px" width="70px" class="figure-img img-fluid rounded" alt="">
                    <figcaption class="figure-caption text-black" style="padding-left: 25%";>Deals</figcaption>
                    </figure>
                </li>
                @foreach($restaurant_types as $restaurant_type)
                <!-- Carousel items -->
                <li class="breadcrumb-img" style="padding-right: 3%;">
                    <a href="{{ url('by_type', $restaurant_type->Restaurant_Type_Name)}}">
        			<figure class="figure">
        				<img src="{{ asset('images/Restaurant_Type')}}/{{ $restaurant_type->Restaurant_Type_Photo}}" height="70px" width="70px" class="figure-img img-fluid rounded" alt="">
        				<figcaption class="figure-caption text-black" style="padding-left: 25%;">
                            {{ $restaurant_type->Restaurant_Type_Name}}</figcaption>
        		    </figure>
                    </a>
        		</li>
                @endforeach
        	</ol>
                
        </div><!-- /.row -->
        <hr>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<!-- Slider -->

<!-- End slider -->    

<!-- 
    
        <div class="col-lg-1"></div>
        <div class="col-lg-10" style="background-color: #5AC363; border-radius: 10px; margin-left: 5%; margin-right: 5%;">
          <div class="col-lg-5" style="padding-left: 7%; padding-bottom: 2%; padding-top: 2%; float: left;">
            <h1 style="color: #FFFFFF; font-weight: bolder; font-style: normal; line-height: 1em; letter-spacing: -1.1px; font-family: Lato, Sans-serif; font-size: 45px; padding-bottom: 2%;">
              What do you want to <br> have today?
            </h1>
            <button style="background-color: #fff; border-radius: 15px; font-family: Lato, Sans-serif; padding-top: 10px; padding-bottom: 10px; padding-left: 4%; padding-right: 4%; color: #000; border: none;">
              <strong>SEE OPTIONS</strong>
            </button>
        </div>
        <div class="col-lg-5" style="float: right; overflow: visible;">
          <img src="{{ asset('frontend/images/burger.png')}}" class="img-responsive img-fluid" width="384" height="291">
        </div>
      
    </div>-->

    <!-- Main content -->
    <div class="content container-fluid" style="margin-left: 2%;">
    	<div class="row">
    		
    		<div class="col-lg-2">
    			<h1 style="font-family: Lato, Sans-serif; font-weight: bolder;">All Stores</h1>
    			&nbsp

    			<!-- checkbox -->
                      <div class="form-group">
                        <div class="form-group">
							<label class="container" style="font-family: myriad pro; font-weight: normal;">
                                <span style="font-family: Lato, Sans-serif;">Picked for you (default)</span>
							  <input type="radio" checked="checked" name="radio" class="rdio-primary">
							  <span class="checkmark" class="rdio-primary"></span>
							</label>
                        </div>
                        <div class="form-group" id="sort_by_rating">
							<label class="container" style="font-family: myriad pro; font-weight: normal;">
                                <span style="font-family: Lato, Sans-serif;">Rating</span>
							  <input type="radio" name="rating">
							  <span class="checkmark" class="rdio-primary"></span>
							</label>
                        </div>
                        <div class="form-group" id="sort_by_popularity">
							<label class="container" style="font-family: myriad pro; font-weight: normal;">
                                <span style="font-family: Lato, Sans-serif;">Most Popular</span>
							  <input type="radio" name="popularity">
							  <span class="checkmark" class="rdio-primary"></span>
							</label>
						</div>	
							<span style="padding-left: 2%; font-family: Lato, Sans-serif;">Price Range</span>

				                <div class="row" style="padding-left: 2%;">
				                  <div class="col-4">
				                    <input type="number" class="form-control" placeholder="min">
				                  </div>
				                  <div class="col-4">
				                    <input type="number" class="form-control" placeholder="max">
				                  </div>
				                </div>
                                <div class="row" style="padding-left: 1%;">
                                
                                &nbsp
				                <div class="col-8" style="width: 100%;">
				                	<br>
				                <button class="btn btn-default btn-block float-right" type="submit" name="submit" 
                                style="background-color: #5FC769; border: none; width: 100%;">
				                    	Sort
				                    </button>
				                  </div>
				                </div>
                      </div>
                      &nbsp
    		</div>
    		<div style="padding-left: 3%; width: 100%;" class="col-lg-10">
    		    
	    			<h1 style="font-family: Lato, Sans-serif; font-weight: bolder; font-size: 50px;">Popular Near You</h1>
	    			&nbsp
	    		<div class="row">
	    			@foreach($restaurants as $restaurant)
	    			<div class="col-lg-3" style="padding-right: 2%;">
		    		<div class="position-relative" style="min-height: 180px;">
                        <div class="deals-ribbon" style="float: left;">
                          Deals of the Day
                        </div>
		    			<a href="{{ url('show_restaurants', $restaurant->id) }}" style="font-family: lato black; color: #000;">
                      <img src="{{ asset('images/restaurant')}}/{{$restaurant->Restaurant_photo}}" class="img-responsive" alt="Photo 3" height="200px" width="345px" style="border-radius: 5px;">
                      <div class="" style="float: left;">
                      	<a href="#">
                        </a>
                      </div>
                      <div class="left-text">
                      	<p style="font-family: Lato, Sans-serif; font-weight: bold; float: left; font-size: 30px; padding-top: 2%;"> {{$restaurant->Restaurant_name}} </p>
                      	
                      </div>
                      <div class="right-text" style="font-family: Lato, Sans-serif; font-size: 20px; padding-top: 2%;">
                      	<p style="float: right; background-color: #EBEDEF; border-radius: 50px; padding: 2%;">4.5</p>
                      </div>
                        </a>
                    </div>
		          </div>
		          @endforeach
		          
	            </div>
    		</div>
    		
    	</div>

      
</div>
<!-- /.content -->


@endsection


<script type="text/javascript">
$(document).ready(function(){

    $(".rdio-primary").change(function(){

        var rating = $("input[type='radio']:checked").val();
        
        console.log(rating);

        alert(rating);
        
        
    });

});
</script>