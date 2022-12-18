@extends('layouts.main') 

@section('content')

    <!-- Start All Pages -->
    <div class="all-page-title page-breadcrumb" 
    style="background-image: url('{{ asset('images/order_menu')}}/{{$order_menu->menu_photo}}'); height: 300px;">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-12">
                    <h1 style="color: #fff; font-family: 'Lato';">{{ $order_menu->item_name }}</h1>
                    
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

    <div class="content">
      <div class="row">
        <div class="col-lg-8" style="padding-left: 1%; padding-top: 1%;">
          <p>
            {{$order_menu->menu_description}}
          </p>
        </div>
      </div>
    </div>

<div class="menu-box">
<div class="container">
    <div class="row">
<table id="cart" class="table table-bordered table-striped">

    <thead>

        <tr>

            <th style="width:40%">Item Name</th>

            <th style="width:10%">Price</th>

            <th style="width:20%">Quantity</th>

            <th style="width:20%" class="text-center">Subtotal</th>

            <th style="width:10%"></th>

        </tr>

    </thead>

    <tbody>

        @php $total = 0 @endphp

        @if(session('cart'))

            @foreach(session('cart') as $id => $details)

                @php $total += $details['price'] * $details['quantity'] @endphp

                <tr data-id="{{ $id }}" data-toggle="modal" data-target="#modal-lg{{ $id }}">

                    <td data-th="Product" data-toggle="modal" data-target="#modal-lg{{ $id }}">

                        <div class="row">

                            <div class="col-sm-3 hidden-xs"><img src="{{ asset('images/order_menu')}}/{{$details['image'] }}" width="100" height="100" class="img-responsive"/></div>

                            <div class="col-sm-9">

                                <h5 class="nomargin" style="font-family: 'Lato', sans serif;">{{ $details['name'] }}</h5>
                                <br>
                                <p class="show-read-more" id="description_text" style="font-family: 'Lato', sans serif;">
                                    {{ $details['menu_description']}}
                                </p>
                            </div>

                        </div>

                    </td>

                    <td data-th="Price">${{ $details['price'] }}</td>

                    <td data-th="Quantity">
                        <div class="amount">
                            <button id="sub{{$id}}" class="minus_button update-cart btn btn-default btn-round btn-sm" style="margin-right: 4%; font-family: 'Lato', sans serif; background-color: #000;">
                                <i class="fa fa-minus" style="color: #fff;"></i>
                            </button>
                            <input type="number" data-type="quantity" disabled name="quantity" id="{{$id}}" class="quantity update-cart" size="1" value="{{ $details['quantity'] }}" style="border: none; align-content: center; font-family: 'Lato', sans serif;" />
                            <button id="add{{$id}}" class="plus_button update-cart btn btn-default btn-round btn-sm">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!--
                        <span class="plus_button">+</span>
                        <input type="text" name="quantity" id="qty" value="{{ $details['quantity'] }}" class="quantity update-cart" 
                        maxlength="12" size="5"/>
                        <span class="min_button">-</span>

                        <div class="row">
                        <button id="minus_button{{ $id }}" onclick="decrease()" class="btn btn-default btn-rounded btn-sm">
                            <i class="fa fa-minus"></i></button>
                        <input id="amount{{ $id }}" type="number" size="5" name="quantity" min="1" value="{{ $details['quantity'] }}" class="quantity update-cart amount">
                        <button id="plus_button{{ $id }}" onclick="increase()" class="btn btn-default btn-sm">
                            <i class="fa fa-plus"></i></button>
                        </div>-->

                    </td>

                    <td data-th="Subtotal" class="text-center">${{ $details['price'] * $details['quantity'] }}</td>

                    <td class="actions" data-th="">

                        <button class="btn btn-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button>

                    </td>

                </tr>

            @endforeach

        @endif

    </tbody>

    <tfoot>

        <tr>
            <td>
                <div class="row">
                <div class="col-sm-4">
                <a href="{{ url('show_restaurants', $rest_id) }}" class="btn btn-warning"><i class="fa fa-angle-left">
                </i> Add More</a>
                </div>
                <div class="col-sm-3">
                <form method="POST" action="{{ route('orders.store')}}">
                    @csrf

                <input type="text" name="restaurant_id" value="{{ $rest_id}}" hidden>
                <input type="text" name="restaurant_name" value="{{ $restaurant->Restaurant_name}}" hidden>
                <input type="text" name="restaurant_address" value="{{ $restaurant->Restaurant_address}}" hidden>
                <input type="text" name="order_status" value="ordered" hidden>
                <button style="float: right;" class="btn btn-success">Checkout</button>
                </form>
                </div>
            </div>
            </td>

            <td colspan="5" class="text-right"><h3><strong>Total ${{ $total }}</strong></h3></td>

        </tr>
        <!-- 
        <tr>
            <td colspan="5" class="text-right">
            <div class="row">
                    <div class="col-lg-4">
                <a href="{{ url('show_restaurants', $rest_id) }}" class="btn btn-warning"><i class="fa fa-angle-left">
                </i> Add More</a>
                </div>
                <div class="col-lg-4">
                <form method="POST" action="{{ route('orders.store')}}">
                    @csrf

                <input type="text" name="restaurant_id" value="{{ $rest_id}}" hidden>
                <input type="text" name="restaurant_name" value="{{ $restaurant->Restaurant_name}}" hidden>
                <input type="text" name="restaurant_address" value="{{ $restaurant->Restaurant_address}}" hidden>
                <input type="text" name="order_status" value="ordered" hidden>
                <button style="float: right;" class="btn btn-success">Checkout</button>
                </form>
                </div>
            </div>

            </td>
        </tr>-->

    </tfoot>

