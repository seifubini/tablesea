@extends('layouts.backend_header')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Guest Type Info </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Home</a></li>
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
                        <div class="card-header">
                            <h3 class="card-title" style="padding-left: 5%;">
                              <button type="button" class="btn btn-block btn-custom" style="background-color: #0065A3; color: #fff;"
                                      data-toggle="modal" data-target="#modal-default_guest_type">
                                  Add New Guest Type</button>
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Guest Type Name</th>
                                    <th>Restaurant Name</th>
                                    <th>Created At</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($guest_types as $guest_type)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $guest_type->guest_type_name}}</td>
                                        <td>{{ $guest_type->Restaurant_name}}</td>
                                        <td>
                                            {{ date_format(date_create($guest_type->created_at), 'jS M Y')}}
                                        </td>
                                        @if(Auth::user()->user_type == "Restaurant")
                                            <td>
                                                <button type="button" title="delete" style="border: none; background-color:transparent;"
                                                        data-toggle="modal" data-target="#modal-danger{{$guest_type->id}}">
                                                    <i class="fas fa-trash fa-lg text-danger"></i>
                                                </button>
                                            </td>
                                    @endif

                                        <!-- Modal Starts Here-->
                                        <div class="modal fade" id="modal-danger{{$guest_type->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-danger">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Are you sure you want to delete <br>
                                                            <strong>"{{ $guest_type->guest_type_name }}"</strong> ?</h4>
                                                    </div>

                                                    <form action="{{ route('delete_guest', $guest_type->id) }}" method="GET">
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
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Guest Type Name</th>
                                    <th>Restaurant Name</th>
                                    <th>Created At</th>
                                    <th>Delete</th>
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


<!-- Add New Guest Type Modal -->
<div class="modal fade" id="modal-default_guest_type">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                <h4 class="modal-title">Add New Guest Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="POST" action="{{ route('guest_types.store')}}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Created By</label>
                        <div class="col-sm-6">
                            <input type="email" name="created_by_email" class="form-control" value="{{Auth::user()->email}}" readonly required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Guest Type Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="guest_type_name" class="form-control" id="inputName" placeholder="Name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-4 col-form-label">Select Restaurant</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="restaurant_id" style="width: 100%;" required>
                                @foreach($restaurants as $restaurant )
                                    <option value="{{$restaurant->id}}">{{$restaurant->Restaurant_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-custom btn-block" style="background-color: #0065A3; color: #fff;">
                        Add Guest Type</button>
                </form>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@endsection
