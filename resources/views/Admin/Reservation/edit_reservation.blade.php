@extends('layouts.admin_header')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Reservation</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Admin</a></li>
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

    <div class="col-md-8">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Reservation Details</h3>

            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('reservations.update', $reservation->id) }}" enctype="multipart/form-data">
                <!-- /.input group -->
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Reservation Code</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" value="{{$reservation->reservation_code}}"
                               placeholder="{{$reservation->reservation_code}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Reserved Date</label>
                        <input type="text" class="form-control" value="{{$reservation->date_of_reservation}}" id="exampleInputName1" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Reserved Time</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" value="{{$reservation->time_of_reservation}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Number of People</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" value="{{$reservation->number_of_people}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Reserved By</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" value="{{$reservation->user_name}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Select Restaurant</label>
                        <select class="form-control select2" name="table_id" style="width: 100%;">
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}">{{$table->table_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- textarea -->
                    <div class="form-group">
                        <label>Additional Note</label>
                        <textarea class="form-control" rows="6" placeholder="Menu Description ..." maxlength = "500" disabled>
                          {{$reservation->reserver_message}}
                        </textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" disabled class="form-check-input" checked value="{{$reservation->reservation_status}}" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Booked</label>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                </div>
            </form>
            <div class="card-footer text-center">
                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#modal-danger">
                    Delete
                </button>
            </div>
        </div>
        <!-- /.card -->
    </div>

    <!-- Modal Starts Here-->
    <div class="modal fade" id="modal-danger">
        <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h4 class="modal-title">Are you sure you want to delete <br> <strong>this reservation</strong> ?</h4>
                </div>

                <form action="{{ route('reservations.destroy',$reservation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection
