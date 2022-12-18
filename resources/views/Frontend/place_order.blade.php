@extends('layouts.main')

@section('content')

<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="font-family: Lato, sans-serif; font-weight: bolder; padding-left: 2%;">
              {{ $restaurant->Restaurant_name}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

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

<div class="container-fluid">
        <div class="row">
        <div class="col-md-5">
              <div class="card-header">
                
                <img src="{{ asset('images/restaurant')}}/{{$restaurant->Restaurant_photo}}" class="img-fluid pad" width=100%; height=320px; style="position: 50% 50%; height: 320px; display: inline-block; object-position: 50% 50%; object-fit: cover; padding-bottom: 2%;">
                <p><i class="fa fa-map-marker" style="padding-right: 2%;"></i>  {{ $restaurant->Restaurant_address }}</p>
              </div>
              <br>
              <h1 class="card-title" style="font-family: Lato, sans-serif; font-weight: bolder; padding-left: 2%; float: left;">
                  Your Items
              </h1>
              <span style="padding-right: 5%;">
              <a role="button" style="float: right; font-family: Lato, sans-serif; font-weight: bold; background-color: #EEEEEE; border-radius: 25px; padding: 1%;">
              +  Add items</a>
            </span>
         <div class="card-body">
          
          <br>
         	@php $total = 0 @endphp

        @if(session('cart'))

            @foreach(session('cart') as $id => $details)

                @php $total += $details['price'] * $details['quantity'] @endphp
         	<div class="row">
         		<div class="col-lg-6" style="font-family: Lato, sans-serif; font-weight: bold; float: left;">
              <p>{{$details['quantity']}}  {{$details['name']}}</p>
         		</div>
         		<div class="col-lg-6" style="font-family: Lato, sans-serif; float: right; padding-left:40%;">
		         	 ${{ $details['price'] * $details['quantity'] }}
         		</div>
         	</div>
         	<hr>
         	@endforeach
              @endif
         </div>
         
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-4">
        	<!-- general form elements -->
            <div class="card card-default">
              <!-- /.card-header -->
              <div class="card-header">
                <h1 class="card-title" style="font-family: Lato, sans-serif; font-weight: bold;">Select Order Type</h1>
              </div>
              <!-- form start -->
              <form method="POST" action="{{ route('checkout') }}">
                @csrf
                <div class="card-body">
                        
                  <div class="form-group">
                    <div class="row">
                      <div class="custom-control custom-radio" style="padding-right: 5%;">
                          <input onclick="javascript:OrderType();" class="custom-control-input custom-control-input-black" type="radio" id="customRadio4" value="order_inside" name="customRadio2">
                          <label for="customRadio4" class="custom-control-label">Order Inside</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input onclick="javascript:OrderType();" class="custom-control-input custom-control-input-black" type="radio" id="customRadio5" value="order_takeaway" name="customRadio2">
                          <label for="customRadio5" class="custom-control-label">Order Take Away</label>
                        </div>
                    </div>
                  </div>
                  <div class="form-group" id="table_number" style="display: none;">
                    <label for="exampleInputPassword1">Table Number</label>
                    <input type="text" class="form-control" name="table_number" id="exampleInputName1" placeholder="Table Number">
                    <br>
                  </div>
                  <div class="form-group" id="location" style="display: none;">
                    <label for="exampleInputPassword1">Location</label>
                    <input type="text" class="form-control" name="location" id="exampleInputName1" placeholder="Location">
                    <br>
                  </div>

                    <input type="hidden" value="{{Auth::user()->name}}" name="user_name">
                    <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                    <input type="hidden" value="{{ $rest_id}}" name="restaurant_id">
                            
                  @php $total = 0 @endphp

                  @if(session('cart'))

                      @foreach(session('cart') as $id => $details)

                          @php $total += $details['price'] * $details['quantity'] @endphp

                            <div class="row" style="padding-bottom: 1%;">
                            <div class="col-sm-6" style="float: left; font-weight: bold; font-family: Lato, sans-serif; color: #000;">
                              <h5>
                              Subtotal
                            </h5>
                            </div>
                            <div class="col-sm-6" style="float: right; font-weight: bold; padding-left: 35%; font-family: Lato, sans-serif; color: #000;">
                              <h5>
                              ${{ $details['price'] * $details['quantity'] }}
                            </h5>
                            </div>
                          </div>
                          <hr>
                  @endforeach

                  @endif
                  <div class="row">
                    <p><hr></p>
                    <div class="col-sm-6" style="float: left; font-weight: bold; font-family: Lato, sans-serif; color: #000;">
                      <h3>
                      Total
                    </h3>
                    </div>
                    <div class="col-sm-6" style="float: right; font-weight: bold; padding-left: 35%; font-family: Lato, sans-serif; color: #000;">
                      <h3>
                     $ {{$total}}
                    </h3>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" disabled class="btn btn-block" id="place_order" style="background-color: #000; color: #fff; font-family: Lato, sans-serif;">
                  Place Order
                  </button>
                </div>
              </form>
            </div>
            <!-- /.card -->
        </div>
        </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
  function OrderType() {
    if (document.getElementById('customRadio4').checked) 
    {
      document.getElementById('table_number').style.display = 'block';
      document.getElementById('location').style.display = 'none';
      document.getElementById("place_order").disabled = false;
    }
    if (document.getElementById('customRadio5').checked) 
    {
      document.getElementById('location').style.display = 'block';
      document.getElementById('table_number').style.display = 'none';
      document.getElementById("place_order").disabled = false;
    }
  }
</script>
@endsection