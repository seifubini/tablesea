<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>MyOnlyMenu</title>

  <!-- Google Font: Source Sans Pro
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
  <!-- Lato fonts -->
  <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Bootstrap Switch -->
  <!-- jQuery -->
  <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- slider script  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>-->

  <!--  end slider -->

  <style type="text/css">

/* Container holding the image and the text */
.img-container {
  position: relative;
  text-align: center;
  color: white;
}

/* Bottom left text */
.bottom-left {
  position: absolute;
  bottom: 300px;
  font-family: 'Lato';
  left: 2%;
}
/* Top left text
.top-left {
  position: absolute;
  top: 8px;
  left: 16px;
  background-color: #5AC363;
  width: 40%;
  border-radius: 10px;
  height: 50px;
  color: #000;
}*/
.deals-ribbon{
  width: 80%;
  height: 35px;
  padding-left: 15px;
  position: absolute;
  left: -2px;
  top: 130px;
  background: #5AC363;
  font-family: Lato;
  color: #fff;
  text-align: center;
  border-top-right-radius: 15px;
  border-bottom-right-radius: 15px;
}
$button-z: 0;
$button-highlight: 1;
$button-selection: 2;

.nav-tabs li> a{
  color: #000;
}
.nav-tabs li> a.active{
  color: #000;
  border-style: solid;
  border-top: 0;
  border-left: 0;
  border-right: 0;
  border-spacing: 25px;
  border-color: #000;
  padding-bottom: 12%;
  font-weight: bolder;
}
@media (max-width: 1280px) {
        .content-mobile {
          display: none;
        }
      }
@media (max-width: 767px) {
        .content-mobile {
          display: none;
        }
      }
@media (min-width: 992px) {
        #content-mobile {
          display: block;
        }
      }
@media(max-width:414px){
       #search-pc {
          display: none;
        }
}
.show-read-more .more-text{
        display: none;
}
/* radio button */
.rdio-primary input[type="radio"]:checked + label {
  &:before {
    border-color: #fbc52d;
  }
  &::after {
    background-color: #fbc52d;
  }
}
/* radio button end */
/* Slider*/
Edit in JSFiddle
Result
JavaScript
HTML
CSS
.customer-logos {
  background-color: #111;
}

/* Slider */
.slick-slide {
    margin: 0px 20px;
}

.slick-slide img {
    width: 100%;
}

.slick-slider
{
    position: relative;
    display: block;
    box-sizing: border-box;

    -webkit-user-select: none;
       -moz-user-select: none;
        -ms-user-select: none;
            user-select: none;

    -webkit-touch-callout: none;
    -khtml-user-select: none;
    -ms-touch-action: pan-y;
        touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
}

