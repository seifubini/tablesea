@extends('layouts.admin_header')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
<div class="container-fluid">
<div class="row">
<div class="col-lg-12">

</div>
<!-- Date -->
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

<!-- Top  Content -->
<div class="content">
<div class="container-fluid">
<div class="row">
<div class="col-md-3">
<form method="GET" action="{{ route('sort_by_date') }}">
@csrf
<input type="hidden" name="restaurant_id" value="{{$restaurant_id}}">
<!-- <label>Sort by Date:</label>-->
<div class="row">
<div class="col-8 form-group">
<div class="input-group date" data-target-input="nearest">
<input type="date" value="{{ $date }}" name="date_of_reservation" class="form-control datetimepicker-input" id="sort_date" required>
</div>
</div>
<div class="col-4 form-group">
<button type="submit" class="btn btn-block btn-outline-light" style="background-color: #0065A3; color: #fff;">
Search
</button>
</div>
</div>
</form>
</div>
<div class="col-md-9">
<div class="row">
<div class="col-md-6">
<button type="button" class="btn btn-block btn-custom" data-toggle="modal" data-target="#modal-lg_reservation" style="background-color: #28A745; color:#fff;">
Create WalkIn Reservation
</button>
</div>
<div class="col-md-6">
<a href="{{ route('create_table', $restaurant_id) }}" style="text-decoration: none; color: #fff;">
<button type="submit" class="btn btn-block btn-custom" style="background-color: #3E3E3E; color: #fff;">
    <span><i class="fas fa-edit" style="color: #fff;"></i></span>
    <span>Edit Table</span>
</button>
</a>
</div>
</div>

</div>
</div>
<!-- Notifications -->
<div class="container p-5" id="toast_alert" style="display: none;">

</div>
</div>
</div>

<!-- Main content -->
<div class="content">
<div class="container-fluid">
<div class="row">
<div class="col-md-3">
<div class="card card-default">
<div class="card-header" style="background-color: #0065A3; color: #fff; padding-bottom: 22px;">
<h3 class="card-title" style="float: left;">
    <span >Reservations List ({{ $number_of_reservations }})</span>
</h3>
<h3 class="card-title" style="float: right;"> Wait List ({{ $wait_listed }}) </h3>
</div>
<!-- /.card-header -->

