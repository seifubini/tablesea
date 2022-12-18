@extends('layouts.main')

@section('content')
<div class="row">
<div style="background-color: #dee78b; width: 100%; height: 70px; font-family: Lato, sans serif; align-content: center; text-align: center;">
        <h3 class="text-center" style="padding-top: 0.9%; font-family: Lato, sans serif; font-weight: bold; color: #565656;">Deals of the day</h3>
</div>
</div>
<div class="row">
<!-- Start All Pages -->
  <div class="banner" style="background-image: linear-gradient(to bottom, rgba(21, 21, 21, 0.08) ,rgba(21, 21, 21, 0.96))
  ,url(' {{ asset('images/restaurant')}}/{{$restaurant->Restaurant_photo}} '); background-size:cover; display: block; background-position: 50% 50%; background-color: rgba(0,0,0,0.5); width: 100%; height: 320px; padding-bottom: 5%;">
    <div class="row">
        <div class="col-lg-8" style="padding-top: 180px; padding-left: 3%; color: #fff;">
          <h1 style="font-family: Lato, sans serif; font-weight: bolder; font-size: 60px;">{{ $restaurant->Restaurant_name }}</h1>

        <div class="bottom-text">
          <p style="font-family: Lato, sans serif; font-weight: bold; font-size: 20px;"> <i class="far fa-location"></i>&nbsp;{{ $restaurant->Restaurant_address}}&nbsp; {{ $restaurant->Restaurant_hours}} - &nbsp;<i class="far fa-comments"></i> &nbsp;{{ $feedback_count}}</p>
        </div>

        </div>
        <div class="col-lg-4" style="padding-right: 3%; float: right; padding-top: 225px; padding-left: 13%;">
          <button style="background-color: #9CC11A; font-weight: bold; width: 150px; border-style: none; width: 150px; border-radius: 30px; color: #fff; padding: 4%; margin-right: 5%;">
            Order menu
          </button>
          <button style="background-color: #fff; width: 150px; font-weight: bold; border-style: none; border-radius: 30px; color: #000; padding: 4%;">
            Page menu
          </button>
        </div>
      </div>
    <div class="container text-center">

    </div>
  </div>
</div>
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

  <!-- End All Pages -->

