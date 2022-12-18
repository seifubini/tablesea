@extends('layouts.admin_header')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Guests </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Guests</li>
                    </ol>
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

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            <p>{{ $message }}</p>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <p>{{ $message }}</p>
        </div>
    @endif


    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Default box -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if(Auth::user()->user_type != "Administrator" || Auth::user()->user_type != "Client" || Auth::user()->user_type != "Booking_Manager")
                                    <button type="button" class="btn btn-block btn-default" data-toggle="modal" data-target="#modal-lg" style="background-color: #0065A3; color: #fff;">
                                        Add New Guest
                                    </button>
                                @endif
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Full Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Guest Type</th>
                                    <th>Membership Tag</th>
                                    <th>Total Reservations</th>
                                    <th>Total Spent</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($guests as $guest)
                                    <tr class="open_data" id="{{ $guest->id }}" data-toggle="modal" data-target="#modal-xl_details{{ $guest->id }}">
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            {{ $guest->guest_title }} {{ $guest->user_name }}
                                        </td>
                                        <td>
                                        {{ $guest->email }}

                                        <!--Reservations Modal Starts Here-->
                                            <div class="modal fade" id="modal-xl_reservations{{ $guest->id }}">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Reservation History</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="content">
                                                                <div class="container-fluid">
                                                                    <div class="row">
                                                                        <!-- Default box -->
                                                                        <div class="col-lg-12">
                                                                            <div class="card">
                                                                                <div class="card-header">
                                                                                    <h3 class="card-title">

                                                                                    </h3>
                                                                                </div>
                                                                                <!-- /.card-header -->
                                                                                <div class="card-body">
                                                                                    <table id="example1{{ $guest->id }}" class="table table-bordered table-striped">
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>No</th>
                                                                                            <th>Reservation Code</th>
                                                                                            <th>Date of Reservation</th>
                                                                                            <th>Time of Reservation</th>
                                                                                            <th>Duration</th>
                                                                                            <th>Number of People</th>
                                                                                            <th>Total Spent</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody id="data_row{{$guest->id}}">

                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                        <tr>
                                                                                            <th>No</th>
                                                                                            <th>Reservation Code</th>
                                                                                            <th>Date of Reservation</th>
                                                                                            <th>Time of Reservation</th>
                                                                                            <th>Duration</th>
                                                                                            <th>Number of People</th>
                                                                                            <th>Total Spent</th>
                                                                                        </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.Reservations modal end here-->

                                        </td>
                                        <td>
                                            {{ $guest->phone_number }}
                                        </td>
                                        <td>
                                            {{ $guest->user_tag }}
                                        </td>

                                        <td>
                                            {{ $guest->membership_tag }}

                                        </td>

                                        <td> {{$guest->number_of_reservations}}</td>

                                        <td>{{ $guest->total_spent}}</td>

                                        <!-- Guest Detail box -->
                                        <div class="modal fade" id="modal-xl_details{{ $guest->id }}">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                                                        <h4 class="modal-title">{{ $guest->user_tag }} {{ $guest->user_name }} Details </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="col-12 col-md-12 order-2 order-md-1">
                                                                <div class="row" style="width: 100%;">
                                                                    <div class="col-12 col-sm-2">
                                                                        <div class="info-box" style="background-color: #0065A3;" id="reservations{{ $guest->id }}"
                                                                             data-toggle="modal" data-target="#modal-xl_reservations{{ $guest->id }}">
                                                                            <div class="info-box-content">
                                                                                <span class="info-box-text text-center text-white text-muted">Reservations</span>
                                                                                <span class="info-box-number text-center text-white text-muted mb-0" id="no_of_reservations{{ $guest->id }}">
                                                                            </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-2">
                                                                        <div class="info-box" style="background-color: #0065A3;">
                                                                            <div class="info-box-content">
                                                                                <span class="info-box-text text-center text-white text-muted">Upcoming</span>
                                                                                <span class="info-box-number text-center text-white text-muted mb-0" id="no_of_upcomings{{ $guest->id }}">
                                                                            </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-2">
                                                                        <div class="info-box" style="background-color: #0065A3;">
                                                                            <div class="info-box-content">
                                                                                <span class="info-box-text text-center text-white text-muted">Cancelled</span>
                                                                                <span class="info-box-number text-center text-white text-muted mb-0" id="no_of_cancelled{{ $guest->id }}">
                                                                            </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 col-sm-2">
                                                                        <div class="info-box" style="background-color: #0065A3;">
                                                                            <div class="info-box-content">
                                                                                <span class="info-box-text text-center text-white text-muted">Cover</span>
                                                                                <span class="info-box-number text-center text-white text-muted mb-0" id="covered{{ $guest->id }}">
                                                                            </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-2">
                                                                        <div class="info-box" style="background-color: #0065A3;">
                                                                            <div class="info-box-content">
                                                                                <span class="info-box-text text-center text-white text-muted">Spend</span>
                                                                                <span class="info-box-number text-center text-white text-muted mb-0" id="total_spent{{ $guest->id }}">
                                                                            </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-2">
                                                                        <div class="info-box" style="background-color: #0065A3;">
                                                                            <div class="info-box-content">
                                                                                <span class="info-box-text text-center text-white text-muted">Completed</span>
                                                                                <span class="info-box-number text-center text-white text-muted mb-0" id="no_of_denied{{ $guest->id }}">
                                                                            </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <form method="POST" action="{{ route('guest.update', $guest->id) }}" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div>
                                                                                <h4>Basic Information</h4>
                                                                            </div>
                                                                            <div class="post">
                                                                                <div class="user-block">
                                                                                    <img class="img-circle img-bordered-sm" src="{{ asset('images/user_avatar.jpeg') }}" alt="user image">
                                                                                    <span class="username">
                                                                          <h5>{{ $guest->user_name }}</h5>
                                                                        </span>
                                                                                    <span class="description">
                                                                            Member Since {{ date_format(date_create($guest->created_at), 'jS M Y')}}
                                                                        </span>
                                                                                </div>
                                                                                <!-- /.user-block -->
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="text-muted">
                                                                                            <div class="form-group">
                                                                                                <label for="exampleInputEmail1">Full Name</label>
                                                                                                <input type="text" class="form-control" name="user_name" id="user_name{{ $guest->id }}" value="{{ $guest->user_name }}">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label for="exampleInputEmail1">Phone Number</label>
                                                                                                <input type="text" class="form-control" name="phone_number" id="phone_number{{ $guest->id }}" value="{{ $guest->phone_number }}">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="text-muted">
                                                                                            <div class="form-group">
                                                                                                <label for="exampleInputEmail1">Title</label>
                                                                                                <input type="text" class="form-control" name="guest_title" id="guest_title{{ $guest->id }}" value="{{$guest->guest_title}}">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label for="exampleInputEmail1">Email</label>
                                                                                                <input type="email" class="form-control" name="email" id="guest_email{{ $guest->id }}" value="{{$guest->email}}">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" name="restaurant_id" value="{{$restaurant_id}}" id="restaurant_id">
                                                                                <div class="text-muted">
                                                                                    <b class="d-block"> Guest Note </b>
                                                                                    <textarea class="form-control" name="guest_note" id="guest_note{{ $guest->id }}" >{{ $guest->guest_note }}</textarea>
                                                                                </div>
                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Guest Type</label>
                                                                                    <select class="custom-select" id="exampleSelectBorder{{ $guest->id }}" name="guest_tag">
                                                                                        <option value="{{$guest->membership_tag}}"></option>
                                                                                        @foreach($guest_types as $guest_type)
                                                                                            <option value="{{$guest_type->guest_type_name}}">{{$guest_type->guest_type_name}}</option>
                                                                                        @endforeach

                                                                                    </select>
                                                                                </div>

                                                                                <!-- select -->
                                                                                <div class="form-group">
                                                                                    <label>Membership Type</label>
                                                                                    <select class="custom-select" name="membership_tag" id="membership_tag">
                                                                                        <option value="{{$guest->membership_tag}}"></option>
                                                                                        @foreach($memberships as $membership)
                                                                                            <option value="{{ $membership->membership_name }}">{{ $membership->membership_name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>


                                                                            @if(Auth::user()->user_type != "Administrator" || Auth::user()->user_type != "Client" || Auth::user()->user_type != "Booking_Manager")
                                                                                <div class="card-footer">
                                                                                    <button type="submit" class="btn btn-custom btn-block" style="background-color: #0065A3; color: #fff;">
                                                                                        Update</button>
                                                                                </div>
                                                                        </form>
                                                                        <div class="card-footer text-center">
                                                                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#modal-danger{{ $guest->id }}">
                                                                                Delete
                                                                            </button>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /. Guest details modal -->

                                        <!--Delete Modal Starts Here-->
                                        <div class="modal fade" id="modal-danger{{ $guest->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-danger">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Are you sure you want to delete guest <br> <strong> {{ $guest->user_name }}</strong> ?</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('guest.destroy',$guest->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        @if(Auth::user()->user_type != "Administrator" || Auth::user()->user_type != "Client" || Auth::user()->user_type != "Booking_Manager")
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-outline-light">Delete</button>
                                                            </div>
                                                        @endif
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.delete modal -->

                                    </tr>


                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Full Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Guest Tag</th>
                                    <th>Membership Tag</th>
                                    <th>Total Reservations</th>
                                    <th>Total Spent</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>

    <!--Create new Guest Modal starts here -->
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                    <h4 class="modal-title">Create new Guest</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('guest.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="post">
                            <!-- /.user-block -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Full Name</label>
                                            <input type="text" class="form-control" name="user_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Phone Number</label>
                                            <input type="text" class="form-control" name="phone_number" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Title</label>
                                            <input type="text" class="form-control" name="guest_title" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="restaurant_id" value="{{$restaurant_id}}">

                            <br>
                            <!-- select -->
                            <div class="form-group">
                                <label> Guest Tag</label>
                                <select class="custom-select" id="exampleSelectBorder" name="user_tag">
                                    <option value="regular_guest" selected>Regular Guest</option>
                                    <option value="loyal_guest">Loyal Guest</option>
                                    <option value="vvip">VVIP</option>
                                    <option value="high_spender">High Spender</option>
                                    <option value="vip">VIP</option>
                                    <option value="angry_customer">Angry Customer</option>
                                    <option value="happy_customer">Happy Customer</option>
                                </select>
                            </div>

                            <!-- select -->
                            <div class="form-group">
                                <label>Membership Type</label>
                                <select class="custom-select" name="membership_tag" id="membership_tag">
                                    <option id="suggested_membership"></option>
                                    @foreach($memberships as $membership)
                                        <option value="{{ $membership->membership_name }}">{{ $membership->membership_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-muted">
                                <b class="d-block"> Guest Note </b>
                                <textarea class="form-control" name="guest_note"></textarea>
                            </div>


                        </div>
                        @if(Auth::user()->user_type != "Administrator" || Auth::user()->user_type != "Client" || Auth::user()->user_type != "Booking_Manager")
                            <div class="card-footer">
                                <button type="submit" class="btn btn-custom btn-block" style="background-color: #0065A3; color: #fff;">
                                    Add Guest</button>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('scripts')

<script type="application/javascript">

    $(".open_data").click( function (){
        var guest_id = $(this).attr('id');

        const restaurant_id = $("#restaurant_id").val();
        const id = guest_id;
        const get_reservations_url = "{{ route('get_reservations')}}";
        const get_upcoming_url = "{{ route('get_upcoming') }}";
        const get_cancelled_url = "{{ route('get_cancelled') }}";
        const get_denied_url = "{{ route('get_denied') }}";
        const get_covered_url = "{{ route('get_cover') }}";
        const get_total_spent_url = "{{ route('get_total_spend') }}";

        //get number of total reservations
        $.ajax({
            url: get_reservations_url,
            method:"POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                restaurant_id: restaurant_id
            },
            success: function(reservations){
                const data = $.parseJSON(reservations);

                const no_of_reservations = data.length;

                $("#no_of_reservations"+guest_id).text(no_of_reservations);
            }
        });

        //get number of upcoming reservations
        $.ajax({
            url: get_upcoming_url,
            method:"POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                restaurant_id: restaurant_id
            },
            success: function(upcoming){
                const data = $.parseJSON(upcoming);

                const no_of_upcoming = data.length;
                $("#no_of_upcomings"+guest_id).text(no_of_upcoming);
            }
        });

        //get number of cancelled reservations
        $.ajax({
            url: get_cancelled_url,
            method:"POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                restaurant_id: restaurant_id
            },
            success: function(cancelled){
                const data = $.parseJSON(cancelled);

                const no_of_cancelled = data.length;
                $("#no_of_cancelled"+guest_id).text(no_of_cancelled);
            }
        });

        //get number of denied reservations
        $.ajax({
            url: get_denied_url,
            method:"POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                restaurant_id: restaurant_id
            },
            success: function(denied){
                const data = $.parseJSON(denied);

                const no_of_denied = data.length;
                $("#no_of_denied"+guest_id).text(no_of_denied);
            }
        });

        //get number of covered seats
        $.ajax({
            url: get_covered_url,
            method:"POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                restaurant_id: restaurant_id
            },
            success: function(denied){
                const data = $.parseJSON(denied);

                $("#covered"+guest_id).text(data);
            }
        });

        //get total amount of money spend
        $.ajax({
            url: get_total_spent_url,
            method:"POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                restaurant_id: restaurant_id
            },
            success: function(denied){
                const data = $.parseJSON(denied);

                $("#total_spent"+guest_id).text(data);
            }
        });

        $("#modal-xl_details"+guest_id).show();
        //populate the reservations history modal
        $("#reservations"+guest_id).click( function (e) {
            e.preventDefault();

            const restaurant_id = $("#restaurant_id").val();
            const id = guest_id;
            const get_reservations_url = "{{ route('get_reservations')}}";


            $.ajax({
                url: get_reservations_url,
                method:"POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    restaurant_id: restaurant_id
                },
                success: function(reservations){
                    const data = $.parseJSON(reservations);

                    const len = data.length;

                    var wrapper = $("tbody #data_row"+guest_id);

                    for(let f = 0; f<len; f++ ){

                        const no = f + 1;
                        const reservation_code = data[f]['reservation_code'];
                        const date = data[f]['date_of_reservation'];
                        const time = data[f]['time_of_reservation'];
                        const people = data[f]['number_of_people'];
                        const duration = data[f]['reservation_duration'];
                        const cost = data[f]['reservation_total_cost'];

                        $(wrapper).append("<tr>");
                        $(wrapper).append("<td>" + no + "</td>");
                        $(wrapper).append("<td>" + reservation_code + "</td>");
                        $(wrapper).append("<td>" + date + "</td>");
                        $(wrapper).append("<td>" + time + "</td>");
                        $(wrapper).append("<td>" + people + "</td>");
                        $(wrapper).append("<td>" + duration + "</td>");
                        $(wrapper).append("<td>" + cost + "</td>");
                        $(wrapper).append("</tr>");

                    }
                    $("#modal-xl_reservations"+guest_id).show();

                }
            });
        });

    });

    $(function()
    {

    });

    //$(document).ready(function(){
    //});
</script>

@endsection
