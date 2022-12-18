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
    <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- date-range-picker -->
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Autocomplete CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/autocomplete.min.css') }}">
    <link rel="stylesheet" href="{{ asset('autocomplete/easy-autocomplete.min.css') }}">
    <link rel="stylesheet" href="{{ asset('autocomplete/easy-autocomplete.themes.min.css') }}">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Lato fonts-->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
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
<!-- Content Wrapper. Contains page content
<div class="content-wrapper">-->
<div class="container-fluid" style="background-color: #fff; width: 100%; padding: 0;">
    <section id="book-a-table" class="content" style="background-image: linear-gradient(to bottom, rgba(21, 21, 21, 0.08) ,rgba(21, 21, 21, 0.96)),
        url(' {{ asset('images/table-reservation.jpg')}}'); background-size:cover; display: block; background-position: 50% 50%;
        background-color: rgba(0,0,0,0.5); width: 100%; height: auto; max-height: 700px; padding-bottom: 5%; margin-bottom: 2%;">

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
                <div class="col-md-12 text-center">
                    <h1 class="text-white" style="font-family: Lato, sand-serif,serif; font-weight: bolder; padding-bottom: 1%;
            font-size: 70px; line-height: 56px; color: #fff;">
                        Ready to make your Reservation?
                    </h1>
                </div>
            </div>

            <div class="form-row" id="check_availability_inputs">

                <input type="hidden" style="display: none;" id="_token" value="{{ csrf_token() }}">

                <div class="col-lg-3 col-md-6 form-group">
                    <div class="input-group date" data-target-input="nearest">
                        <input type="date" name="date_of_reservation" class="form-control datepicker-input" id="reservation_date" placeholder="Date" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 form-group">
                    <div class="form-group">
                        <select class="custom-select" name="time_of_reservation" id="reservation_time">
                            <option value="01:00:00">01:00 AM</option>
                            <option value="01:30:00">01:30 AM</option>
                            <option value="02:00:00">02:00 AM</option>
                            <option value="02:30:00">02:30 AM</option>
                            <option value="03:00:00">03:00 AM</option>
                            <option value="03:30:00">03:30 AM</option>
                            <option value="04:00:00">04:00 AM</option>
                            <option value="04:30:00">04:30 AM</option>
                            <option value="05:00:00">05:00 AM</option>
                            <option value="05:30:00">04:30 AM</option>
                            <option value="06:00:00">06:00 AM</option>
                            <option value="06:30:00">06:30 AM</option>
                            <option value="07:00:00">07:00 AM</option>
                            <option value="07:30:00">07:30 AM</option>
                            <option value="08:00:00">08:00 AM</option>
                            <option value="08:30:00">08:30 AM</option>
                            <option value="09:00:00">09:00 AM</option>
                            <option value="09:30:00">09:30 AM</option>
                            <option value="10:00:00">10:00 AM</option>
                            <option value="10:30:00">10:30 AM</option>
                            <option value="11:00:00">11:00 AM</option>
                            <option value="11:30:00">11:30 AM</option>
                            <option value="12:00:00">12:00 AM</option>
                            <option value="12:30:00">12:30 AM</option>
                            <option value="13:00:00">01:00 PM</option>
                            <option value="13:30:00">01:30 PM</option>
                            <option value="14:00:00">02:00 PM</option>
                            <option value="14:30:00">02:30 PM</option>
                            <option value="15:00:00">03:00 PM</option>
                            <option value="15:30:00">03:30 PM</option>
                            <option value="16:00:00">04:00 PM</option>
                            <option value="16:30:00">04:30 PM</option>
                            <option value="17:00:00">05:00 PM</option>
                            <option value="17:30:00">04:30 PM</option>
                            <option value="18:00:00">06:00 PM</option>
                            <option value="18:30:00">06:30 PM</option>
                            <option value="19:00:00">07:00 PM</option>
                            <option value="19:30:00">07:30 PM</option>
                            <option value="20:00:00">08:00 PM</option>
                            <option value="20:30:00">08:30 PM</option>
                            <option value="21:00:00">09:00 PM</option>
                            <option value="21:30:00">09:30 PM</option>
                            <option value="22:00:00">10:00 PM</option>
                            <option value="22:30:00">10:30 PM</option>
                            <option value="23:00:00">11:00 PM</option>
                            <option value="23:30:00">11:30 PM</option>
                            <option value="00:00:00">12:00 PM</option>
                            <option value="00:30:00">12:30 PM</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 form-group">
                    <div class="form-group">
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
                    <!--
                    <div class="input-group date" data-target-input="nearest">
                        <input type="number" class="form-control" name="" id="" placeholder="No. of people" required>
                    </div>-->
                </div>
                <div class="col-lg-3 col-md-6 form-group">
                    <div class="form-group">
                        <input type="text" class="form-control" name="Restaurant_name" id="search-form" placeholder="Restaurant name" required>
                        <!--<div id="message">Selection</div>-->
                    </div>
                    <ul class="form-group" id="searchResult" style="color: #ffffff;"></ul>
                </div>
            </div>
            <div class="form-row" id="user_inputs">

                <div class="col-lg-4 col-md-6 form-group">
                    <div class="input-group">
                        <input type="email" class="form-control" name="reserver_email" style="display: none;" id="reserver_email" placeholder="Email" required>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 form-group">
                    <div class="input-group date" data-target-input="nearest">
                        <input type="text" class="form-control" name="user_full_name" style="display: none;" id="user_full_name" placeholder="Full Name" required>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 form-group">
                    <div class="input-group date" data-target-input="nearest">
                        <input type="text" class="form-control" name="phone_number" style="display: none;" id="phone_number" placeholder="Phone Number" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
        <textarea class="form-control" id="reserver_additional_message" style="display: none;"
                  name="reserver_message" maxlength="500">
        </textarea>
            </div>
            <button type="button" class="btn btn-primary btn-block" id="check_button">Check Availability</button>
            <button type="button" class="btn btn-primary btn-block" style="display: none;" id="book_table">Book a Table</button>
            <div id="error_message" class="ajax_response" style="float:left"></div>
            <div id="success_message" class="ajax_response" style="float:left"></div>

        </div>
    </section>

    @yield('content')


