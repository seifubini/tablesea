<!DOCTYPE html>
<html lang="en">
<head>
    <title>TableSea Restaurant Reservation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Google Font: Source Sans Pro-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Lato fonts-->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
    <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/differenceHours.js') }}"></script>
    <!-- MomentJS -->
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <style>
        #book-a-table {
            background-color: #FAFAFA;

            width: 100%;
        }
        .book-a-table p {
            color: #9a9a9a;
        }
        #additional_message {
            min-height: 150px;
            max-width: 100%;
        }
        .form-group{
            padding: 0.5%;
        }
        .bs-example{
            width: 100%;
            height: 40px;
        }
    </style>
</head>
<body>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid" style="width: 100%; padding: 0; height: 1080px">
    <section id="book-a-table" class="content" style="background-image: linear-gradient(to top, rgba(21, 21, 21, 0.08) ,rgba(21, 21, 21, 0.96)),
        url(' {{ asset('images/restaurant')}}/{{$restaurant->Restaurant_photo}} '); background-size:cover; display: block; background-position: 50% 50%;
        background-color: rgba(0,0,0,0.5); width: 100%; height: auto; max-height: 1800px; padding-bottom: 5%; margin-bottom: 0%;">

        <nav class="navbar navbar-expand-md">
            <a href="{{ url('/') }}" style="margin-left: 2%;">
                <img src="{{ asset('images/TableSeaNew.jpg')}}"
                     class="d-inline-block align-middle mr-2" width="70" height="70" alt="Table Sea Logo">
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- <div class="navbar-nav">
                    <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="#" class="nav-item nav-link">Profile</a>
                    <a href="#" class="nav-item nav-link">Messages</a>
                    <a href="#" class="nav-item nav-link disabled" tabindex="-1">Reports</a>
                </div>-->
                <div class="navbar-nav ml-auto" style="padding-right: 5%;">
                    @if (Route::has('login'))
                        <div class="hidden fixed top-0 right-0 px-1 py-4 sm:block">
                            @auth
                                <form action="{{ route('logout') }}" method="POST" >
                                @csrf
                                <!-- --><button type="submit" class="btn btn-default btn-flat float-right">
                                        {{ __('Logout') }}</button>

                                </form>
                            @else
                                <button class="btn btn-custom" style="background-color: #0065A3; color: #fff; font-weight: bold;"
                                        data-toggle="modal" data-target="#modal-default-signin">
                                    Log in </button>

                                <button class="btn btn-custom" style="background-color: #fff; color: #0065A3; font-weight: bold;"
                                        data-toggle="modal" data-target="#modal-default-signup">
                                    Register</button>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <div class="container" data-aos="fade-up" style="padding-top: 3%;">

            <div class="row">
                <div class="d-block col-lg-12 col-md-6 col-sm-4 text-center">
                    <h1 class="text-white" style="font-family: Lato, sand-serif,serif; font-weight: bolder; padding-bottom: 1%;
                        font-size:3vw; line-height: 56px; color: #fff;">
                        Ready to make your reservation at <br> {{$restaurant->Restaurant_name}}?
                    </h1>
                </div>
            </div>

            <div class="form-row" id="check_availability_inputs">

                <input type="hidden" style="display: none;" id="_token" value="{{ csrf_token() }}">

                <div class="d-block col-lg-4 col-md-6 form-group">
                    <div class="input-group date" data-target-input="nearest">
                        <input type="date" name="date_of_reservation" class="form-control" id="reservation_date" placeholder="Date" required>
                    </div>
                </div>
                <div class="d-block col-lg-4 col-md-6 form-group">
                    <div class="input-group">
                        <input type="time" class="form-control" readonly value="--:-- --" name="time_of_reservation" id="reservation_time">
                        <!-- <select class="custom-select" name="time_of_reservation" id="reservation_time">
                                <option value=""></option>
                        </select>-->
                    </div>
                </div>
                <div class="d-block col-lg-4 col-md-6 form-group">
                    <div class="input-group">
                        <select class="custom-select" name="number_of_people" id="number_of_people" required>
                            <option value="1">1 Person</option>
                            <option value="2">2 Persons</option>
                            <option value="3">3 Persons</option>
                            <option value="4">4 Persons</option>
                            <option value="5">5 Persons</option>
                            <option value="6">6 Persons</option>
                            <option value="7">7 Persons</option>
                            <option value="8">8 Persons</option>
                            <option value="9">9 Persons</option>
                            <option value="10">10 Persons</option>
                            <option value="11">11 Persons</option>
                            <option value="12">12 Persons</option>
                            <option value="13">13 Persons</option>
                            <option value="14">14 Persons</option>
                            <option value="15">15 Persons</option>
                            <option value="16">16 Persons</option>
                            <option value="17">17 Persons</option>
                            <option value="18">18 Persons</option>
                            <option value="19">19 Persons</option>
                            <option value="20">20 Persons</option>
                            <option value="large_party">Large Party</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" value="{{$restaurant->id}}" id="restaurant_id" style="display: none;" name="restaurant_id">
            </div>
            <div id="working_times" class="d-block row form-group">
                @foreach($working_hours as $available_hour)
                <button name="hour_button" type="button" class="btn bg-gradient hour" value="{{ $available_hour }}"
                        style="margin-left: 1%; margin-bottom: 1%; background-color: #0065A3; color: #fff;">
                    {{ date('h:i A', strtotime($available_hour)) }}
                </button>
                @endforeach
            </div>
            <div id="available_times" hidden class="row form-group">

            </div>

            <div class="form-row" id="user_inputs" style="display: none;">
                @if (Auth::check())
                <div class="d-block col-lg-4 col-md-6 form-group">
                    <div class="input-group">
                        <input type="email" value="{{Auth::user()->email}}" class="form-control" name="reserver_email" style="display: none;" id="reserver_email" placeholder="Email" required>
                    </div>
                </div>
                <div class="d-block col-lg-4 col-md-6 form-group">
                    <div class="input-group date" data-target-input="nearest">
                        <input type="text" value="{{Auth::user()->name}}" class="form-control" name="user_full_name" style="display: none;" id="user_full_name" placeholder="Full Name" required>
                    </div>
                </div>
                <div class="d-block col-lg-4 col-md-6 form-group">
                    <div class="input-group date" data-target-input="nearest">
                        <input type="text" value="{{Auth::user()->user_phone_number}}" class="form-control" name="phone_number" style="display: none;" id="phone_number" placeholder="Phone Number" required>
                    </div>
                </div>
                @else
                    <div class="d-block col-lg-4 col-md-6 form-group">
                        <div class="input-group">
                            <input type="email" class="form-control" name="reserver_email" style="display: none;" id="reserver_email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="d-block col-lg-4 col-md-6 form-group">
                        <div class="input-group date" data-target-input="nearest">
                            <input type="text" class="form-control" name="user_full_name" style="display: none;" id="user_full_name" placeholder="Full Name" required>
                        </div>
                    </div>
                    <div class="d-block col-lg-4 col-md-6 form-group">
                        <div class="input-group date" data-target-input="nearest">
                            <input type="text" class="form-control" name="phone_number" style="display: none;" id="phone_number" placeholder="Phone Number" required>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row form-group">
                <textarea class="d-block form-control" id="reserver_additional_message" style="display: none;" maxlength="500"></textarea>
            </div>

            <div class="d-block row form-group">
            <button type="button" class="btn btn-primary btn-block" id="check_button">Check Availability</button>
            <button type="button" class="btn btn-primary btn-block" style="display: none;" id="book_table">Book a Table</button>
            </div>
            <div id="error_message" class="ajax_response" style="float:left"></div>
            <div id="success_message" class="ajax_response" style="float:left"></div>

            <br>
            <br>
            <br>
            <div class="row form-group">
                <h1 class="text-center text-white">{{$restaurant->Restaurant_name}}</h1>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-white"><i class="fas fa-map-marked"></i> {{$restaurant->Restaurant_address}}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-white"><i class="fas fa-mobile"></i> {{$restaurant->Restaurant_phone}}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-white">
                                <i class="fas fa-times-circle"></i>
                                {{ date('h:i A', strtotime($restaurant->restaurant_opening_hour)) }}
                                - {{ date('h:i A', strtotime($restaurant->restaurant_closing_hour)) }}
                            </h4>
                        </div>
                    </div>
                    <p class="card-text text-white">{{ $restaurant->Restaurant_description }}</p>
                </div>
            </div>
        </div>

    </section>

    @yield('content')

