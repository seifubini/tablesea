<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TableSea Reservation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">

<div class="wrapper">
<!-- Content Wrapper. Contains page content -->


<section class="content">

<div class="container">

    <div class="row">

      <div class="col-lg-2 col-12 col-md-2 col-xl-2">
            
        </div>

        <div class="col-lg-8 col-12 col-md-8 col-xl-8 mt-5 " >

            <center> 
              <a href="{{ url('/')}}">
                <img src="{{ asset('images/TableSeaNew.jpg')}}" width="40%" height="250px"/>
              </a> 
            </center>
            
            <br><br>
            <center>
              <h2>
                <b>Dear {{Auth::user()->name}} your subscription is expired, please contact the administrator to renew your subscription.
                </b>
              </h2>
            </center>

            <center>
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
            </center>
            
            <br>

            <form action="{{ route('logout') }}" method="POST" >
              @csrf    
            
                <br>
                <button type="submit" class="btn btn-primary btn-block" style="height:48px;">
                  <b> Logout </b> 
                </button>
           
            </form>

        </div>


    </div>

 </div>

</section>


</div>

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js')}}"></script>
</body>
</html>