</div>

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


@yield('scripts')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Bootstrap Switch-->
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
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
<!-- Autocomplete on Search Plugin -->
<script src="{{ asset('js/autocomplete.js') }}"></script>
<script src="{{ asset('js/autocomplete.min.js') }}"></script>
<script src="{{ asset('autocomplete/jquery.easy-autocomplete.min.js') }}"></script>
<!--
<script src="{{ asset('jquery-3.3.1.js') }}" type="text/javascript"></script>-->
<script type="application/javascript">

    $(document).ready(function(){

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

        const get_all_restaurants = "{{ route('get_all_restaurants') }}";
        $.ajax({
            url: get_all_restaurants,
            type: 'get',
            dataType: 'json',
            success:function(all_restaurants){
                //console.log(all_restaurants);
                //const restaurants = $.parseJSON(all_restaurants);
                //console.log(restaurants);
                var options = {
                    data: all_restaurants,
                    getValue: "name",
                    list: {
                        match: {
                            enabled: true
                        }
                    }
                };

                $("#search-form").easyAutocomplete(options);

            }

        });
        return false;

    });

</script>
<script>
    $(document).ready(function(){

        $('#check_button').click(function() {

            const check_url = "{{ route('check_availability')}}";
            const _token = $("input#_token").val();
            const reservation_date = $("#reservation_date").val();
            const reservation_time = $("#reservation_time").val();
            const number_of_people = $("#number_of_people").val();
            const Restaurant_name = $("#search-form").val();

            console.log(reservation_date);
            console.log(reservation_time);
            console.log(number_of_people);
            console.log(check_url);
            console.log(_token);

            $.ajax({

                url:check_url,

                method:"POST",

                data: {

                    _token: _token,

                    reservation_date: reservation_date,

                    reservation_time: reservation_time,

                    number_of_people: number_of_people,

                    Restaurant_name: Restaurant_name

                },
                success:function(availability){
                    //success data
                    const value = availability.value;

                    //if restaurant not found
                    if(value === "restaurant not found")
                    {
                        toastr.error(availability.message);
                    }
                    if(value === "closed")
                    {
                        toastr.error(availability.message);
                    }
                    if (value === "full") {
                        toastr.error(availability.message);
                    } else {

                        //if table is not booked
                        if (value === "empty") {
                            const route = availability.route;
                            window.location.replace(route);
                            toastr.info(availability.message);
                            //alert(availability.data);
                            //const limit = 8;
                            /**for (var i  = 0; i > limit; i++)
                             {
                              var time_slot = reservation_time + '00:30';
                              console.log(time_slot);
                          }*/

                            $("#reserver_additional_message").show();
                            $("#reserver_email").show();
                            $("#user_full_name").show();
                            $("#phone_number").show();
                            $("#check_button").hide();
                            $("#book_table").show();


                            //if table is not booked execute this
                            $("#book_table").click(function () {

                                const reserver_message = $("#reserver_additional_message").val();
                                const _token = $("input#_token").val();
                                const number_of_people = $("#number_of_people").val();
                                const reservation_date = $("#reservation_date").val();
                                const reservation_time = $("#reservation_time").val();
                                const book_url = "{{ route('book_table')}}";
                                const Restaurant_name = $("#search-form").val();
                                const user_name = $("#user_full_name").val();
                                const phone_number = $("#phone_number").val();
                                const user_email = $("#reserver_email").val();
                                if (reserver_message.length > 500) {
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

                                        Restaurant_name: Restaurant_name,

                                        user_name: user_name,

                                        phone_number: phone_number,

                                        user_email: user_email

                                    },
                                    success: function (booked) {
                                        if (booked.value === "login") {
                                            toastr.error(booked.message);
                                            window.location.replace("{{ route('login') }}");
                                        }
                                        toastr.success('Table Booked Successfully.');
                                        window.location.reload();
                                    }
                                });

                            });

                        }
                        //if table is booked execute this
                        if (value === "booked") {
                            toastr.success('Table Booked Successfully.');
                            window.location.reload();
                        }

                    }

                },
            });

        });
    });

</script>

</body>
</html>
