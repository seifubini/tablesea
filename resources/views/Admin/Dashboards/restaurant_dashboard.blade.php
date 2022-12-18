@extends('layouts.restaurant_dashboard_header')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">{{ $restaurant->Restaurant_name }} Dashboard</h4>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Home</a></li>
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

    @if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager" || Auth::user()->user_type == "Administrator")

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box" style="background-color: #0065A3; color: #fff;">
                        <div class="inner">
                            <h3 style="color: #fff;">{{ $online_reservations }}</h3>
                            <p> Online Reservations </p>
                        </div>
                        <div class="icon">
                            <!-- <i class="ion ion-bag"></i>-->
                            <i class="fa fa-globe" ></i>
                        </div>
                        <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box" style="background-color: #fff; color: #0065A3;">
                        <div class="inner">
                            <h3>{{ $walkin_reservations }}</h3>

                            <p>WalkIn Reservations </p>
                        </div>
                        <div class="icon">
                            <!--<i class="ion ion-"></i>-->
                            <i class="fas fa-walking"></i>
                        </div>
                        <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box" style="background-color: #0065A3; color: #fff;">
                        <div class="inner">
                            <h3>{{ $total_covers }}</h3>

                            <p>Total Completed Covers</p>
                        </div>
                        <div class="icon">
                            <!--<i class="ion ion-person-add"></i> -->
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </div>
                        <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box" style="background-color: #fff; color: #0065A3;">
                        <div class="inner">
                            <h3>{{ $number_of_tables }}</h3>

                            <p>Revenue Collected </p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-6 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <!-- Reservations BAR CHART -->
                    <div class="card card-default">
                        <div class="card-header" style="background-color: #0065A3; color: #fff;">
                            <h3 class="card-title">Reservations Graph</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="stackedBarChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-6 connectedSortable">

                    <!-- Reservations BAR CHART -->
                    <div class="card card-default">
                        <div class="card-header" style="background-color: #0065A3; color: #fff;">
                            <h3 class="card-title">Revenues Graph</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="stackedBarChartRevenues" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </section>
                <!-- right col -->

                <!-- Default box -->
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar" style="height: 300px;"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <!-- Default box -->
                <div class="col-lg-12">
                    <div class="card">
                        <!-- All Reservations Table -->
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-head-fixed text-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Restaurant Name</th>
                                    <th>Reserved Date</th>
                                    <th>Reservation Code</th>
                                    <th>Reserved Time</th>
                                    <th>No of People</th>
                                    <th>Reserved By</th>
                                    <th>Guest Email</th>
                                    <th>Guest Phone</th>
                                    <th>Created By</th>
                                    <th>Reservation Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($total_reservations as $reservation)
                                    <tr class="id_of_reservation" id="{{$reservation->id}}">
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $reservation->Restaurant_name }}</td>
                                        <td>{{ date_format(date_create($reservation->date_of_reservation), 'jS M Y')}}</td>
                                        <td>{{ $reservation->reservation_code }}</td>
                                        <td>{{ date('h:i A', strtotime($reservation->time_of_reservation)) }}</td>
                                        <td>{{ $reservation->number_of_people}}</td>
                                        <td>{{ $reservation->user_name}}</td>
                                        <td>{{ $reservation->user_email}}</td>
                                        <td>{{ $reservation->user_phone}}</td>
                                        <td>{{ $reservation->creater_name}}</td>
                                        @if($reservation->reservation_status == 'confirmed')
                                            <td><span class="right badge badge-success">Confirmed</span></td>
                                        @elseif($reservation->reservation_status == 'booked')
                                            <td><span class="right badge badge-info">Booked</span></td>
                                        @elseif($reservation->reservation_status == 'cancelled')
                                            <td><span class="right badge badge-danger">Cancelled</span></td>
                                        @elseif($reservation->reservation_status == 'denied')
                                            <td><span class="right badge badge-danger">Denied</span></td>
                                        @elseif($reservation->reservation_status == 'late')
                                            <td><span class="right badge badge-warning">Arrive Late</span></td>
                                        @elseif($reservation->reservation_status == 'Completed')
                                            <td><span class="right badge badge-custom" style="background-color: #0065A3; color: #fff;">
                                                Completed</span></td>
                                        @else
                                            <td><span class="right badge badge-danger">Empty</span></td>
                                        @endif
                                </tr>
                                @endforeach

                                </tbody>
                                <!-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Restaurant Name</th>
                                    <th>Reserved Date</th>
                                    <th>Reservation Code</th>
                                    <th>Reserved Time</th>
                                    <th>Number of People</th>
                                    <th>Reserved By</th>
                                    <th>Guest Email</th>
                                    <th>Guest Phone</th>
                                    <th>Reservation Status</th>
                                </tr>
                                </tfoot>-->
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <!-- Reservations Table End here -->
                    </div>
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Online Reservation Modal -->
    <div class="modal hide" id="modal-lg_online">
        <div class="modal-dialog modal-default">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                    <h4 class="modal-title">Update Online Reservation</h4>
                    <button type="button" class="close" id="close_modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- form start -->
                    <form method="POST" action="" id="reservation_update" enctype="multipart/form-data">
                        <!-- /.input group -->
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Guest Name</label>
                                    <div class="input-group">
                                        <input type="text" name="user_name" id="user_name_update" class="form-control" disabled>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fas fa-user"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Guest Email</label>
                                    <div class="input-group">
                                        <input type="email" name="user_email" id="user_email_update" class="form-control" disabled>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-envelop"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Guest Phone</label>
                                    <div class="input-group">
                                        <input type="text" name="user_phone" id="user_phone_update" class="form-control" disabled>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Date -->
                                <div class="form-group">
                                    <label>Reservation Date</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" disabled id="date_of_reservation_update" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Reservation Time</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="text" disabled id="time_of_reservation_update" class="form-control datetimepicker-input" data-target="#timepicker"/>
                                            <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- textarea -->
                                <div class="form-group">
                                    <label>Reservation Code</label>
                                    <p class="form-control" id="reservation_code_update">
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Covers</label>
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="number" min="1" id="people_update" name="number_of_people" class="form-control">
                                        </div>
                                        <div class="col-8">
                                            <button type="button" id="less_people_update" class="btn" style="border: none;">
                                                <i class="fas fa-minus-circle" style="background-color: #fff; color: #007BC9;"></i>
                                            </button>
                                            <button type="button" id="more_people_update" class="btn" style="border: none;">
                                                <i class="fas fa-plus-circle" style="background-color: #fff; color: #007BC9;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($rest_type == 'Automatic')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Duration in Minutes</label>
                                        <div class="row">
                                            <div class="col-4">
                                                <input type="text" id="online_booking_duration_update" name="reservation_duration" class="form-control" value="60">
                                            </div>
                                            <div class="col-8">
                                                <button type="button" id="online_booking_minus_update" class="btn" style="border: none;">
                                                    <i class="fas fa-minus-circle" style="background-color: #fff; color: #007BC9;"></i>
                                                </button>
                                                <button type="button" id="online_booking_plus_update" class="btn" style="border: none;">
                                                    <i class="fas fa-plus-circle" style="background-color: #fff; color: #007BC9;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <!-- select -->
                                <div class="form-group">
                                    <label>Guest Type</label>
                                    <select class="custom-select" name="reservation_tag" id="reservation_tag_update">
                                        @foreach($guest_types as $guest_type)
                                            <option value="{{$guest_type->guest_type_name}}">{{$guest_type->guest_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6" id="tables_list_update">
                                <div class="form-group">
                                    <label>Select Table</label>
                                    <select class="select2bs4" name="table_id[]" multiple="multiple" id="online_tables" data-placeholder="Select Table"
                                            style="width: 100%; list-style: none;">
                                        @foreach($free_tables as $free_table)
                                            <option value="{{ $free_table->id }}" class="form-control">
                                                {{$free_table->table_name}} ({{$free_table->min_covers}} - {{$free_table->max_covers}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if($rest_type == 'Automatic')
                                <div class="col-md-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="custom-select" name="reservation_status" id="reserved_status_update">
                                            <option value="booked">Booked</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="late">Late</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif($rest_type == 'Manual')
                                <div class="col-md-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="custom-select" name="reservation_status" id="reserved_status_update">
                                            <option value="booked">Booked</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="Completed">Completed</option>
                                            <option value="late">Late</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <!-- select -->
                                <div class="form-group">
                                    <label>Membership Type</label>
                                    <select class="custom-select" name="membership_tag" id="membership_tag_update">
                                        @foreach($memberships as $membership)
                                            <option value="{{ $membership->membership_name }}">{{ $membership->membership_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if($rest_type == 'Manual')
                                <div class="col-md-6" id="total_cost_value_update">
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label>Total Bill</label>
                                        <input type="number" class="form-control" name="reservation_total_cost" id="reservation_total_cost_update">
                                    </div>
                                </div>
                        @endif

                        <!--<div class="col-md-6">
                                         textarea
                                        <div class="form-group">
                                            <label>Additional Note</label>
                                            <p class="form-control">

                                            </p>
                                        </div>
                                    </div>-->

                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Additional Note</label>
                                <textarea class="form-control"
                                          name="reserver_message" id="reserver_message_update"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Guest Note</label>
                                <textarea class="form-control"
                                          name="hostess_note" readonly id="hostess_note_update"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputFile">File input</label>
                                    <div class="input-group">
                                      <div class="custom-file">
                                        <input type="file" name="reservation_attachment" class="custom-file-input" id="reservation_attachment">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                      </div>
                                    </div>
                              </div>
                              <br>
                            </div>
                        <hr>
                        <input type="hidden" name="restaurant_id" >
                        <input type="hidden" name="date_of_reservation" >
                        <input type="hidden" name="time_of_reservation" >
                        <input type="hidden" name="user_id">

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-custom btn-block" style="background-color: #007BC9; color: #fff;">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.online reservation modal end here-->

    <!-- WalkIn Reservation Modal -->
    <div class="modal hide" id="modal-lg_reservation">
        <div class="modal-dialog modal-default">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #28A745; color:#fff;">
                    <h4 class="modal-title">Create WalkIn Reservation</h4>
                    <button type="button" class="close" id="close_reservation" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('reservations.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email</label>
                                <div class="input-group" style="width: 100%;">
                                    <input type="text" class="form-control" id="Example-1"
                                           name="user_email" placeholder="Email Address" required style="width: 100%;">

                                </div>
                                <ul class="form-group" id="searchResult" style="color: #000000;"></ul>

                            </div>
                            <div class="col-md-6">
                                <label>User Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="user_name" placeholder="Full Name" id="suggested_name" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone Number</label>
                                    <input type="number" name="user_phone" class="form-control" id="suggested_phone" required>
                                </div>
                            </div>
                            <input type="hidden" name="reservation_type" value="walkin">
                            <div class="col-md-6">
                                <!-- Date -->
                                <div class="form-group">
                                    <label>Reservation Date</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="date" id="reservation_date" name="date_of_reservation" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Reservation Time</label>
                                        <div class="form-group">
                                            @if($hour_count > 0)
                                                <select class="custom-select" name="time_of_reservation" id="reservation_time">
                                                    @for($i = 0; $i < $hour_count; $i++)
                                                        <option value="{{$available_hours[$i]['hour']}}">
                                                            {{ date('h:i A', strtotime($available_hours[$i]['hour']))}} - {{$available_hours[$i]['hour_name'] }}</option>
                                                    @endfor
                                                </select>
                                            @else
                                                <select class="custom-select" name="time_of_reservation" id="reservation_time">
                                                    @foreach($free_hours as $free_hour)
                                                        <option value="{{$free_hour}}">{{ date('h:i A', strtotime($free_hour)) }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                            @if($rest_type == 'Automatic')
                                <div class="col-md-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="custom-select" name="reservation_status" id="status_of_reservation">
                                            <option value="booked">Booked</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="wait_list">Waitlist</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif($rest_type == 'Manual')
                                <div class="col-md-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="custom-select" name="reservation_status" id="status_of_reservation">
                                            <option value="booked">Booked</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="wait_list">Waitlist</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if($rest_type == 'Automatic')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Duration in Minutes</label>
                                        <div class="row">
                                            <div class="col-4">
                                                <input type="text" id="reservation_duration" name="reservation_duration" class="form-control" value="60">
                                            </div>
                                            <div class="col-8">
                                                <button type="button" id="minus_button" class="btn" style="border: none;">
                                                    <i class="fas fa-minus-circle" style="background-color: #fff; color: #28A745;"></i>
                                                </button>
                                                <button type="button" id="plus_button" class="btn" style="border: none;">
                                                    <i class="fas fa-plus-circle" style="background-color: #fff; color: #28A745;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif

                            <div class="col-md-6">
                                <!-- select -->
                                <div class="form-group">
                                    <label>Guest Type</label>
                                    <select class="custom-select" name="reservation_tag">
                                        @foreach($guest_types as $guest_type)
                                            <option value="{{$guest_type->guest_type_name}}">{{$guest_type->guest_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Covers</label>
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="number" id="number_of_people" value="1" min="1" name="number_of_people" class="form-control" required>
                                        </div>
                                        <div class="col-8">
                                            <button type="button" id="minus_people" class="btn" style="border: none;">
                                                <i class="fas fa-minus-circle" style="background-color: #fff; color: #28A745;"></i>
                                            </button>
                                            <button type="button" id="plus_people" class="btn" style="border: none;">
                                                <i class="fas fa-plus-circle" style="background-color: #fff; color: #28A745;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="restaurant_id" id="restaurant_id" value="{{$restaurant_id}}">

                            <div class="col-md-6" id="table_walkin">
                                <div class="form-group">
                                    <label>Select Table</label>
                                    <select class="select2bs4" name="table_id[]" multiple="multiple" id="walkin_tables" data-placeholder="Select Table"
                                            style="width: 100%; list-style: none;">
                                        @foreach($free_tables as $free_table)
                                            <option value="{{ $free_table->id }}" class="form-control">
                                                {{$free_table->table_name}} ({{$free_table->min_covers}} - {{$free_table->max_covers}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
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
                            </div>

                            @if($rest_type == 'Manual')
                            <!-- <div class="col-md-6">-->
                                <!-- textarea
                                <div class="form-group">
                                    <label>Total Bill</label>
                                    <input type="number" class="form-control" name="reservation_total_cost">
                                </div>
                            </div>-->
                            @endif

                            <input type="hidden" name="created_by" value="{{ Auth::user()->id }}" style="display: none;">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Created By</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" name="creater_name" readonly>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Additional Note</label>
                                    <textarea class="form-control" name="reserver_message"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Guest Note</label>
                                    <textarea class="form-control" name="hostess_note" id="suggested_hostess_note"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputFile">File input</label>
                                    <div class="input-group">
                                      <div class="custom-file">
                                        <input type="file" name="reservation_attachment" class="custom-file-input" id="reservation_attachment">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                      </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-custom btn-block" style="background-color: #28A745; color: #fff;">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    @elseif(Auth::user()->user_type == "Booking_Manager")

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          <div class="col-sm-6">
            <h5 class="m-0">Select a restaurant to continue.</h5>
          </div><!-- /.col -->
          <br>
          <br>

          <img src="{{ asset('images/TableSeaNew.jpg')}}" width="100%" style="opacity: .8; padding: 0; height: auto; max-height: 150px; object-fit: contain;">

      </div>
      </section>

    @endif

    <!-- /.content-wrapper -->
    <input type="hidden" id="get_all_guests" value="{{ route('get_all_guests', $restaurant_id) }}" style="display: none;">
@endsection
@section('scripts')

<script>

    $(document).ready(function (e){
        $(".id_of_reservation").click( function(){

            document.getElementById('cover-spin').style.display = "flex";

            setTimeout(function(){
                $('#cover-spin').css('display', 'none');
            }, 3000);

            var reservation_id = $(this).attr('id');

            var get_reservation = "{{route('get_reservation', '')}}"+"/"+reservation_id;
                var update_reservation = "{{ route('reservations.update', '') }}"+"/"+reservation_id;

                $.ajax({
                    url: get_reservation,
                    type: 'GET',
                    dataType: 'json', // added data type
                    success: function(reservation) {
                        $("#reservation_update").attr('action', update_reservation);
                        $("#user_name_update").val(reservation['user_name']);
                        $("#user_email_update").val(reservation['user_email']);
                        $("#user_phone_update").val(reservation['user_phone']);
                        $("#date_of_reservation_update").val(reservation['date_of_reservation']);
                        $("#time_of_reservation_update").val(reservation['time_of_reservation']);
                        $("#reservation_code_update").text(reservation['reservation_code']);
                        $("#people_update").val(reservation['number_of_people']);
                        $("#online_booking_duration_update").val(reservation['reservation_duration']);
                        $("#reservation_tag_update").val(reservation['reservation_tag']);
                        $("#reserved_status_update").val(reservation['reservation_status']);
                        $("#manual_status").val(reservation['reservation_status']);
                        $("#membership_tag_update").val(reservation['membership_name']);
                        $("#reservation_total_cost_update").val(reservation['reservation_total_cost']);
                        $("#reserver_message_update").text(reservation['reserver_message']);
                        $("#hostess_note_update").text(reservation['hostess_note']);

                        $("#modal-lg_online").modal('show');
                    }
                });

        });

    });

    $(document).ready(function(e){

        if($('#status_of_reservation').val() === 'booked'){
            $('#table_walkin').hide();
        }
        if($('#status_of_reservation').val() === 'wait_list'){
            $('#table_walkin').hide();
        }

        e.preventDefault;

        $(function(){
            const get_all_guests = $("#get_all_guests").val();
            console.log(get_all_guests);

            $.ajax({
                url: get_all_guests,
                type: 'get',
                dataType: 'json',
                success:function(all_guests){
                    console.log(all_guests);

                    var options = {
                        data: all_guests,
                        getValue: "email",
                        list: {
                            match: {
                                enabled: true
                            }
                        }
                    };

                    $("#Example-1").easyAutocomplete(options);
                    $(".eac-item").css('color', '#000000');
                    $(".easy-autocomplete").css('width', '100%');

                }

            });
        });

        $("#Example-1").change(function (){

            var email = $("#Example-1").val();
            const find_guest_url = '{{ route('find_guest') }}';
            var restaurant_id = $("#restaurant_id").val();

            $.ajax({
                url: find_guest_url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    restaurant_id: restaurant_id,
                    email: email
                },
                success: function (guest) {
                    if(guest !== null)
                    {
                        var guests = $.parseJSON(guest);

                        var guest_name = guests['user_name'];
                        var guest_phone = guests['phone_number'];
                        var guest_tag = guests['user_tag'];
                        var guest_membership = guests['membership_tag'];
                        var guest_hostess_note = guests['guest_note'];

                        $("#suggested_name").val(guest_name);
                        $("#suggested_phone").val(guest_phone);
                        $("#suggested_membership").val(guest_membership);
                        $("#membership_tag").val(guest_membership);
                        $("#guest_tag").val(guest_tag);
                        $("#suggested_hostess_note").text(guest_hostess_note);
                        //$("#suggested_hostess_note").prop('disabled', true);
                    }

                }
            });


        });

    });

    $(document).ready(function (){

        $('#minus_button').click(function (e) {
            e.preventDefault();
            const min_value = parseInt($("#reservation_duration").val());
            const duration_value = min_value - 15;
            $('#reservation_duration').val(duration_value);
        });

        $('#plus_button').click(function (e) {
            e.preventDefault();
            const max_value = parseInt($("#reservation_duration").val());
            const duration_value = max_value + 15;
            $('#reservation_duration').val(duration_value);
        });

        $("#status_of_reservation").change( function () {
            var status = $("#status_of_reservation").val();
            if(status === 'booked')
            {
                $("#table_walkin").hide();
            }
            if(status === 'wait_list')
            {
                $("#table_walkin").hide();
            }
            else{
                $("#table_walkin").show();
            }
        });

    });

    $(document).ready(function (){

        // for creating new modal
        $('#plus_people').click(function (e) {
            e.preventDefault();
            const value = parseInt($("#number_of_people").val());
            const plus_value = value + 1;

            $("#number_of_people").val(plus_value);
        });

        $('#minus_people').click(function (e) {
            e.preventDefault();
            const value = parseInt($("#number_of_people").val());
            if (value > 1) {
                const plus_value = value - 1;
                $("#number_of_people").val(plus_value);
            }
        });

        if($('#reserved_status').val() === 'booked' || $('#reserved_status').val() === 'wait_list' ){
            $('#tables_list_update').css('display', 'none');
            $('#total_cost_value').css('display', 'none');
        }
        $('#reserved_status').change(function (){
            var status_val = $('#reserved_status').val();

            if(status_val === 'booked')
            {
                $('#tables_list_update').css('display', 'none');
                $('#total_cost_value').css('display', 'none');
            }
            if(status_val === 'wait_list')
            {
                $('#tables_list_update').css('display', 'none');
                $('#total_cost_value').css('display', 'none');
            }
            if(status_val === 'confirmed'){
                $('#tables_list_update').css('display', 'flex');
                $('#total_cost_value').css('display', 'none');
            }
            if(status_val === 'Completed'){
                $('#total_cost_value').css('display', 'flex');
                $('#tables_list_update').css('display', 'flex');
            }
            else{

            }

        });
    });

    $(document).ready(function(e){
        e.preventDefault;

        $('select[multiple]').multiselect();

        $('#walkin_tables').multiselect({
            columns: 1,
            placeholder: 'Select Tables',
            search: true
        });

        $('#online_tables').multiselect({
            columns: 1,
            placeholder: 'Select Tables',
            search: true
        });

        //for the update modal
        $('#online_booking_minus_update').click(function (e) {
            e.preventDefault;
            const min_value = parseInt($("#online_booking_duration_update").val());
            const duration_value = min_value - 15;
            $('#online_booking_duration_update').val(duration_value);
        });

        $('#online_booking_plus_update').click(function (e) {
            e.preventDefault;
            const max_value = parseInt($("#online_booking_duration_update").val());
            const duration_value = max_value + 15;
            $('#online_booking_duration_update').val(duration_value);
        });

        $('#more_people_update').click(function () {

            const value = parseInt($("#people_update").val());
            const plus_value = value + 1;

            $("#people_update").val(plus_value);
        });

        $('#less_people_update').click(function () {

            const value = parseInt($("#people_update").val());
            if (value > 1) {
                const plus_value = value - 1;
                $("#people_update").val(plus_value);
            }
        });

        if($('#reserved_status_update').val() === 'booked' || $('#reserved_status_update').val() === 'wait_list' ){
            $('#tables_list_update').css('display', 'none');
            $('#total_cost_value_update').css('display', 'none');
        }
        $('#reserved_status_update').change(function (){
            var status_val = $('#reserved_status_update').val();

            if(status_val === 'booked')
            {
                $('#tables_list_update').css('display', 'none');
                $('#total_cost_value_update').css('display', 'none');
            }
            if(status_val === 'wait_list')
            {
                $('#tables_list_update').css('display', 'none');
                $('#total_cost_value_update').css('display', 'none');
            }
            if(status_val === 'confirmed'){
                $('#tables_list_update').css('display', 'flex');
                $('#total_cost_value_update').css('display', 'none');
            }
            if(status_val === 'Completed'){
                $('#total_cost_value').css('display', 'flex');
                $('#total_cost_value_update').css('display', 'flex');
            }
            else{
                $('#tables_list_update').css('display', 'none');
                $('#total_cost_value_update').css('display', 'none');
            }

        });

        $("#close_modal").click( function(){
            $("#modal-lg_online").hide();
        });

        $("#close_reservation").click( function(){
           $("#modal-lg_reservation").hide();
        });

    });

    $(function (e) {
        e.preventDefault;

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear();

        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');

        // initialize the external events
        // -----------------------------------------------------------------
        var reservations = <?php echo json_encode($calendar_data); ?>;
        const events = $.parseJSON(reservations);
        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left  : 'prev,next today',
                center: 'title',
                right : 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap',
            //Random default events
            events: events,
            editable  : false,
            //on click edit reservation info
            eventClick: function(info) {

                document.getElementById('cover-spin').style.display = "flex";

                setTimeout(function(){
                    $('#cover-spin').css('display', 'none');
                }, 4000);

                var reservation_id = info.event.id;

                var get_reservation = "{{route('get_reservation', '')}}"+"/"+reservation_id;
                var update_reservation = "{{ route('reservations.update', '') }}"+"/"+reservation_id;

                $.ajax({
                    url: get_reservation,
                    type: 'GET',
                    dataType: 'json', // added data type
                    success: function(reservation) {
                        $("#reservation_update").attr('action', update_reservation);
                        $("#user_name_update").val(reservation['user_name']);
                        $("#user_email_update").val(reservation['user_email']);
                        $("#user_phone_update").val(reservation['user_phone']);
                        $("#date_of_reservation_update").val(reservation['date_of_reservation']);
                        $("#time_of_reservation_update").val(reservation['time_of_reservation']);
                        $("#reservation_code_update").text(reservation['reservation_code']);
                        $("#people_update").val(reservation['number_of_people']);
                        $("#online_booking_duration_update").val(reservation['reservation_duration']);
                        $("#reservation_tag_update").val(reservation['reservation_tag']);
                        $("#reserved_status_update").val(reservation['reservation_status']);
                        $("#manual_status").val(reservation['reservation_status']);
                        $("#membership_tag_update").val(reservation['membership_name']);
                        $("#reservation_total_cost_update").val(reservation['reservation_total_cost']);
                        $("#reserver_message_update").text(reservation['reserver_message']);
                        $("#hostess_note_update").text(reservation['hostess_note']);

                        $("#modal-lg_online").modal('show');
                    }
                });
            },
            //on click create new reservation
            dateClick: function(info) {
                var dtToday = new Date();

                var month = dtToday.getMonth() + 1;
                var day = dtToday.getDate();
                var year = dtToday.getFullYear();

                var maxDate = year + '-' + month + '-' + day;
                //var clicked_date = info.dateStr;
                var today = new Date(year, month - 1, day);

                var clicked_date = new Date(info.dateStr);

                if(clicked_date >= today)
                {
                    $("#reservation_date").val(clicked_date);

                    $("#modal-lg_reservation").modal('show');
                }
                else{
                    toastr.error("you can't reserve a past date");
                }
            },
            droppable : false, // this allows things to be dropped onto the calendar !!!
            drop      : function(info) {
                // is the "remove after drop" checkbox checked?
                if (checkbox.checked) {
                    // if so, remove the element from the "Draggable Events" list
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                }
            }
        });

        calendar.render();

    });

    $(document).ready(function () {

        $(function (e) {
            e.preventDefault;

            var online_reservation_data = <?php echo json_encode($online_reservation_data); ?>;
            const online_data = $.parseJSON(online_reservation_data);
            var walkin_reservation_data = <?php echo json_encode($walkin_reservation_data); ?>;
            const walkin_data = $.parseJSON(walkin_reservation_data);

            var stackedBarChartData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    {
                        label: 'Online Reservations',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: online_data
                    },
                    {
                        label: 'WalkIn Reservations',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: walkin_data
                    },
                ]
            };

            //---------------------
            //- STACKED BAR CHART -
            //---------------------
            var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');

            var stackedBarChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            };

            new Chart(stackedBarChartCanvas, {
                type: 'bar',
                data: stackedBarChartData,
                options: stackedBarChartOptions
            });

        });

        $(function (e) {
            e.preventDefault;

            //stacked bar chart for revenues
            var reservations_revenue_data = <?php echo json_encode($revenues_data); ?>;
            const revenue_data = $.parseJSON(reservations_revenue_data);

            var stackedBarChartData = {
                labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    {
                        label               : 'Revenue collected for each month',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius          : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                : revenue_data
                    }
                ]
            };

            //---------------------
            //- STACKED BAR CHART -
            //---------------------
            var stackedBarChartCanvas = $('#stackedBarChartRevenues').get(0).getContext('2d');

            var stackedBarChartOptions = {
                responsive              : true,
                maintainAspectRatio     : false,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            };

            new Chart(stackedBarChartCanvas, {
                type: 'bar',
                data: stackedBarChartData,
                options: stackedBarChartOptions
            });

        });

    });

</script>

@endsection