<!-- Content Header (Page header)
    <div class="content-header">
      <div class="row img-container position-relative" style="min-height: 180px;">
      	<img src="{{ asset('images/restaurant') }}/{{ $restaurant->Restaurant_photo}}" class="img-responsive" style="border-radius: 10px;" height="300px" width="100%">
      	<div class="top-left">
        Deals of the day</div>
      	<div class="">
      	<h2 style="font-family: Lato;">
      		{{ $restaurant->Restaurant_name}}
      	</h2>
        </div>
        <div class="bottom-text">
          <p> <i class="far fa-location"></i>{{ $restaurant->Restaurant_address}}&nbsp; {{ $restaurant->Restaurant_hours}} - &nbsp;<i class="far fa-comments"></i> &nbsp;{{ $feedback_count}}</p>
        </div>
      	</div>
    </div>-->
    <div class="content">
      <div class="row">
        <div class="col-lg-8" style="padding-left: 3%; padding-top: 1%;">
          <p>
            {{$restaurant->Restaurant_description}}
          </p>
          <p>
            {{$restaurant->Restaurant_address}}
          </p>
        </div>
        <!-- -->
        <div class="col-lg-4" style="padding-right: 1%; float: right; padding-top: 1%; padding-left: 12%;">
          <button style="background-color: #565656; font-family: Lato, sans serif; font-weight: bold; border-style: none; margin-right: 5%; border-radius: 30px; color: #fff; padding: 4%; width: 150px;">
            Play and Win
          </button>
          <button style="background-color: #9CC11A; width: 170px; font-family: Lato, sans serif; font-weight: bold; border-style: none; border-radius: 30px; color: #fff; padding: 4%;">
            <a href="{{ url('/reservations')}}" style="color: #fff;" target="_blank">
            Table Reservation
            </a>
          </button>
        </div>
      </div>
    </div>
    <div class="content">
    	<div class="row">
    		<div class="col-lg-12" style="padding-left: 3%; padding-right: 3%;">
    		<div role="tabpanel">
      <ul class="nav nav-tabs" role="tablist" style="padding-bottom: 9px; padding-top: 40px; margin-bottom: 2%;">
            <li role="presentation" class="active" style="padding-right: 2%; font-family: lato black; color: #000;">
              <a href="#home" class="active" aria-controls="home" role="tab" data-filter="*" data-toggle="tab" style="font-family: Lato, sans serif; font-weight: bold; font-size: 20px; color: #000;">
              Staff Picks</a>
            </li>
          @foreach ($categories as $item)
            <li role="presentation" class="{{ $item->id == 1 ? 'active' : '' }}" style="padding-right: 2%; font-family: lato black; color: #000;">
              <a href="#home{{ $item->id }}" aria-controls="home" role="tab" data-toggle="tab" style="font-family: Lato, sans serif; font-weight: bold; font-size: 20px; color: #000;">
                {{ $item->category_name }}</a>
            </li>
          @endforeach
      </ul>

    </div>
    		</div>
    	</div>

    </div>

    <!-- Main content -->
    <div class="content">
    	<div class="row">
        <div class="col-lg-12" style="padding-left: 3%; padding-right: 1%;">
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="home" class="active">
              <div class="row">

                @foreach($order_menus as $order_menu)
                <div class="col-lg-4">
                  <div class="card">
                    <a href="javascript:void(0);" class="open_modal" data-href="{{ url('set_modal/'.$order_menu->id) }}" data-toggle="modal" data-target="#modal-lg" style="font-family: lato black; color: #000;">
                    <!-- <a href="{{ route('add.to.cart', $order_menu->id)}}"  style="font-family: lato black; color: #000;">-->
                    <div class="card-body">
                      <div style="float: left; width: 60%;">
                        <h5 class="card-title" style="font-family: Lato, sans serif; padding-bottom: 3%; font-weight: bold;">{{ $order_menu->item_name}}</h5>
                        <br>
                      <p class="card-text show-read-more" style="font-family: Lato, sans serif; padding-bottom: 3%;">
                        {{$order_menu->menu_description}}</p>
                        <br>
                        <p style="font-family: Lato, sans serif; padding-bottom: 3%; font-size: 20px; font-weight: bold;">
                        {{ $order_menu->item_price}}
                      </p>
                      </div>
                      <div style="float: right; width: 40%;">
                        <img class="card-img-right" src="{{ asset('images/order_menu')}}/{{$order_menu->menu_photo}}" class="img-responsive" alt="Photo 3" height="150px" width="100%">
                      </div>

                    </div>
                  </a>
                  </div>

              </div>
              @endforeach

              </div>
          </div>

       @foreach ($product as $item)
            <div role="tabpanel" class="tab-pane {{ $item->id == 1 ? 'active' : '' }}" id="home{{ $item->id }}" class="active">
              <div class="row">
                @foreach ($item->order_menu as $element)
                <div class="col-lg-4">
                  <div class="card">

                  <a href="javascript:void(0);" class="open_modal" data-href="{{ url('set_modal/'.$element->id) }}" data-toggle="modal" data-target="#modal-lg" style="font-family: lato black; color: #000;">
                    <!-- <a href="{{ route('add.to.cart', $order_menu->id)}}" style="font-family: lato black; color: #000;"></a>-->
                    <div class="card-body">
                      <div style="float: left; width: 60%;">
                        <h5 class="card-title" style="font-family: lato; font-weight: bold;">
                          {{$element->item_name}}
                        </h5>
                      <p class="card-text show-read-more">
                        {{$element->menu_description}}</p>

                        <p style="padding-bottom: 30%;">
                        {{ $element->item_price}}

                      </p>
                      </div>
                      <div style="float: right; width: 40%;">
                        <img class="card-img-right" src="{{ asset('images/order_menu')}}/{{$element->menu_photo}}" class="img-responsive" alt="Photo 3" height="150px" width="100%">
                      </div>
                    </div>
                  </a>
                  </div>
                </div>

                <br>

                @endforeach
              </div>
            </div>

       @endforeach

      </div>
      </div>

    	</div>


    </div>
    <!-- /.content -->

    <!-- Order Modal -->
                  <div class="modal fade" id="modal-lg" aria-labelledby="insertModalLabel">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content item">
                            <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <div class="modal-body">
                            <img id="menu_photo" src="" width="100%" height="250px" alt="Image">
                            <br>
                            <h1 id="item_name" style="padding-top: 10px; padding-bottom: 10px; font-family: Lato, sans serif; font-weight: bolder;">
                            </h1>

                              <p id="item_description" style="font-family: 'Lato', sans serif">&hellip;</p>

                              <h3 id="item_price" style="font-family: 'Lato', sans serif">
                                $

                              </h3>

                            </div>
                            <div class="modal-footer mr-auto totals-item" style="width: 100%;">
                              <form  action="{{ route('populate_cart') }}" method="POST" id="add_to_cart_link">
                                @csrf

                                <div class="row" style="display: inline-block; width: 50%">
                                    <div class="col-lg-10">
                                      <span id="sub" class="minus_button btn btn-default mr-auto btn-round btn-sm" style="font-family: 'Lato', sans serif; float: left; background-color: #000;">
                                          <i class="fa fa-minus" style="color: #fff;"></i>
                                      </span>
                                      <input type="number" name="quantity" id="quantity" class="quantity" size="1" value="1" min="1" style="border: none; align-content: center; font-family: 'Lato', sans serif;" />
                                      <span id="add" class="plus_button btn btn-default mr-auto btn-round btn-sm" style="font-family: 'Lato', sans serif; float: left; background-color: #000;">
                                          <i class="fa fa-plus" style="color: #fff"></i>
                                      </span>

                                    </div>
                                </div>
                                <!--<label for="pass-quantity" class="pass-quantity">Quantity</label>
                                <input class="qty" type="number" value="0" min="0" id="quantity">
                               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->

                                <input type="hidden" id="form_id" name="id" value="0" >
                              <button type="submit" value="submit"  class="btn btn-default btn-block mr-auto update-cart"
                              style="float: right; background-color: #000; color: #fff; width: 45%; font-family: 'Lato', sans serif;">

                                <p id="form_button">Add to Cart $</p>
                                <p class="text-right" id="total">  </p>

                              </button>
                              </form>
                            </div>

                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- End modal -->