<div class="card-body overflow-auto" style="height: 494px;">
    <div class="row">
    <span id="color_status"></span>
    </div>
    <div class="row" style="display: inline-block;">
        <span class="color_code" title="Confirmed" style="height: 25px; width: 25px; border-radius: 50%; background-color: #D7F7A1; display: inline-block;">
        </span>
        <span class="color_code" title="Completed" style="height: 25px; width: 25px; border-radius: 50%; background-color: #0065A3; display: inline-block;">
            </span>
        <span class="color_code" title="Cancelled" style="height: 25px; width: 25px; border-radius: 50%; background-color: #E6292D; display: inline-block;">
            </span>
        <span class="color_code" title="Late" style="height: 25px; width: 25px; border-radius: 50%; background-color: #E78E1E; display: inline-block;">
            </span>
        <span class="color_code" title="Waitlist" style="height: 25px; width: 25px; border-radius: 50%; background-color: #363636; display: inline-block;">
            </span>
        <span class="color_code" title="Booked" style="height: 25px; width: 25px; border-radius: 50%; background-color: #E7F6FF; display: inline-block;">
            </span>
        <br>
    </div>
    @if($reservations->isEmpty())
        <h3>No Reservations</h3>
    @endif
        
    <div>
        @foreach($reservations as $reservation)
            <p style="display: none;">{{ ++$i }}</p>
        <div class="card card-default" id="reservation_status">
            @if($reservation->reservation_attachment != "")
            <span class="badge badge-success navbar-badge"
                  style="position: absolute; top: -15px; right: 80px; padding: 5px 10px; color: #fff;
                   font-weight: bolder; border-radius: 5px; content:attr(data-badge); margin: auto;"
                    title="File is Attached">File Attached</span>
            @endif
            @if($reservation->reserver_message != "")
            <span class="badge badge-warning navbar-badge"
                  style="position: absolute; top: -15px; right: -10px; padding: 5px 10px; color: #000;
                   font-weight: bolder; border-radius: 5px; content:attr(data-badge);"
                    title="Note is Available">Note Available</span>
            @endif
            @if($reservation->reservation_status == 'confirmed')
                <div class="row" style="background-color: #D7F7A1; color: #74B10C; border-radius: 10px;">
                    <div role="button" id="{{$reservation->reservation_code}}" class="col-md-12 open_modal" data-href="{{ $reservation->id }}" 
                    data-action="{{$reservation->reservation_status}}" title="{{ route('booked_tables', $reservation->id) }}" data-toggle="modal" href="#modal-lg{{ $reservation->id }}" style="color:#74B10C; font-size: 20px;">
                        
                        <div class="col-md-6" style="float: left; padding-top: 5%;">
                            <p class="text-sm" id="status_table{{ $reservation->id }}">
                                <i class="fas fa-user"></i> {{$reservation->user_name}}<br>
                                <i class="fas fa-clock"></i> {{ date('h:i A', strtotime($reservation->time_of_reservation)) }}<br>
                            </p>
                        </div>

                        <div class="col-md-6" style="float: right; padding-top: 5%;">
                            <p class="text-sm">
                                <i class="fas fa-check"></i> {{ ucfirst($reservation->reservation_status) }}<br>
                                <i class="fas fa-user-friends"></i> {{$reservation->number_of_people}}<br>
                            </p>
                        </div>

                    </div>

                </div>
            @elseif($reservation->reservation_status == 'Completed')
                <div class="row" style="background-color: #0065A3; color: #fff; border-radius: 10px;">
                    <div role="button" id="{{$reservation->reservation_code}}" class="col-md-12 open_modal" data-href="{{ $reservation->id }}" 
                    data-action="{{$reservation->reservation_status}}" title="{{ route('booked_tables', $reservation->id) }}" data-toggle="modal" href="#modal-lg{{ $reservation->id }}" style="color:#fff; font-size: 20px;">
                        
                        <div class="col-md-6" style="float: left; padding-top: 5%;">
                            <p class="text-sm" id="status_table{{ $reservation->id }}">
                                <i class="fas fa-user"></i> {{$reservation->user_name}}<br>
                                <i class="fas fa-clock"></i> {{ date('h:i A', strtotime($reservation->time_of_reservation)) }}<br>
                            </p>
                        </div>

                        <div class="col-md-6" style="float: right; padding-top: 5%;">
                            <p class="text-sm">
                                <i class="fas fa-check"></i> {{ ucfirst($reservation->reservation_status) }}<br>
                                <i class="fas fa-user-friends"></i> {{$reservation->number_of_people}}<br>
                                <i class="fas fa-money-bill"></i> {{$reservation->reservation_total_cost}} {{$restaurant->restaurant_currency}} <br>
                            </p>
                        </div>

                    </div>

                </div>
            @elseif($reservation->reservation_status == 'cancelled')
                <div class="row" style="background-color: #E6292D; color: #fff; border-radius: 10px;">
                    <div role="button" class="col-md-12 open_modal" data-href="{{ $reservation->id }}" title="{{ route('booked_tables', $reservation->id) }}" data-toggle="modal" 
                    data-action="{{$reservation->reservation_status}}" href="#modal-lg{{ $reservation->id }}" style="color:#fff; font-size: 20px;">
                        
                        <div class="col-md-6" style="float: left; padding-top: 5%;">
                            <p class="text-sm" id="status_table{{ $reservation->id }}">
                                <i class="fas fa-user"></i> {{$reservation->user_name}}<br>
                                <i class="fas fa-clock"></i> {{ date('h:i A', strtotime($reservation->time_of_reservation)) }}<br>
                            </p>
                        </div>

                        <div class="col-md-6" style="float: right; padding-top: 5%;">
                            <p class="text-sm">
                                <i class="fas fa-check"></i> {{ ucfirst($reservation->reservation_status) }}<br>
                                <i class="fas fa-user-friends"></i> {{$reservation->number_of_people}}<br>
                            </p>
                        </div>
                        
                    </div>
                </div>
            @elseif($reservation->reservation_status == 'late')
                <div class="row" style="background-color: #E78E1E; color: #fff; border-radius: 10px;">
                    <div role="button" class="col-md-12 open_modal" data-href="{{ $reservation->id }}" title="{{ route('booked_tables', $reservation->id) }}" data-toggle="modal" 
                    data-action="{{$reservation->reservation_status}}" href="#modal-lg{{ $reservation->id }}" style="color:#fff; font-size: 20px;">
                        
                        <div class="col-md-6" style="float: left; padding-top: 5%;">
                            <p class="text-sm" id="status_table{{ $reservation->id }}">
                                <i class="fas fa-user"></i> {{$reservation->user_name}}<br>
                                <i class="fas fa-clock"></i> {{ date('h:i A', strtotime($reservation->time_of_reservation)) }}<br>
                            </p>
                        </div>

                        <div class="col-md-6" style="float: right; padding-top: 5%;">
                            <p class="text-sm">
                                <i class="fas fa-check"></i> {{ ucfirst($reservation->reservation_status) }}<br>
                                <i class="fas fa-user-friends"></i> {{$reservation->number_of_people}}<br>
                            </p>
                        </div>
                        
                    </div>
                </div>
            @elseif($reservation->reservation_status == 'wait_list')
                <div class="row" style="background-color: #363636; color: #fff; border-radius: 10px;">
                    <div role="button" id="{{$reservation->reservation_code}}" title="{{ route('booked_tables', $reservation->id) }}" class="col-md-12 open_modal" 
                    data-action="{{$reservation->reservation_status}}" data-href="{{ $reservation->id }}" data-toggle="modal" href="#modal-lg{{ $reservation->id }}" style="color:#fff; font-size: 20px;">
                        
                        <div class="col-md-6" style="float: left; padding-top: 5%;">
                            <p class="text-sm" id="status_table{{ $reservation->id }}">
                                <i class="fas fa-user"></i> {{$reservation->user_name}}<br>
                                <i class="fas fa-clock"></i> {{ date('h:i A', strtotime($reservation->time_of_reservation)) }}<br>
                            </p>
                        </div>

                        <div class="col-md-6" style="float: right; padding-top: 5%;">
                            <p class="text-sm">
                                <i class="fas fa-check"></i> {{ ucfirst($reservation->reservation_status) }}<br>
                                <i class="fas fa-user-friends"></i> {{$reservation->number_of_people}}<br>
                            </p>
                        </div>
                        
                    </div>
                </div>
            @else
                <div class="row" style="background-color: #E7F6FF; color: #0065A3; border-radius: 10px;">
                    <div role="button" class="col-md-12 open_modal" data-href="{{ $reservation->id }}" title="{{ route('booked_tables', $reservation->id) }}" 
                    data-action="{{$reservation->reservation_status}}" data-toggle="modal" href="#modal-lg{{ $reservation->id }}" style="color:#0065A3; font-size: 20px;">
                        
                        <div class="col-md-6" style="float: left; padding-top: 5%;">
                            <p class="text-sm" id="status_table{{ $reservation->id }}">
                                <i class="fas fa-user"></i> {{$reservation->user_name}}<br>
                                <i class="fas fa-clock"></i> {{ date('h:i A', strtotime($reservation->time_of_reservation)) }}<br>
                            </p>
                        </div>

                        <div class="col-md-6" style="float: right; padding-top: 5%;">
                            <p class="text-sm">
                                <i class="fas fa-check"></i> {{ ucfirst($reservation->reservation_status) }}<br>
                                <i class="fas fa-user-friends"></i> {{$reservation->number_of_people}}<br>
                            </p>
                        </div>
                        
                    </div>

                </div>
        @endif
            <!-- Online Reservation Modal -->
            <div class="modal fade" id="modal-lg{{ $reservation->id }}">
                <div class="modal-dialog modal-default">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                            <h4 class="modal-title">Update Online Reservation</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                            <!-- form start -->
                            <form method="POST" action="{{ route('reservations.update', $reservation->id) }}" enctype="multipart/form-data">
                                <!-- /.input group -->
                                @csrf
                                @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Guest Name</label>
                                        <div class="input-group">
                                            <input type="text" value="{{$reservation->user_name}}" name="user_name" class="form-control" disabled>
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
                                            <input type="email" value="{{$reservation->user_email}}" name="user_email" class="form-control" disabled>
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
                                            <input type="text" value="{{$reservation->user_phone}}" name="user_phone" class="form-control" disabled>
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
                                            <input type="text" disabled value="{{ $reservation->date_of_reservation }}" class="form-control datetimepicker-input" data-target="#reservationdate"/>
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
                                                <input type="text" disabled value="{{ $reservation->time_of_reservation }}" class="form-control datetimepicker-input" data-target="#timepicker"/>
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
                                        <p class="form-control">
                                            {{$reservation->reservation_code}}
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
                                                    <input type="number" min="1" id="people{{$reservation->id}}" name="number_of_people" value="{{ $reservation->number_of_people }}" class="form-control">
                                                </div>
                                                <div class="col-8">
                                                    <button type="button" id="less_people{{$reservation->id}}" class="btn" style="border: none;">
                                                        <i class="fas fa-minus-circle" style="background-color: #fff; color: #007BC9;"></i>
                                                    </button>
                                                    <button type="button" id="more_people{{$reservation->id}}" class="btn" style="border: none;">
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
                                                    <input type="text" id="online_booking_duration{{$reservation->id}}" name="reservation_duration" class="form-control" value="60">
                                                </div>
                                                <div class="col-8">
                                                    <button type="button" id="online_booking_minus{{$reservation->id}}" class="btn" style="border: none;">
                                                        <i class="fas fa-minus-circle" style="background-color: #fff; color: #007BC9;"></i>
                                                    </button>
                                                    <button type="button" id="online_booking_plus{{$reservation->id}}" class="btn" style="border: none;">
                                                        <i class="fas fa-plus-circle" style="background-color: #fff; color: #007BC9;"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
        @endif
        
        @if($rest_type == 'Automatic')
                <div class="col-md-6">
                    <!-- select -->
                    <div class="form-group">
                        <label>Status</label>
                        <select class="custom-select" name="reservation_status" id="reserved_status{{$reservation->id}}">
                            @if($reservation->reservation_status == 'booked')
                                    <option value="booked" selected>Booked</option>
                                @else
                                    <option value="booked">Booked</option>
                                @endif
                                
                                @if($reservation->reservation_status == 'wait_list')
                                    <option value="wait_list" selected>Waitlist</option>
                                @else
                                    <option value="wait_list">Waitlist</option>
                                @endif
                                
                                @if($reservation->reservation_status == 'confirmed')
                                    <option value="confirmed" selected>Confirmed</option>
                                @else
                                    <option value="confirmed">Confirmed</option>
                                @endif
                                
                                @if($reservation->reservation_status == 'cancelled')
                                    <option value="cancelled" selected>Cancelled</option>
                                @else
                                    <option value="cancelled">Cancelled</option>
                                @endif
                                 
                                @if($reservation->reservation_status == 'late')
                                    <option value="late" selected>Late</option>
                                @else
                                    <option value="late">Late</option>
                                @endif 
                        </select>
                    </div>
                </div>
                @elseif($rest_type == 'Manual')
                    <div class="col-md-6">
                        <!-- select -->
                        <div class="form-group">
                            <label>Status</label>
                            <select class="custom-select" name="reservation_status" id="reserved_status{{$reservation->id}}">
                                @if($reservation->reservation_status == 'booked')
                                    <option value="booked" selected>Booked</option>
                                @else
                                    <option value="booked">Booked</option>
                                @endif
                                
                                @if($reservation->reservation_status == 'wait_list')
                                    <option value="wait_list" selected>Waitlist</option>
                                @else
                                    <option value="wait_list">Waitlist</option>
                                @endif
                                
                                @if($reservation->reservation_status == 'confirmed')
                                    <option value="confirmed" selected>Confirmed</option>
                                @else
                                    <option value="confirmed">Confirmed</option>
                                @endif
                                
                                @if($reservation->reservation_status == 'cancelled')
                                    <option value="cancelled" selected>Cancelled</option>
                                @else
                                    <option value="cancelled">Cancelled</option>
                                @endif
                                
                                @if($reservation->reservation_status == 'Completed')
                                    <option value="Completed" selected>Completed</option>
                                @else
                                    <option value="Completed">Completed</option>
                                @endif
                                 
                                @if($reservation->reservation_status == 'late')
                                    <option value="late" selected>Late</option>
                                @else
                                    <option value="late">Late</option>
                                @endif   
                                
                            </select>
                        </div>
                    </div>
                @endif
        
        <div class="col-md-6">
        <!-- select -->
        <div class="form-group">
                <label>Guest Type</label>
                <select class="custom-select" name="reservation_tag">
                    <option value="{{$reservation->reservation_tag}}" selected>{{$reservation->reservation_tag}}</option>
                    @foreach($guest_types as $guest_type)
                        <option value="{{$guest_type->guest_type_name}}">{{$guest_type->guest_type_name}}</option>
                    @endforeach
</select>
            </div>
    </div>

        <div class="col-md-6" id="table_selector{{$reservation->id}}">
            <div class="form-group">
                <label>Select Table</label>
                <select class="select2bs4" name="table_id[]" multiple="multiple" id="online_tables{{$reservation->id}}" data-placeholder="Select Table"
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
                                            <select class="custom-select" name="membership_tag">
                                                @foreach($memberships as $membership)
                                                    <option value="{{ $membership->membership_name }}">{{ $membership->membership_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
        @if($rest_type == 'Manual')
            <div class="col-md-6" id="bill_total{{$reservation->id}}">
            <!-- textarea -->
            <div class="form-group">
            <label>Total Bill</label>
            <input type="number" class="form-control" value="$reservation->reservation_total_cost" name="reservation_total_cost">
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
                                                  name="reserver_message" maxlength="150">{{$reservation->reserver_message}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Guest Note</label>
                                        <textarea class="form-control"
                                                  name="hostess_note" maxlength="150" readonly>{{$reservation->hostess_note}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputFile">File input</label>
                                        <div class="input-group">
                                          <div class="custom-file">
                                            <input type="file" name="reservation_attachment" class="custom-file-input" id="exampleInputFile" 
                                                value="{{$reservation->reservation_attachment}}">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                          </div>
                                          
                                        </div>
                                      </div>
                                      <br>
                                </div>
                                <hr>
                                <input type="hidden" name="restaurant_id" value="{{$restaurant_id}}">
                                <input type="hidden" name="date_of_reservation" value="{{$reservation->date_of_reservation}}">
                                <input type="hidden" name="time_of_reservation" value="{{$reservation->time_of_reservation}}">
                                <input type="hidden" name="user_id" value="{{$reservation->user_id}}">

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-custom btn-block" style="background-color: #007BC9; color: #fff;">
                                        Update
                                    </button>
                                </div>
                            </form>
                        @if(Auth::user()->user_type == "Restaurant")    
                            <div class="card-footer text-center">
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#modal-danger{{ $reservation->id }}">
                                Delete
                            </button>
                            </div>

                        <!--Delete Modal Starts Here-->
                        <div class="modal fade" id="modal-danger{{ $reservation->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content bg-danger">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Are you sure you want to delete this reservation <br>
                                            <strong> {{ $reservation->user_name }} - {{ $reservation->user_email }} </strong> ?</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
            
                                    <form action="{{ route('reservations.destroy',$reservation->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        @if(Auth::user()->user_type == "Restaurant")
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
                        
                        @endif
                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.online reservation modal end here-->

        </div>
        @endforeach
</div>
</div>
<!-- /.card-body -->

</div>
<!-- /.card -->
</div>

<!-- Tables list with their arranged positions -->
<div class="col-md-9">
<div class="card card-default" style="height: 550px;">
<div class="card-header" style="background-color: #0065A3; color: #fff; float: left;">
<!-- <h3 class="card-title" style="float: left;">Your Tables </h3>-->

<div class="row">

    <div class="col-sm-2">
        <p style="font-size: 10px;">
            AVL TABLES - {{ $open_tables }}</p>
    </div>

    <div class="col-sm-2">
        <p style="font-size: 10px;">
            BOOKED - {{ $booked }}</p>
    </div>

    <div class="col-sm-2">
        <p style="font-size: 10px;">
            CONFIRMED - {{ $confirmed }}</p>
    </div>

    <div class="col-sm-2">
        <p style="font-size: 10px;">
            CANCELLED - {{ $cancelled }}</p>
    </div>

    <div class="col-sm-2">
        <p style="font-size: 10px;">
            COMPLETED - {{ $completed }}</p>
    </div>

    <div class="col-sm-1">
        <p style="font-size: 10px;">
            LATE - {{ $late }}</p>
    </div>

    <!-- <div class="col-sm-1">
        <p style="font-size: 10px;">
            AVL TABLES - {{ $available_tables }}</p>
    </div>-->

</div>

</div>
<!-- /.card-header -->
<div class="card-body" style="margin:0; padding:0; list-style-type:none; height: 500px;
width: auto; background-image: url('{{ asset('images/table-bg.png') }}')">
<section class="tables_position-list" style="margin: 0; padding: 0; list-style: none;">
@foreach($tables as $table)
<li style="padding: 0; list-style: none; float:left; margin:5px 3px;
background:none; width:auto; height:auto;">
    <a href="javascript:void(0);" style="float:none; color: #000;" class="image_link" data-href="{{ $table->id }}"
       data-toggle="modal" data-target="#modal-default_history{{ $table->id }}" id="{{$table->table_name}}">
        <img src="{{ asset('table_shapes') }}/{{ $table->table_shape}}" width="30px" height="30px">
        <p class="text-center" style="font-size: 12px; padding: 0;" id="badge{{$table->id}}">{{$table->table_name}}</p>
    </a>
</li>
        <!-- Table reservation history modal -->
        <div class="modal fade" id="modal-default_history{{ $table->id }}">
            <div class="modal-dialog modal-default">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                        <h4 class="modal-title">Reservation History of {{$table->table_name}} for {{ date_format(date_create($date), 'jS M Y')}}</h4>
                        <button type="button" id="close_history{{ $table->id }}" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Reservation History Box -->
                            <!-- /.card-body -->
                        <div class="card-body">
                            <div id="accordion{{ $table->id }}">

                            </div>
                        </div>
                            <!-- /.card-body -->
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
@endforeach
</section>

</div>
<!-- /.card-body -->
</div>
<!-- /.card -->

</div>

</div>
</div>
</div>

<!-- WalkIn Reservation Modal -->
<div class="modal fade" id="modal-lg_reservation">
<div class="modal-dialog modal-default">
<div class="modal-content">
<div class="modal-header" style="background-color: #28A745; color:#fff;">
<h4 class="modal-title">Create WalkIn Reservation</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
        <input type="date" min="{{ $date }}" id="reservation_date" name="date_of_reservation" class="form-control datetimepicker-input" data-target="#reservationdate"/>
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
    <textarea class="form-control" name="reserver_message" maxlength="150"></textarea>
</div>
</div>
<div class="col-md-12">
<div class="form-group">
    <label>Guest Note</label>
    <textarea class="form-control" name="hostess_note" id="suggested_hostess_note" maxlength="150"></textarea>
</div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="exampleInputFile">File input</label>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" name="reservation_attachment" class="custom-file-input" id="exampleInputFile" >
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

<input type="hidden" id="get_all_guests" value="{{ route('get_all_guests', $restaurant_id) }}" style="display: none;">

@endsection
@section('scripts')
<script type="application/javascript">

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

$(document).ready(function(e){
e.preventDefault;

var rest_id = "{{$restaurant_id}}";
const get_positions_url = "{{ route('get_table_positions', $restaurant_id) }}";

$.ajax({
url: get_positions_url,
type: 'GET',
dataType: 'json', // added data type
success: function(tables) {

var count = 0;

$("section.tables_position-list li").each( function() {

var id = tables[count]['id'];
var positions = tables[count]['table_position'];
var posArr = positions.split(',');
var top = posArr[0];
var left = posArr[1];
var new_left = left.substring(1, left.length);
var booked = tables[count]['table_is_booked'];

var height = $(this).height();
var width = $(this).width();
var t_top = top.substr(5);
var t_left = new_left.substr(6);


$(this).css({
position: 'absolute',
margin: '5px 3px',
top: t_top+ 'px',
left: t_left+'px',
});

count++;
});

}

});

return false;
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

$("div #reservation_status .open_modal").each( function() {
var reservation_id = $(this).attr('data-href');
var booked_seats_url = $(this).attr('title');
var reservation_code = $(this).attr('id');
var container = $("#status_table"+reservation_id);

$.ajax({
url: booked_seats_url,
type: 'GET',

success: function (tables) {
var data = $.parseJSON(tables);

var len = data.length;

if(len > 0) {
    for (let f = 0; f < len; f++) {
        var booked = data[f]['table_is_booked'];
        var t_id = data[f]['id'];
        var t_name = data[f]['table_name'];
        var r_code = data[f]['reservation_code'];
        var b_time = data[f]['time_of_reservation'];
        var booked_bagde = $("#badge"+t_id);

        var booked_time = new Date('1970-01-01T' + b_time + 'Z')
            .toLocaleTimeString({},
                {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
            );

        if(reservation_code === r_code)
        {
            $(container).append('<span>\n' +
                '<i class="fas fa-table"></i>'+ ' - '+t_name +'<br>\n' +
                '</span>');
        }
        else{

        }

        if(booked === 'yes')
        {
            $(booked_bagde).css({
                'background-color':'#28A745', 'color':'#fff', 'font-weight':'bolder'
            });
            $(booked_bagde).attr('title', booked_time);
        }
        else
        {

        }


    }
}
}
});

});

});

//manipulate the online reservation values
$(document).on('click','.open_modal', function(e){


var reservation_id = $(this).attr('data-href');
var booked_seats_url = $(this).attr('title');

var reservation_status = $(this).attr('data-action');
            
if(reservation_status === 'booked' || reservation_status === 'cancelled')
{
    $('#bill_total'+reservation_id).css('display', 'none');
    $('#table_selector'+reservation_id).css('display', 'none');
}
if(reservation_status === 'confirmed')
{
    $('#bill_total'+reservation_id).css('display', 'none');
    $('#table_selector'+reservation_id).css('display', 'block');
}
if(reservation_status === 'Completed')
{
    $('#bill_total'+reservation_id).css('display', 'block');
    $('#table_selector'+reservation_id).css('display', 'block');
}

$('#reserved_status'+reservation_id).change( function (){
   var stat_val = $('#reserved_status'+reservation_id).val();
    
    if(stat_val === 'booked' || stat_val === 'cancelled')
    {
        $('#bill_total'+reservation_id).css('display', 'none');
        $('#table_selector'+reservation_id).css('display', 'none');
    }
    if(stat_val === 'confirmed')
    {
        $('#bill_total'+reservation_id).css('display', 'none');
        $('#table_selector'+reservation_id).css('display', 'block');
    }
    if(stat_val === 'Completed')
    {
        $('#bill_total'+reservation_id).css('display', 'block');
        $('#table_selector'+reservation_id).css('display', 'block');
    }
});

$('#online_booking_minus'+reservation_id).click(function (e) {
e.preventDefault;
const min_value = parseInt($("#online_booking_duration"+reservation_id).val());
const duration_value = min_value - 15;
$('#online_booking_duration'+reservation_id).val(duration_value);
});

$('#online_booking_plus'+reservation_id).click(function (e) {
e.preventDefault;
const max_value = parseInt($("#online_booking_duration"+reservation_id).val());
const duration_value = max_value + 15;
$('#online_booking_duration'+reservation_id).val(duration_value);
});

$('#more_people'+reservation_id).click(function () {

const value = parseInt($("#people"+reservation_id).val());
const plus_value = value + 1;

$("#people"+reservation_id).val(plus_value);
});

$('#less_people'+reservation_id).click(function () {

const value = parseInt($("#people"+reservation_id).val());
if (value > 1) {
const plus_value = value - 1;
$("#people"+reservation_id).val(plus_value);
}
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
   else{
       $("#table_walkin").show();
   }
});

});

$(document).ready(function (){

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
});

$(document).on('click','.image_link', function(e){

var table_id = $(this).attr('data-href');
var history_url = "{{ route('get_history') }}";
var rest_id = '{{$restaurant_id}}';
var table_name = $(this).attr('id');
var date = '{{ $date }}';
var wrapper = $(".modal-body .card-body #accordion"+table_id);

$('.table_history_name').text(table_name);

$.ajax({
url: history_url,
method: "POST",
data: {
_token: '{{ csrf_token() }}',
id: table_id,
rest_id: rest_id,
date: date
},
success: function (history) {
var data = $.parseJSON(history);

var len = data.length;
if(len > 0){
for(let f = 0; f<len; f++ ) {
var no = f + 1;
var guest_name = data[f]['user_name'];
guest_name = guest_name.toLowerCase().replace(/\b[a-z]/g, function(guest) {
return guest.toUpperCase();
});
var reserved_date = data[f]['date_of_reservation'];
var reserved_time = data[f]['time_of_reservation'];
reserved_time = new Date('1970-01-01T' + reserved_time + 'Z')
.toLocaleTimeString({},
    {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
);
var no_of_people = data[f]['number_of_people'];
var total_spent = data[f]['reservation_total_cost'];
var reservation_code = data[f]['reservation_code'];
var reservation_status = data[f]['reservation_status'];
reservation_status = reservation_status.charAt(0).toUpperCase() + reservation_status.slice(1);
var reservation_type = data[f]['reservation_type'];
reservation_type = reservation_type.charAt(0).toUpperCase() + reservation_type.slice(1);
var reservation_tag = data[f]['reservation_tag'];
//var guest_tags = reservation_tag.split('_');
//var first = guest_tags[0].charAt(0).toUpperCase() + guest_tags[0].slice(1);
//var second = guest_tags[1].charAt(0).toUpperCase() + guest_tags[1].slice(1);
//var guest_tag = first+' '+second;
var href = '#collapse'+no;
var div_id = 'collapse'+no;

$(wrapper).append('<div class="card card-primary" id="history'+ table_id +'">\n' +
'<div class="card-header">\n' +
'<h4 class="card-title w-100">\n' +
'<a class="d-block w-100" data-toggle="collapse" href="#collapse'+ no +'">\n' +
reservation_code + '</a>\n' +
'</h4>\n' +
'</div>\n' +
'<div id="collapse'+ no +'" class="collapse" data-parent="#accordion'+ table_id +'">\n' +
'<div class="card-body">\n' +
'<dl class="row">\n' +
'<dt class="col-sm-6">'+'Guest Name'+'</dt>\n' +
'<dd class="col-sm-6">'+guest_name+'</dd>\n' +
'<dt class="col-sm-6">'+'Reserved Date'+'</dt>\n' +
'<dd class="col-sm-6">'+reserved_date+'</dd>\n' +
'<dt class="col-sm-6">'+'Reserved Time'+'</dt>\n' +
'<dd class="col-sm-6">'+reserved_time+'</dd>\n' +
'<dt class="col-sm-6">'+'Number of People'+'</dt>\n' +
'<dd class="col-sm-6">'+no_of_people+'</dd>\n' +
'<dt class="col-sm-6">'+'Total Spent'+'</dt>\n' +
'<dd class="col-sm-6">'+total_spent+'</dd>\n' +
'<dt class="col-sm-6">'+'Reservation Status'+'</dt>\n' +
'<dd class="col-sm-6">'+reservation_status+'</dd>\n' +
'<dt class="col-sm-6">'+'Reservation Type'+'</dt>\n' +
'<dd class="col-sm-6">'+reservation_type+'</dd>\n' +
'<dt class="col-sm-6">'+'Guest Type'+'</dt>\n' +
'<dd class="col-sm-6">'+reservation_tag+'</dd>\n' +
'</dl>\n' +
'</div>\n' +
'</div>\n' +
'</div>');
}

}else{
$(wrapper).append(
'<h4 class="card-title w-100">\n' +
'No History Available' +
'</h4>');
}

$("#modal-default_history"+table_id).show();
}

});
var closer = '#close_history'+table_id;
$(closer).click( function (e) {

$(wrapper).empty();
$("#modal-default_history"+table_id).hide();
});

e.preventDefault;

});

</script>
@endsection
