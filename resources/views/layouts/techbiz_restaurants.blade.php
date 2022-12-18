<!DOCTYPE html>
<html lang="en"><!-- Basic -->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
   
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
     <!-- Site Metas -->
    <title>MyOnly Menu Restaurants</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="{{ asset('frontend/restaurant/images/favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('frontend/restaurant/images/apple-touch-icon.png')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/restaurant/css/bootstrap.min.css')}}">    
	<!-- Site CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/restaurant/css/style.css')}}">    
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/restaurant/css/responsive.css')}}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/restaurant/css/custom.css')}}">

    <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Modal CSS Style -->

    <!-- End Modal CSS -->

</head>

<body>

    <section class="content">

        @yield('content')

    </section>

    @yield('scripts')

<!-- Start Contact info -->
    <div class="contact-imfo-box">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <i class="fa fa-volume-control-phone"></i>
                    <div class="overflow-hidden">
                        <h4>Phone</h4>
                        <p class="lead">
                            +01 123-456-4590
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <i class="fa fa-envelope"></i>
                    <div class="overflow-hidden">
                        <h4>Email</h4>
                        <p class="lead">
                            yourmail@gmail.com
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <i class="fa fa-map-marker"></i>
                    <div class="overflow-hidden">
                        <h4>Location</h4>
                        <p class="lead">
                            800, Lorem Street, US
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact info -->
    
    
    
    <a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

    <!-- ALL JS FILES -->
    <script src="{{ asset('frontend/restaurant/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/popper.min.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/bootstrap.min.js')}}"></script>
    <!-- ALL PLUGINS -->
    <script src="{{ asset('frontend/restaurant/js/jquery.superslides.min.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/images-loded.min.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/isotope.min.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/baguetteBox.min.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/form-validator.min.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/contact-form-script.js')}}"></script>
    <script src="{{ asset('frontend/restaurant/js/custom.js')}}"></script>

    <!-- jQuery -->

<!-- <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>-->
<!-- Bootstrap 4 
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>-->
<!-- SweetAlert2 
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>-->
<!-- Toastr 
<script src="{{ asset('plugins/toastr/toastr.min.js')}}"></script>-->
<!-- AdminLTE App 
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>-->
<!-- AdminLTE for demo purposes 
<script src="{{ asset('dist/js/demo.js')}}"></script>-->
<!-- 
<script>
$(document).ready(function(){
    /* Set rates */
    var fadeTime = 300;

    /* Assign actions */
   $('.pass-quantity input').change(function() {
     updateQuantity(this);
   });

     /* Recalculate cart */
     function recalculateCart() {
     var subtotal = 0;

     /* Sum up row totals */
     $('.item').each(function() {
       subtotal += parseFloat($(this).children('.product-line-price').text());
     });

     /* Calculate totals */
     var total = subtotal;

     /* Update totals display */
     $('.totals-value').fadeOut(fadeTime, function() {
       $('#cart-subtotal').html(subtotal.toFixed(2));
       $('.cart-total').html(total.toFixed(2));
       if (total == 0) {
         $('.checkout').fadeOut(fadeTime);
       } else {
         $('.checkout').fadeIn(fadeTime);
       }
       $('.totals-value').fadeIn(fadeTime);
     });

    }

     /* Update quantity */
   function updateQuantity(quantityInput) {
     /* Calculate line price */
     var productRow = $(quantityInput).parent().parent();
     var price = parseFloat(productRow.children('.product-price').text());
     var quantity = $(quantityInput).val();
     var linePrice = price * quantity;

     /* Update line price display and recalc cart totals */
     productRow.children('.product-line-price').each(function() {
       $(this).fadeOut(fadeTime, function() {
         $(this).text(linePrice.toFixed(2));
         recalculateCart();
         $(this).fadeIn(fadeTime);
       });
     });
   }

   
});
</script>
-->

</body>
</html>