</div>


<!-- SignIn modal  -->
<div class="modal hide" id="modal-signin">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="welcome_message"></h4>
                <button type="button" id="close_modal" class="close" data-dismiss="modal" aria-label="Close">
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
                                        <input type="email" name="email" class="form-control" id="exampleInputEmail2" placeholder="Enter email">
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

<!-- SignIn modal  -->
<div class="modal fade" id="modal-default-signin">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sign in</h4>
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

<!-- SignUp Modal -->
<div class="modal fade" id="modal-default-signup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sign Up</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="login-box-msg">Create an Account</p>

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

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Full name" name="name" :value="old('name')" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" :value="old('email')" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="Phone" class="form-control" placeholder="Phone Number" name="user_phone_number" :value="old('user_phone_number')" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_type" value="Client">
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-default btn-block" style="background-color: #5AC363; color: #fff;">SignUp</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- SignIn modal  -->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sign in</h4>
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
                                        <input type="email" name="email" class="form-control" id="exampleInputEmail2" placeholder="Enter email">
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

<!-- Welcome back Message modal  -->
<div class="modal fade" id="modal-default-welcome">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="logged_user_message"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal end here -->

@yield('scripts')
<script>
    $(function(){
        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;

        $('#reservation_date').attr('min', maxDate);
    });