@php $total = 0 @endphp

        @if(session('cart'))

            @foreach(session('cart') as $id => $details)

                @php $total += $details['price'] * $details['quantity'] @endphp

              @endforeach
              @endif

              @php $total = 0 @endphp

        @if(session('cart'))

            @foreach(session('cart') as $id => $details)

                @php $total += $details['price'] * $details['quantity'] @endphp

                <!-- Order Modal -->
                  <div class="modal fade" id="session_modal-lg" aria-labelledby="insertModalLabel">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content item">
                            <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <div class="modal-body">
                            <img id="cart_photo{{$details['item_id']}}" src="" width="100%" height="250px" alt="Image">
                            <br>
                            <h1 id="cart_name{{$details['item_id']}}" style="padding-top: 10px; padding-bottom: 10px; font-family: Lato, sans serif; font-weight: bolder;">
                            </h1>

                              <p id="cart_description{{$details['item_id']}}" style="font-family: 'Lato', sans serif"></p>

                              <h3 id="cart_price{{$details['item_id']}}" style="font-family: 'Lato', sans serif">
                                $

                              </h3>

                            </div>
                            <form  action="{{ route('update.cart') }}" method="POST" id="update_cart_link{{$details['item_id']}}">
                                @csrf
                                @method('patch')
                                <input type="hidden" id="_token" value="{{ csrf_token() }}">
                            <div class="modal-footer mr-auto totals-item" style="width: 100%;">

                                <div class="row" style="display: inline-block; width: 50%">
                                    <div class="col-lg-10">
                                      <span id="sub" class="minus_button btn btn-default mr-auto btn-round btn-sm" style="font-family: 'Lato', sans serif; float: left; background-color: #000;">
                                          <i class="fa fa-minus" style="color: #fff;"></i>
                                      </span>
                                      <input type="number" name="quantity" id="cart_quantity{{$details['item_id']}}" class="quantity" size="1" value="1" min="1" style="border: none; align-content: center; font-family: 'Lato', sans serif;" />
                                      <span id="add" class="plus_button btn btn-default mr-auto btn-round btn-sm" style="font-family: 'Lato', sans serif; float: left; background-color: #000;">
                                          <i class="fa fa-plus" style="color: #fff"></i>
                                      </span>

                                    </div>
                                </div>
                                <!--<label for="pass-quantity" class="pass-quantity">Quantity</label>
                                <input class="qty" type="number" value="0" min="0" id="quantity">
                               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->

                                <input type="hidden" id="cart_id{{$details['item_id']}}" name="id" value="0" >
                              <button type="submit" value="submit"  class="btn btn-default btn-block mr-auto"
                              style="float: right; background-color: #000; color: #fff; width: 45%; font-family: 'Lato', sans serif;">

                                <p id="form_button">update Cart $</p>
                                <p class="text-right" id="cart_total{{$details['item_id']}}">  </p>

                              </button>

                            </div>
                          </form>

                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- End modal -->

    @endforeach

        @endif