</table>

<!-- Order Modal -->
                    <div class="modal fade" id="modal-lg{{$id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content item">
                                <!-- 
                            <div class="modal-header">
                              <h4 class="modal-title">  </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>-->
                            <div class="modal-body">
                            <img src="{{ asset('images/order_menu') }}/{{ $order_menu->menu_photo}}" width="100%" height="250px" alt="Image">
                            <br>
                            <h1 style="padding-top: 10px; padding-bottom: 10px; font-family: Lato, sans serif; font-weight: bolder;">
                                {{ $order_menu->sub_category_name }}</h1>

                          
                              <p style="font-family: 'Lato', sans serif">{{ $order_menu->menu_description}}&hellip;</p>

                              <h3 class="product-price" id="product-price" style="font-family: 'Lato', sans serif">
                                $ {{ $order_menu->item_price }}
                              </h3>
                              <div id="accordion">
                                  <div class="card card-default">
                                <div class="card-header">
                                  <h4 class="card-title w-50">
                                    <a class="d-block w-50" data-toggle="collapse" href="#collapseTwo" style="font-family: lato black; color: #000;">
                                      <strong>Choose your option</strong>
                                      required * 
                                    </a>
                                  </h4>
                                </div>
                                <div id="collapseTwo" class="collapse show" data-parent="#accordion">
                                  <div class="card-body">
                                    @foreach($sub_categories as $sub_category)
                                    <!-- 
                                    <table>
                                        <tbody>
                                        <tr data-id="{{ $id }}">
                                            <td>
                                            <div class="form-group">
                                              <label class="container" style="font-family: myriad pro; font-weight: normal;">{{$sub_category->sub_category_name}}
                                                <input data-th="additional_price" type="radio" id="additional_price" value="{{$sub_category->additional_price}}" name="radio" onclick="calcular()" 
                                                style="font-family: myriad pro; font-weight: normal;">
                                                <span class="checkmark"></span>
                                                <p>{{$sub_category->additional_price}} Birr</p>
                                              </label>
                                            </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>-->
                                    @endforeach
                                    
                                  </div>
                                  </div>
                                
                              </div>
                            </div>
                            </div>
                            <div class="modal-footer mr-auto totals-item" style="width: 100%;">
                                <div class="row" style="float: left; display: inline-block; width: 50%">
                                    <div class="col-lg-8">
                                        <table>
                                            <tbody>
                                            <tr data-id="{{ $id }}">
                                                <td data-th="Quantity">
                                            <span id="sub{{$id}}" class="minus_button btn btn-default mr-auto btn-round btn-sm update-cart" style="font-family: 'Lato', sans serif; float: left; background-color: #000;">
                                                <i class="fa fa-minus" style="color: #fff;"></i>
                                            </span>
                                            <input type="text" data-type="quantity" disabled name="quantity" id="{{$id}}" class="quantity" size="1" value="{{ $details['quantity'] }}" style="border: none; align-content: center; font-family: 'Lato', sans serif;" />
                                            <span id="add{{$id}}" class="plus_button btn btn-default mr-auto btn-round btn-sm update-cart" style="font-family: 'Lato', sans serif; float: left; background-color: #000;">
                                                <i class="fa fa-plus" style="color: #fff"></i>
                                            </span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--<label for="pass-quantity" class="pass-quantity">Quantity</label>
                                <input class="qty" type="number" value="0" min="0" id="quantity">
                               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                              <button type="button" class="btn btn-default btn-block mr-auto cart-total totals-item" style="float: right; background-color: #000; width: 40%">
                                <span class="totals-value cart-total" id="cart-subtotal"> 
                                    <a href="{{ route('add.to.cart', $order_menu->id) }}" role="button" style="color: #fff; font-family: 
                                        'Lato', sans serif">
                                        <span class="text-left">Add {{$details['quantity']}} to Cart </span> 
                                        <span class="text-right">${{ $total }}</span> 
                                    </a>
                                
                                </span>
                              </button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      <!-- /.end modal -->

</div>
</div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

    $(document).ready(function(){
        $("#modal-lg{{$id}}").modal('show');
    });

    $(document).ready(function(){
    var maxLength = 50;
    $(".show-read-more").each(function(){
        var myStr = $(this).text("");
        if($.trim(myStr).length > maxLength){
            var newStr = myStr.substring(0, maxLength);
            var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
            $(this).empty().html(newStr);
            $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
            $(this).append('<span class="more-text">' + removedStr + '</span>');
        }
    });
    $(".read-more").click(function(){
        $(this).siblings(".more-text").contents().unwrap();
        $(this).remove();
    });
    });

    $('.plus_button').click(function (event) {
        
        event.preventDefault();

        //var amount = $("input[data-type='answer']").val();

        //var new_amount = amount + 1;

        //alert(amount);
        //$(".quantity").val(new_amount);
        $(this).prev().val(+$(this).prev().val() + 1);
        
    });

    $('.minus_button').click(function (event) {

        event.preventDefault();

        //var amount = $(".quantity").val();

            //if(amount > 1){
              //  new_amount = amount - 1;

                //console.log(new_amount);
                //$(".quantity").val(new_amount);
            //}
            if ($(this).next().val() > 1) {
            if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
            }
    });

    $(".update-cart").click(function (e) {

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

                //$("#modal-lg{{$id}}").html(ajax_load).load(loadUrl);
                //$('#modal-lg{{$id}}')[0].update();
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