</script>
<script>
    $(document).ready(function(){

        $(function() {});

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });


        $("#close_modal").click(function () {
           $("#modal-signin").hide();
        });

        $("#working_times button").click(function() {
            var hour = $(this).val();

            $("#reservation_time").val(hour);
        });

        $("#modal-default").hide();

        $("#close_button").click(function () {
            $("#modal-default").hide();
        });

        $('#check_button').click(function() {

            const check_url = "{{ route('check_restaurant')}}";
            const _token = $("input#_token").val();
            const reservation_date = $("#reservation_date").val();
            const reservation_time = $("#reservation_time").val();
            const number_of_people = $("#number_of_people").val();
            const restaurant_id = $("#restaurant_id").val();

            console.log(reservation_date);
            console.log(reservation_time);
            console.log(number_of_people);
            console.log(check_url);
            console.log(_token);

            //alert(reservation_time);

            $.ajax({

                url:check_url,

                method:"POST",

                data: {

                    _token: _token,

                    reservation_date: reservation_date,

                    reservation_time: reservation_time,

                    number_of_people: number_of_people,

                    restaurant_id: restaurant_id

                },
                success:function(availability){
                    //success data
                    const value = availability.value;

                    //if restaurant not found
                    if(value === "restaurant not found")
                    {
                        $(document).Toasts('create', {
                            class: 'bg-warning',
                            title: 'Restaurant not Found',
                            body: availability.message + '                          '
                        });
                        //toastr.error(availability.message);
                        //alert(availability.message);
                    }
                    if(value === "closed")
                    {
                        $(document).Toasts('create', {
                            class: 'bg-warning',
                            title: 'Restaurant Closed',
                            body: availability.message + '                          '
                        });
                        //toastr.error(availability.message);
                        //alert(availability.message);
                    }
                    if(value === "full")
                    {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Table Occupied',
                            body: availability.message + '                          '
                        });
                        //toastr.error(availability.message);
                        //alert(availability.message);
                    }
                    else{
                        //if table is not booked
                        if(value === "empty")
                        {
                            $(document).Toasts('create', {
                                class: 'bg-info',
                                title: 'Table Open',
                                body: availability.message + '                          '
                            });

                            $("#working_times").hide();
                            $("#reserver_additional_message").show();
                            $("#user_inputs").show();
                            $("#reserver_email").show();
                            $("#user_full_name").show();
                            $("#phone_number").show();
                            $("#check_button").hide();
                            $("#check_button").hide();
                            $("#book_table").show();

                            //if table is not booked execute this
                            $("#book_table").click(function () {

                                const reserver_message = $("#reserver_additional_message").val();
                                const _token = $("input#_token").val();
                                const number_of_people = $("#number_of_people").val();
                                const reservation_date = $("#reservation_date").val();
                                const reservation_time = $("#reservation_time").val();
                                const book_url = "{{ route('book_restaurant')}}";
                                const restaurant_id = $("#restaurant_id").val();
                                const user_name = $("#user_full_name").val();
                                const phone_number = $("#phone_number").val();
                                const user_email = $("#reserver_email").val();
                                const reservation_type = 'online';
                                if(reserver_message.length > 500){
                                    $("#error_message").show().html
                                    ("Max input for the message field is 500 characters");
                                    alert("Max input for the message field is 500 characters");
                                    $("#book_table").prop('disabled', true);
                                }

                                $.ajax({

                                    url: book_url,

                                    method: "POST",

                                    data: {

                                        _token: _token,

                                        reservation_date: reservation_date,

                                        reservation_time: reservation_time,

                                        number_of_people: number_of_people,

                                        reserver_message: reserver_message,

                                        restaurant_id: restaurant_id,

                                        reservation_type: reservation_type,

                                        user_name: user_name,

                                        phone_number: phone_number,

                                        user_email: user_email

                                    },
                                    success: function (booked) {

                                        $(document).Toasts('create', {
                                            class: 'bg-success',
                                            title: 'Table Booked Successfully.',
                                            body: booked.message + '                          '
                                        });

                                        if(booked.registered === "logged_user")
                                        {
                                            var logged_user_message = "Welcome back, "+user_name+'.';
                                            $("#modal-default-welcome").show();
                                            $("#logged_user_message").text(logged_user_message);

                                        }
                                        if(booked.registered === "yes")
                                        {
                                            var welcome_message = "Welcome back, "+user_name+'.';
                                            $("#modal-signin").show();
                                            $("#welcome_message").text(welcome_message);
                                            $("#exampleInputEmail2").val(user_email);

                                        }
                                        if(booked.registered === "no")
                                        {
                                            var thank_message = "Thanks, "+user_name+'.';
                                            $("#modal-default").show();
                                            $("#login_thank_you").text(thank_message);
                                            $("#user_name").val(user_name);
                                            $("#user_email").val(user_email);
                                            $("#user_phone").val(phone_number);
                                        }
                                        //alert('table booked successfully.');
                                    }
                                });

                            });

                        }
                        //if table is booked execute this
                        if(value === "all_booked"){
                            $(document).Toasts('create', {
                                class: 'bg-danger',
                                title: 'Tables are Fully Booked.',
                                body: 'All our Tables are Fully Booked, Try another Time or Date.'
                            });
                            //toastr.error('Table is Fully Booked, Try another Time or Date.');

                            $("#working_times").hide();
                            $("#available_times").show();

                            var available_hours = $.parseJSON(availability.available_hours);
                            for (var i = 0; i < available_hours.length; i++)
                            {
                                var open_hour = available_hours[i];

                                const timeString12hr = new Date('1970-01-01T' + open_hour + 'Z')
                                    .toLocaleTimeString({},
                                        {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
                                    );

                                $("#available_times").append(
                                    '<div class=\"col-lg-2 col-md-2\">' +
                                    '<button name=\"hour_button\" type=\"button\" class=\"btn bg-gradient-secondary hour\">'
                                    + timeString12hr +"</button></div>");

                            }
                            $(".hour").click(function () {
                                var ButtonText = $(this).text();
                                $("#reservation_time").val(ButtonText);
                                $("#reservation_time").attr("placeholder", ButtonText);
                                //alert(ButtonText);
                            });
                        }

                    }

                },
            });

        });

        //window.location.reload();
    });

</script>
</body>
</html>
