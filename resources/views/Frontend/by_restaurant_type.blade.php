@extends('layouts.main')

@section('content')

    <!-- Main content -->
    <div class="content" style="margin-left: 2%;">
    	<div class="row">
    		
    		<div class="col-lg-2">
    			<h1 style="font-family: Lato, Sans-serif; font-weight: bolder;">{{$restaurant_type}}</h1>
    			&nbsp

    			<!-- checkbox -->
                      <div class="form-group">
							<label class="container" style="font-family: myriad pro; font-weight: normal;">Picked for you (default)
							  <input type="radio" checked="checked" name="radio" style="color: #5FC769;">
							  <span class="checkmark"></span>
							</label>
							<label class="container" style="font-family: myriad pro; font-weight: normal;">Rating
							  <input type="radio" name="radio">
							  <span class="checkmark"></span>
							</label>
							<label class="container" style="font-family: myriad pro; font-weight: normal;">Most Popular
							  <input type="radio" name="radio">
							  <span class="checkmark"></span>
							</label>
							
							<h5 style="padding-left: 2%; font-family: Lato, Sans-serif;">Price Range</h5>

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
    		    
	    			<h1 style="font-family: Lato, Sans-serif; font-weight: bolder; font-size: 50px;"></h1>
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


</script>