.slick-list
{
    position: relative;
    display: block;
    overflow: hidden;

    margin: 0;
    padding: 0;
}
.slick-list:focus
{
    outline: none;
}
.slick-list.dragging
{
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list
{
    -webkit-transform: translate3d(0, 0, 0);
       -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
         -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
}

.slick-track
{
    position: relative;
    top: 0;
    left: 0;

    display: block;
}
.slick-track:before,
.slick-track:after
{
    display: table;

    content: '';
}
.slick-track:after
{
    clear: both;
}
.slick-loading .slick-track
{
    visibility: hidden;
}

.slick-slide
{
    display: none;
    float: left;

    height: 100%;
    min-height: 1px;
}
[dir='rtl'] .slick-slide
{
    float: right;
}
.slick-slide img
{
    display: block;
}
.slick-slide.slick-loading img
{
    display: none;
}
.slick-slide.dragging img
{
    pointer-events: none;
}
.slick-initialized .slick-slide
{
    display: block;
}
.slick-loading .slick-slide
{
    visibility: hidden;
}
.slick-vertical .slick-slide
{
    display: block;

    height: auto;

    border: 1px solid transparent;
}
.slick-arrow.slick-hidden {
    display: none;
}
/* End Slider*/
  </style>

</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="padding-left: 1.5%; padding-top: 0.5%; height: 130px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars fa-2x"></i></a>
      </li>
      <li class="navbar-brand">
        <a href="{{ url('/home')}}">
        <img src="{{ asset('images/IMG_3772.PNG')}}" class="img-responsive" height="40" style="padding-left: 10%;">
        </a>
      </li>

    </ul>
    <!-- Search Box -->
    <form id="search-pc" method="POST" action="{{ route('autocomplete-search') }}" class="mx-2 my-auto d-inline w-100" style="padding-top: 0.5%; padding-left: 3%; padding-right: 1%;">
      @csrf
            <div class="input-group">
                <input type="text" id="search" name="search_query" class="form-control border border-right-0" placeholder="What are you carving?" required>
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary border border-left-0" value="submit" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>

    <!-- Right navbar links -->
    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto" style="padding-top: 1%;">
      <!-- Navbar Search -->
      <li id="content-mobile" class="nav-item" style="display: none;">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <li class="nav-item" style="padding-bottom: 1%; padding-right: 5%;">
        <a class="nav-link" href="#" role="button">
          @if($query != "")
          <button style="width: 250px; background-color: #000; padding: 4%; color: #fff;">{{$query}}</button>
          @endif
        </a>
      </li>
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown" style="padding-top: 1%; padding-right: 0%;">
        <a class="nav-link" data-toggle="dropdown" href="#" style="background-color: #000; width: 160px; border-radius: 10px; color: #fff; height: 50px;">
          <i class="nav-icon fas fa-shopping-cart float-left" style="padding-top: 2%;"></i>
          <p class="text-white" style="color: #fff; float: right; padding-bottom: 7%;"> {{ count((array) session('cart')) }}</p>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

          <a href="javascript:void(0);" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>
              <span class="badge badge-pill badge-danger mr-3">{{ count((array) session('cart')) }}</span>
              @php $total = 0 @endphp

                        @foreach((array) session('cart') as $id => $details)

                            @php $total += $details['price'] * $details['quantity'] @endphp

                        @endforeach
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Total:
                  <span class="float-right text-sm">$ {{ $total }}</span>
                </h3>

                <p class="text-sm"></p>
                <p class="text-sm text-muted"></p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>

          @if(session('cart'))

                        @foreach(session('cart') as $id => $details)

          <a href="javascript:void(0);" id="cart_link" data-id="{{$id}}" class="dropdown-item cart_link" data-href="#session_modal-lg" data-toggle="modal" data-target="#session_modal-lg">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('images/order_menu')}}/{{$details['image'] }}" id="session_image" alt="User Avatar" class="img-size-50 mr-3 img-brand session_image">
              <div class="media-body">
                <input type="hidden" name="id" id="session_id" class="session_id" value="{{$id}}" >
                <h3 class="dropdown-item-title">
                  <span>{{ $details['name'] }}</span>
                  <span class="float-right text-sm">Quantity:<span> {{ $details['quantity'] }}</span></span>
                  <input type="hidden" value="{{ $details['quantity'] }}" id="session_quantity" class="session_quantity">
                  <input type="hidden" value="{{ $details['price']}}" id="session_price" class="session_price">
                  <input type="hidden" value="{{ $details['name']}}" id="session_name" class="session_name">
                  <input type="hidden" value="{{ $details['menu_description']}}" id="session_description" class="session_description">
                </h3>
                <br>
                <p class="text-sm" id="menu_description"></p>
                <p class="text-sm text-muted">$<span>{{ $details['price'] }}</span></p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
                  <input type="text" name="restaurant_id" value="{{$details['restaurant_id']}}" hidden>
                @endforeach
              @endif
          <div class="dropdown-divider"></div>
          @if($rest_id)
          <button class="dropdown-item dropdown-footer" role="button" value="submit">
            <a href="{{ route('place_order', $rest_id)}}">Place Order</a>
          </button>
          @endif
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">

        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">

        </a>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-white-primary elevation-4" style="background-color: #fff; color: #000;">
    <!-- Brand Logo -->
    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="float: right;"><i class="fas fa-bars fa-2x"></i></a>
    <br>
    <a class="brand-link">

    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      @if (Auth::guest())
      <button type="button" style="background-color: #000; color: #fff; margin-left: 2px; padding: 2%; width: 90%; border: none;" data-toggle="modal" data-target="#modal-default">
      Sign In
      </button>
      @else
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if(Auth::user()->user_image == "")
          <img src="{{ asset('images/user_avatar.jpeg')}}" class="img-circle elevation-2" alt="User Image">
          @else
          <img src="{{ asset('images/users') }}/{{ Auth::user()->user_image }}" class="img-circle elevation-2" alt="User Image">
          @endif

        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
          <a href="#">View account</a>
        </div>
      </div>
      @endif
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          @if (Auth::guest())
          <li class="nav-item">
            <a href="#" class="nav-link">
              <p>
                Create a business account
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <p>
                Add your restaurant
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/play_and_win',$rest_id) }}" class="nav-link {{ (request()->is('play_and_win*')) ? 'active' : ''}}" target="_blank">
              <p>
                Play and win
              </p>
            </a>
          </li>
          @else
          <li class="nav-item">
            <a href="{{ url('/my_orders', Auth::user()->id)}}" class="nav-link {{ (request()->is('my_orders*')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                My Orders
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/my_games', Auth::user()->id)}}" class="nav-link {{ (request()->is('my_games*')) ? 'active' : ''}}">
              <i class="fa fa-bullhorn"></i>
              <p>
                Promotions
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/play_and_win',$rest_id) }}" class="nav-link {{ (request()->is('play_and_win*')) ? 'active' : ''}}" target="_blank">
              <p>
                Play and win
              </p>
            </a>
          </li>
          <br>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" >
            @csrf

              <button type="submit" class="btn btn-default btn-flat" style="width: 100%;">
                Sign out
              </button>
            </form>
          </li>
          <a class="brand-link"></a>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <p>
                Create a business account
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <p>
                Add your restaurant
              </p>
            </a>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #fff;">

    <section class="content">

  @yield('content')

    </section>

    @yield('scripts')

  </div>
  <!-- /.content-wrapper -->

  <!-- SignIn modal  -->
  <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Sign in to start your Session</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
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
              <!-- form start -->
              <form id="LoginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-default btn-block" style="background-color: #5AC363; color: #fff;">SignIn</button>
                </div>
              </form>
              <p class="mb-1">
                @if (Route::has('password.request'))
                           <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                        @endif

              </p>
            <!--</div>
             /.card -->
            </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
            </div>

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal end here -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer" style="background-color: #F4F6F9;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS FOR SEARCH -->

<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js')}}"></script>

<!-- Bootstrap Switch -->

<script type="text/javascript">
$(document).ready(function(){
  $(function () {

    $.validator.setDefaults({
      submitHandler: function () {
        alert( "Form successful submitted!" );
      }
    });
    $('#LoginForm').validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 5
        },
      },
      messages: {
        email: {
          required: "Please enter a email address",
          email: "Please enter a vaild email address"
        },
        password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 8 characters long"
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
});
</script>

  <script type="text/javascript">

    $(document).ready(function(){
      $("#menu_description").each(function(){
      $("#menu_description").text(function(index, currentText) {
        return currentText.substr(0, 50);
      });
      });
    });

    $(document).ready(function(){
        var maxLength = 100;
        $(".show-read-more").each(function(){
            var myStr = $(this).text();
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
  </script>

</body>
</html>
