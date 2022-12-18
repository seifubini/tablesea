@extends('layouts.techbiz_restaurants')	

@section('content')

<!-- Start header -->
	<header class="top-navbar">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="#">
					<img src="{{ asset('images/my_only_menu.jpg')}}" width="200px" height="54px" alt="" />
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbars-rs-food">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item"><a class="nav-link" href="{{ url('/home')}}">Home</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('restaurants.show', $rest_id) }}">Menu</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ url('/my_orders', $rest_id)}}">My Orders</a></li>
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

                                </div>

                                <div class="col-lg-8 col-sm-8 col-8 cart-detail-product">
                                	<input type="text" name="user_name" value="{{ Auth::user()->name}}" hidden>
									<input type="text" name="user_id" value="{{ Auth::user()->id}}" hidden>
									<input type="text" name="restaurant_id" value="{{ $rest_id}}" hidden>
									<input type="text" name="restaurant_name" value="{{ $rest_name}}" hidden>
									
									<input type="text" name="order_status" value="ordered" hidden>
									<input type="text" name="item_name" value="{{ $details['name'] }}" hidden>
									<input type="text" name="item_price" value="{{ $details['price'] }}" hidden>
									<input type="text" name="amount" value="{{ $details['quantity'] }}" hidden>

                                    <p>{{ $details['name'] }}</p>

                                    <span class="price text-info"> ${{ $details['price'] }}</span> <span class="count"> Quantity:{{ $details['quantity'] }}</span> 

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
					</ul>
				</div>
			</div>
		</nav>
	</header>
	<!-- End header -->
	
	<!-- Start All Pages -->
	<div class="all-page-title page-breadcrumb" 
	style="background-image: url('{{ asset('images/category')}}/{{$cat_image}}');">
		<div class="container text-center">
			<div class="row">
				<div class="col-lg-12">
					<h1>{{ $rest_name}}</h1>
					
				</div>
			</div>
		</div>
	</div>
	<!-- End All Pages -->
	
	<!-- Start Order Menu -->
	<div class="menu-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-title text-center">
						<h2> Our {{ $cat_name}} Menu</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="special-menu text-center">
						<div class="button-group filter-button-group">
							<button class="active" data-filter="*">All</button>
							@foreach($sub_categories as $subcategory)
							<button data-filter=".{{$subcategory->sub_category_name}}">{{ $subcategory->sub_category_name }}</button>
							@endforeach
						</div>
					</div>
				</div>
			</div>
				
			<div class="row special-list">
				@foreach($order_menus as $order_menu)
				<div class="col-lg-4 col-md-6 special-grid {{$order_menu->sub_category_name}}">
					<div class="gallery-single fix">
						<!-- Trigger/Open The Modal -->
						<img src="{{ asset('images/order_menu') }}/{{ $order_menu->menu_photo}}" width="350px" height="230px" alt="Image">
						
						<div class="why-text">
							<h4>{{ $order_menu->item_name }}</h4>
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg{{$order_menu->id}}">
			                  Add Order
			                </button>
							<h5> ${{ $order_menu->item_price }}</h5>
						</div>
					
							<!-- Order Modal -->
					<div class="modal fade" id="modal-lg{{$order_menu->id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content item">
                            <div class="modal-header">
                              <h4 class="modal-title"> {{ $order_menu->sub_category_name }} </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
				            <div class="modal-body">
				            <img src="{{ asset('images/order_menu') }}/{{ $order_menu->menu_photo}}" width="90%" height="250px" alt="Image">

				              <p>One fine body&hellip;</p>

				              <h3 class="product-price" id="product-price">
				              	$ {{ $order_menu->item_price }}
				              </h3>

				            </div>
				            <div class="modal-footer justify-content-between totals-item">
				            	<!--<label for="pass-quantity" class="pass-quantity">Quantity</label>
				            	<input class="qty" type="number" value="0" min="0" id="quantity">
				               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
				              <button type="button" class="btn btn-primary cart-total totals-item">
				              	<span class="totals-value cart-total" id="cart-subtotal"> 
				              		<a href="{{ route('add.to.cart', $order_menu->id) }}" role="button" style="color: #fff">
											Add to Cart</a>
				              	
				              	</span>
				              </button>
				            </div>
				          </div>
				          <!-- /.modal-content -->
				        </div>
				        <!-- /.modal-dialog -->
				      </div>
				      <!-- /.modal -->
					</div>

				</div>
				@endforeach

				
			</div>
		</div>
	</div>
	<!-- End Order Menu -->
@endsection

@section('scripts')

<script type="text/javascript">
function calculateTotal()
{
    let item_price={}

    item_price.sugar = ($("#quantity").val() * $("#product-price").val() )
    $("#cart-subtotal").val(item_price.sugar);

    let total = item_price.sugar;

    $("#cart-subtotal").text(total);
}
$(function()
 {
    $(".qty").on("change keyup",calculateTotal);
});

</script>

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