@endsection
@section('scripts')
<script type="text/javascript">

  $(document).ready(function(){

   $(document).on('click','.open_modal',function(){

        var url = $(this).attr('data-href');
        console.log(url);
        $.ajax({
          url:url,
          method:"GET",
          success:function(order_menu){
            var data = $.parseJSON(order_menu);
            var pic = data.menu_photo;
            var id = data.id;

            //success data
            console.log(data);
            $('#form_id').val(data.id);
            $('#item_name').html(data.item_name);
            $('#item_price').html(data.item_price);
            $('#item_description').html(data.menu_description);
            $('#menu_photo').attr("src", "{{ asset('images/order_menu')}}" + "/" +data.menu_photo);
            //$('#').val(id);
            $('#quantity').val(1);
            $('#total').html(data.item_price);

            $('#modal-lg').modal('show');

            console.log(data.id);
            console.log(data.item_name);
            console.log(data.item_price);
            console.log(data.menu_description);
            console.log($('#add_to_cart_link'));
            console.log(pic);
          }
        });

      $('.plus_button').click(function (event) {

            event.preventDefault();

            $(this).prev().val(+$(this).prev().val() + 1);

        });

        $('.minus_button').click(function (event) {

            event.preventDefault();

              if ($(this).next().val() > 1) {

                  if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);

                  $(this).prev().val(+$(this).prev().val() + 1);

              }

        });

        $(".plus_button").click(function(event){

        event.preventDefault();

        var item_price = $("#item_price").text();

        var quantity = $("#quantity").val();
        //$('#form_quantity').val(quantity);

         total = quantity * item_price;

         $("#total").html(total);

        });

        $(".minus_button").click(function(event){

          event.preventDefault();

          var item_price = $("#item_price").text();

          var quantity = $("#quantity").val();
          //$('#form_quantity').val(quantity);

           total = quantity * item_price;

           $("#total").html(total);

        });

        $("#modal-lg").hide(function(){

        $("#quantity").val(1);

        });

    });
  });

  $(document).on('click', '#cart_link' ,function(){

      var id = $('#session_id').val();
      //var id = $('#cart_link').val("data-id");
      var name = $('#session_name').val();
      var quantity = $('#session_quantity').val();
      //var quantity = ele.('.session_quantity').val();
      var description = $('#session_description').val();
      //var description = ele.('.session_description').val();
      var price = $('#session_price').val();
      //var price = ele.('.session_price').val();
      var image = $('#session_image').attr('src');
      //var image = ele.('.session_image').attr("src");
      var total = quantity * price;

      @if(session('cart'))

            @foreach(session('cart') as $id => $details)
      if($.sessionStorage.getItem("cart") != null){

      $('#cart_name{{$details['item_id']}}').html(name);
      $('#cart_price{{$details['item_id']}}').html(price);
      $('#cart_description{{$details['item_id']}}').html(description);
      $('#cart_photo{{$details['item_id']}}').attr("src", image);
      $('#cart_quantity{{$details['item_id']}}').val(quantity);
      $('#cart_total{{$details['item_id']}}').html(total);
      $('#cart_id{{$details['item_id']}}').val(id);
      $('#session_modal-lg').show();

      console.log(id);
      console.log(name);
      console.log(quantity);
      console.log(description);
      console.log(price);
      console.log(image);
      console.log(total);

      $('.plus_button').click(function (event) {

            event.preventDefault();

            $(this).prev().val(+$(this).prev().val() + 1);

        });

        $('.minus_button').click(function (event) {

            event.preventDefault();

              if ($(this).next().val() > 1) {

                  if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);

                  $(this).prev().val(+$(this).prev().val() + 1);

              }

        });

        $(".plus_button").click(function(event){

        event.preventDefault();

        var item_price = $("#cart_price{{$details['item_id']}}").text();

        var quantity = $("#cart_quantity{{$details['item_id']}}").val();
        //$('#form_quantity').val(quantity);

         total = quantity * item_price;

         $("#cart_total{{$details['item_id']}}").html(total);

        });

        $(".minus_button").click(function(event){

          event.preventDefault();

          var item_price = $("#cart_price{{$details['item_id']}}").text();

          var quantity = $("#cart_quantity{{$details['item_id']}}").val();
          //$('#form_quantity').val(quantity);

           total = quantity * item_price;

           $("#cart_total{{$details['item_id']}}").html(total);

        });
      }
      @endforeach

        @endif

    $(".update-cart").click(function (e) {

        e.preventDefault();

        var id = $('#cart_id').val();
        var quantity = $('#cart_quantity').val();
        var _token = $("input#_token").val();

        var formData = new FormData();

        formData.append("_token", _token);
        formData.append("quantity", quantity);
        formData.append("id", id);

        console.log(id);
        console.log(quantity);
        console.log(_token);

        $.ajax({

            url: '{{ route('update.cart') }}',

            method: "patch",

            data: {

                _token: _token,

                id: id,

                quantity: quantity

            },

            success: function (response) {

               console.log(response);

               window.location.reload();

            }

        });

    });

    });


</script>

@endsection
