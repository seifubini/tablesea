@extends('layouts.front')	

@section('content')

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
						<li class="nav-item"><a class="nav-link" href="{{ route('restaurants.show', $restaurant->id) }}">Menu</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ url('/my_orders', $restaurant->id)}}">My Orders</a></li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown-a" data-toggle="dropdown">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>
							Cart 
							<span class="badge badge-pill badge-danger">{{ count((array) session('cart')) }}</span>
						</a>
							<div class="dropdown-menu" aria-labelledby="dropdown-a">
								
								@php $total = 0 @endphp

                        @foreach((array) session('cart') as $id => $details)

                            @php $total += $details['price'] * $details['quantity'] @endphp

                        @endforeach

                        <div class="col-lg-6 col-sm-6 col-6 total-section text-right">

                            <p>Total: <span class="text-info">$ {{ $total }}</span></p>

                        </div>
                        <form method="POST" action="{{ route('orders.store')}}">
						@csrf

                        @if(session('cart'))

                        @foreach(session('cart') as $id => $details)

                            <div class="row cart-detail">

                                <div class="col-lg-4 col-sm-4 col-4 cart-detail-img">

                                    <img src="" />

                                </div>

                                <div class="col-lg-8 col-sm-8 col-8 cart-detail-product">
                                	<input type="text" name="user_name" value="{{ Auth::user()->name}}" hidden>
									<input type="text" name="user_id" value="{{ Auth::user()->id}}" hidden>
									<input type="text" name="restaurant_id" value="{{ $restaurant->id}}" hidden>
									<input type="text" name="restaurant_name" value="{{ $restaurant->Restaurant_name}}" hidden>
									<input type="text" name="restaurant_address" value="{{ $restaurant->Restaurant_address}}" hidden>
									<input type="text" name="order_status" value="ordered" hidden>
									<input type="text" name="item_name" value="{{ $details['name'] }}" hidden>
									<input type="text" name="item_price" value="{{ $details['price'] }}" hidden>
									<input type="text" name="amount" value="{{ $details['quantity'] }}" hidden>

                                    <p>{{ $details['name'] }}</p>

                                    <span class="price text-info"> ${{ $details['price'] }}</span> <span class="count"> Quantity:{{ $details['quantity'] }}</span> 
                                    <input type="number" name="Quantity" class="form-group" >

                                </div>

                            </div>

                        @endforeach

                    @endif
                    <div class="row">

                        <div class="col-lg-12 col-sm-12 col-12 text-center">

                            <button type="submit" class="btn btn-primary btn-block">
                            	Submit
                            </button>

                        </div>

                    </div>
                </form>
							</div>

						</li>
						<li>
							<form action="{{ route('logout') }}" method="POST" >
            @csrf
                <a href="{{ route('accounts.edit', Auth::user()->id)}}" class="btn btn-default btn-flat">Profile</a>                    
            <!-- --><button type="submit" class="btn btn-default btn-flat float-right">
            {{ __('Logout') }}</button>
          
            </form>
						</li>
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

    <!-- -->
    <div align="center">
    	<br>
    	@if($promotions == 0)
    	<div class="heading-title text-center">
    		<h1>No Promotion for Today</h1>
    	</div>

    	@else
    	<div class="heading-title text-center">
    		<h1>Promotion</h1>
    	</div>

    	<iframe frameborder="0" height="850" src="https://localhost/promotion" width="100%"></iframe>
        
        @endif

        </div>

<!--new menu -->
	<div class="menu">
		<div class="container">
			<div class="menu-top">
				<div class="col-md-4 menu-left animated wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="500ms">
					<h3>Menu</h3>
					<label><i class="glyphicon glyphicon-menu-up"></i></label>
					<span>There are many variations</span>
				</div>
				<div class="col-md-8 menu-right animated wow fadeInRight" data-wow-duration="1000ms" data-wow-delay="500ms">
					<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour , or randomised words which don't look even slightly believable.There are many variations by injected humour. There are many variations of passages of Lorem Ipsum available.There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form by injected humour , or randomised words</p>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="menu-bottom animated wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="500ms">
				<div class="col-md-4 menu-bottom1">
					<div class="btm-right">
						<a href="events.html">
							<img src="{{ asset('fronntend/cookery/images/me.jpg')}}" alt="" class="img-responsive">
							<div class="captn">
								<h4>Lorem</h4>
								<p>$20.00</p>				
							</div>
						</a>						
					</div>
				</div>
				<div class="col-md-4 menu-bottom1">
					<div class="btm-right">
						<a href="events.html">
							<img src="{{ asset('fronntend/cookery/images/me1.jpg')}}" alt="" class="img-responsive">
							<div class="captn">
								<h4>Lorem</h4>
								<p>$20.00</p>				
							</div>
						</a>						
					</div>
				</div>
				<div class="col-md-4 menu-bottom1">
					<div class="btm-right">
						<a href="events.html">
							<img src="{{ asset('fronntend/cookery/images/me2.jpg')}}" alt="" class="img-responsive">
							<div class="captn">
								<h4>Lorem</h4>
								<p>$20.00</p>				
							</div>
						</a>	
					</div>
				</div>
				<div class="clearfix"> </div>				
			</div>
			
		</div>
	</div>
<!-- new menu end -->

	<!-- Start Menu -->
	<div class="menu-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-title text-center">
						<h2>Order Menu</h2>
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting</p>
					</div>
				</div>
			</div>
			
			<div class="row inner-menu-box">
				<div class="col-1">
					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
						<a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">All</a>
						@foreach($categories as $category)
						<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">{{ $category->category_name }}</a>
						@endforeach
						<a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Lunch</</a>
						<a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Dinner</a>
					</div>
				</div>
				
				<div class="col-11">
					<div class="tab-content" id="v-pills-tabContent">
						<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
							<div class="row">
								@foreach($order_menus as $order_menu)
								<!-- menu item start here -->
								<div class="col-lg-4 col-md-6 special-grid drinks">
									<div class="gallery-single fix">
										<img src="{{ asset('images/order_menu') }}/{{ $order_menu->menu_photo}}" class="img-fluid" alt="Image">
										<div class="why-text">
										<!-- <form method="POST" action="{{ route('orders.store')}}">
											@csrf-->
											<h4>{{ $order_menu->item_name }}</h4>
											<input type="text" name="item_name" value="{{$order_menu->item_name}}" hidden>
											<p>Sed id magna vitae eros sagittis euismod.</p>
											
											<div class="pull-left">
												<h5>{{ $order_menu->item_price }}</h5>
												<input type="number" name="item_price" value="{{ $order_menu->item_price }}" hidden>
											</div>
											<div class="pull-right">
												<!-- <input type="number" name="amount">-->
												<input type="text" name="user_name" value="{{ Auth::user()->name}}" hidden>
												<input type="text" name="user_id" value="{{ Auth::user()->id}}" hidden>
												<input type="text" name="restaurant_id" value="{{ $restaurant->id}}" hidden>
												<input type="text" name="restaurant_name" value="{{ $restaurant->Restaurant_name}}" hidden>
												<input type="text" name="restaurant_address" value="{{ $restaurant->Restaurant_address}}" hidden>
												<input type="text" name="order_status" value="ordered" hidden>
												<input type="text" name="menu_photo" value="{{ $order_menu->menu_photo}}" hidden>
											</div>
											<hr>
											<br>
											<h4 class="pull-left">Total Price: </h4>
											<button class="btn btn-primary pull-right" type="submit">
												<a href="{{ route('add.to.cart', $order_menu->id) }}" role="button">
											order</a></button>
											
										<!-- </form>-->
										</div>	
											
									</div>

								</div>
								<!-- menu item end here -->

								@endforeach
								
							</div>
							
						</div>
						
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<!-- End Order Menu  -->
	<hr>
	<!-- Start Gallery -->
	<div class="gallery-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-title text-center">
						<h2>Flip Menu</h2>
						<p>Check our Flip Menu also</p>
					</div>
				</div>
			</div>
			<div class="tz-gallery">
				<div class="row">
					@foreach($flip_menus as $flip_menu)
					<div class="col-sm-12 col-md-4 col-lg-4">
						<a class="lightbox" href="{{ asset('images/menu') }}/{{ $flip_menu->menu_picture}}">
							<img class="img-fluid" src="{{ asset('images/menu') }}/{{ $flip_menu->menu_picture}}" alt="Gallery Images">
						</a>
						<div class="heading-title text-center">
						<h2>{{$flip_menu->menu_name}}</h2>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<!--<div class="contact-box">-->
		<hr>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-title text-center">
						<h2>Feedback</h2>
						<p>Write a Feedback for this Restaurant </p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<form id="contact_Form" method="POST" action="{{ route('feedbacks.store') }}" >
						@csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="exampleInputEmail1">Feedback Subject *</label>
									<input type="text" class="form-control" placeholder="Feedback Subject" id="name" name="feedback_subject" required data-error="Please enter Subject Here" >
									<div class="help-block with-errors"></div>
								</div>                                 
							</div>
							
							<input type="hidden" value="{{ Auth::user()->email }}" name="user_email">
							<input type="hidden" value="{{ Auth::user()->name }}" name="user_name">
							<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
							<input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
							<div class="col-md-12">
								<div class="form-group"> 
									<label for="exampleInputEmail1">Feedback Message *</label>
									<textarea class="form-control" name="feedback_message" placeholder="Your Message" rows="4" data-error="Write your message" required></textarea>
									<div class="help-block with-errors"></div>
								</div>
								<div class="submit-button">
									<button type="submit" class="btn btn-primary">Send Feedback</button>
									<div class="clearfix"></div> 
								</div>
							</div>
						</div>            
					</form>
				</div>
			</div>
		</div>
		<br>
		<hr>
	
		<div class="container">
			<div class="row">
				<div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity" style="background-color: #F4F6F9;">

                  	
				<!-- Feedback -->
				@foreach($feedbacks as $feedback)
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{ asset('dist/img/user1-128x128.jpg')}}" alt="user image">
                        <span class="username">
                          <p> {{$feedback->user_name}}</p>
                          
                        </span>
                        <span class="description">Shared publicly - {{ date_format(date_create($feedback->created_at), 'jS M Y')}}</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                      	{{$feedback->feedback_message}}
                      </p>
                    </div>
                    @endforeach
                    <!-- /.feedback -->
                </div>
                <br>
                <!-- post -->
                
            </div>
            </div>
			</div>
		</div>
		<br>
		<br>
	
@endsection

@section('scripts')

<script type="text/javascript">


    $(".update-cart").change(function (e) {

        e.preventDefault();

  

        var ele = $(this);
  

        $.ajax({

            url: '{{ route('update.cart') }}',

            method: "patch",

            data: {

                _token: '{{ csrf_token() }}', 

                id: ele.parents("tr").attr("data-id"), 

                quantity: ele.parents("tr").find(".quantity").val()

            },

            success: function (response) {

               window.location.reload();

            }

        });

    });

  

    $(".remove-from-cart").click(function (e) {

        e.preventDefault();

  

        var ele = $(this);

  

        if(confirm("Are you sure want to remove?")) {

            $.ajax({

                url: '{{ route('remove.from.cart') }}',

                method: "DELETE",

                data: {

                    _token: '{{ csrf_token() }}', 

                    id: ele.parents("tr").attr("data-id")

                },

                success: function (response) {

                    window.location.reload();

                }

            });

        }

    });

</script>

@endsection