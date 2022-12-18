@extends('layouts.admin_header')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users Info </h1>
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

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
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
                                <button class="btn btn-custom" style="background-color: #0065A3; color: #fff; float:left;"
                                        data-toggle="modal" data-target="#modal-lg">
                                    <span><i class="fas fa-plus" style="color: #fff;"></i></span>
                                    <span>Add New User</span>
                                </button>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User Name</th>
                                    <th>User Phone</th>
                                    <th>User Email</th>
                                    <th>User Photo</th>
                                    <th>User Type</th>
                                    <th>Member Since</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach ($clients as $user)
                                        <td>{{ ++$i }}</td>
                                        @if($user->id == Auth::user()->id)
                                            <td><strong>{{ $user->name}}</strong></td>
                                        @else
                                            <td>{{ $user->name}}</td>
                                        @endif
                                        <td>{{ $user->user_phone_number}}</td>
                                        <td>{{ $user->email}}</td>
                                        @if($user->user_image == "")
                                            <td>N/A</td>
                                        @else
                                            <td><img src="{{ asset('images/users') }}/{{ $user->user_image}}" height="75" width="100"></td>
                                        @endif
                                        <td>{{ $user->user_type}}</td>
                                        <td>{{ date_format(date_create($user->created_at), 'jS M Y')}}</td>
                                        <td>
                                            <button type="button" title="delete" style="border: none; background-color:transparent;"
                                                    data-toggle="modal" data-target="#modal-danger{{$user->id}}">
                                                <i class="fas fa-trash fa-lg text-danger"></i>
                                            </button>
                                        </td>
                                </tr>
                                <!-- Modal Starts Here-->
                                <div class="modal fade" id="modal-danger{{$user->id}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-danger">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Are you sure you want to delete <br> <strong>"{{ $user->name }}"</strong> ?</h4>
                                            </div>

                                            <form action="{{ route('administrator.destroy', $user->id) }}" method="POST">
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
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>User Name</th>
                                    <th>User Phone</th>
                                    <th>User Email</th>
                                    <th>User Photo</th>
                                    <th>User Type</th>
                                    <th>Member Since</th>
                                    <th>Action</th>
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

    <!-- Add New User Modal -->
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-default">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                    <h4 class="modal-title">Add New User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="POST" action="{{ route('administrator.store')}}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="inputName" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="Email" name="email" class="form-control" id="inputName2" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="Password" name="password" class="form-control" id="inputName2" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">User Type</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="user_type" style="width: 100%;" required>
                                    <option value="Restaurant">Restaurant</option>
                                    <option value="Administrator">Administrator</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-custom btn-block" style="background-color: #0065A3; color: #fff;">
                            Add User</button>
                    </form>

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection
