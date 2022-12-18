@extends('layouts.admin_header')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Table Reservations </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Restaurants</li>
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

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-hover table-head-fixed text-nowrap">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Reserved Date</th>
                                    <th>Reservation Code</th>
                                    <th>Reserved Time</th>
                                    <th># Persons</th>
                                    <th>File</th>
                                    <th>Reserved By</th>
                                    <th>Guest Email</th>
                                    <th>Guest Phone</th>
                                    <th>Created By</th>
                                    <th>Guest Note</th>
                                    <th>Status</th>
                                    <th>Update</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach ($reservations as $reservation)
                                        <td>{{ ++$i }}</td>
                                        <td>{{ date_format(date_create($reservation->date_of_reservation), 'jS M Y')}}</td>
                                        <td>{{ $reservation->reservation_code }}</td>
                                        <td>{{ date('h:i A', strtotime($reservation->time_of_reservation)) }}</td>
                                        <td>{{ $reservation->number_of_people}}</td>
                                        @if($reservation->reservation_attachment != "")
                                        <td>
                                            <a href="{{asset('images/attachments')}}/{{ $reservation->reservation_attachment}}" target="_blank" style="text-decoration: none; color: #fff;">
                                            <button type="button" class="btn btn-custom">
                                                <i class="fas fa-eye" title="view attachment"></i>
                                                 Open
                                            </button>
                                            </a>
                                        </td>
                                        @else
                                        <td>N/A</td>
                                        @endif
                                        <td>{{ $reservation->user_name}}</td>
                                        <td>{{ $reservation->user_email}}</td>
                                        <td>{{ $reservation->user_phone}}</td>
                                        <td>{{ $reservation->creater_name}}</td>
                                        <td style="display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; max-width: 10ch;">{{ $reservation->hostess_note }}</td>

                                        @if($reservation->reservation_status == 'confirmed')
                                            <td><span class="right badge badge-success">Confirmed</span></td>
                                        @elseif($reservation->reservation_status == 'booked')
                                            <td><span class="right badge badge-info">Booked</span></td>
                                        @elseif($reservation->reservation_status == 'cancelled')
                                            <td><span class="right badge badge-danger">Cancelled</span></td>
                                        @elseif($reservation->reservation_status == 'late')
                                            <td><span class="right badge badge-warning">Arrived Late</span></td>
                                        @elseif($reservation->reservation_status == 'Completed')
                                            <td><span class="right badge badge-custom" style="background-color: #0065A3; color: #fff;">
                                                Completed</span></td>
                                        @else
                                            <td><span class="right badge badge-danger">Empty</span></td>
                                        @endif
                                    @if(Auth::user()->user_type != "Booking_Manager")
                                        <td>
                                        <form method="POST" action="{{ route('update_reservation_status') }}">
                                            @csrf
                                            <input type="hidden" name="reservation_id" value="{{$reservation->id}}">
                                            <div class="form-group">
                                                <select class="form-control" name="reservation_status">
                                                    <option value="confirmed">
                                                        <span class="right badge badge-success">Confirmed</span>
                                                    </option>
                                                    <option value="booked">
                                                        <span class="right badge badge-info">Booked</span>
                                                    </option>
                                                    <option value="late">
                                                        <span class="right badge badge-warning">Late</span>
                                                    </option>
                                                    <option value="cancelled">
                                                        <span class="right badge badge-danger">Cancelled</span>
                                                    </option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-block btn-outline-primary btn-sm">
                                                Update</button>
                                        </form>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>

@endsection
