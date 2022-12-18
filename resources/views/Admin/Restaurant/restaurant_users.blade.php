@extends('layouts.backend_header')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Restaurant Info </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Restaurant Users</li>
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
                                <button type="button" class="btn btn-block btn-custom" style="background-color: #0065A3; color: #fff;"
                                        data-toggle="modal" data-target="#modal-default">
                                    Add New User</button>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User Name</th>
                                    <th>Restaurant Name</th>
                                    <th>User Email</th>
                                    <th>User Type</th>
                                    <th>Created At</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($my_users as $my_user)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $my_user->name}}</td>
                                    <td>{{$my_user->Restaurant_name}}</td>
                                    <td>{{ $my_user->email}}</td>
                                    @if($my_user->user_role == "Manager")
                                    <td> Manager</td>
                                    @elseif($my_user->user_role == "Booking_Manager")
                                    <td> Booking Manager</td>
                                    @endif
                                    <td>
                                        {{ date_format(date_create($my_user->created_at), 'jS M Y')}}
                                    </td>
                                    @if(Auth::user()->user_type == "Restaurant")
                                    <td>
                                        <button type="button" style="border: none; background-color:transparent;"
                                                data-toggle="modal" data-target="#modal-default_edit{{$my_user->id}}">
                                            <i class="fas fa-user-edit fa-lg" style="color: #000;"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" title="delete" style="border: none; background-color:transparent;"
                                                data-toggle="modal" data-target="#modal-danger{{$my_user->id}}">
                                            <i class="fas fa-trash fa-lg text-danger"></i>
                                        </button>
                                    </td>
                                    @endif

                                <!-- Edit User Modal -->
                                    <div class="modal fade" id="modal-default_edit{{$my_user->id}}">
                                        <div class="modal-dialog modal-default">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                                                    <h4 class="modal-title">Edit User</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <form method="POST" action="{{ route('edit_user', $my_user->user_id)}}" enctype="multipart/form-data" class="form-horizontal">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group row">
                                                            <label for="inputName" class="col-sm-4 col-form-label">Created By</label>
                                                            <div class="col-sm-6">
                                                                <input type="email" name="created_by_email" class="form-control" value="{{Auth::user()->email}}" readonly required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName" class="col-sm-4 col-form-label">User Name</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" name="name" value="{{$my_user->name}}" class="form-control" id="inputName" placeholder="Name" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-4 col-form-label">User Email</label>
                                                            <div class="col-sm-6">
                                                                <input type="Email" name="email" value="{{$my_user->email}}" class="form-control" id="inputName2" placeholder="Email" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-4 col-form-label">Password</label>
                                                            <div class="col-sm-6">
                                                                <input type="Password" name="password" class="form-control" id="inputName2" placeholder="Password">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-4 col-form-label">Confirm Password</label>
                                                            <div class="col-sm-6">
                                                                <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-4 col-form-label">User Type</label>
                                                            <div class="col-sm-6">
                                                                <select class="form-control select2" name="user_type" style="width: 100%;" required>
                                                                    <option value="{{$my_user->user_role}}" selected>{{$my_user->user_role}}</option>
                                                                    <option value="Manager">Manager</option>
                                                                    <option value="Booking_Manager">Booking Manager</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-4 col-form-label">Select Restaurant</label>
                                                            <div class="col-sm-6">
                                                                <select class="select2bs4" name="restaurant_id[]" multiple="multiple" id="user_restaurant" data-placeholder="Select Restaurant"
                                                                    style="width: 100%; list-style: none;">
                                                                    @foreach($restaurants as $restaurant)
                                                                        @if($my_user->restaurant_id == $restaurant->id)
                                                                        <option value="{{$restaurant->id}}" class="form-control" selected>{{$restaurant->Restaurant_name}}</option>
                                                                        @else
                                                                        <option value="{{$restaurant->id}}" class="form-control">{{$restaurant->Restaurant_name}}</option>
                                                                        @endif
                                                                        
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="restaurant_user_id" 
                                                            value="{{$my_user->user_id}}" style="display: none;">
                                                        
                                                        <!-- <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-2 col-form-label">Select Restaurant</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control select2" name="restaurant_id" style="width: 100%;" required>
                                                                    @foreach($restaurants as $restaurant )
                                                                        @if($my_user->restaurant_id == $restaurant->id)
                                                                        <option value="{{$restaurant->id}}" selected>{{$restaurant->Restaurant_name}}</option>
                                                                        @else
                                                                        <option value="{{$restaurant->id}}">{{$restaurant->Restaurant_name}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>-->

                                                        <button type="submit" class="btn btn-custom btn-block" style="background-color: #0065A3; color: #fff;">
                                                            Update</button>
                                                    </form>

                                                </div>

                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                <!-- Modal Starts Here-->
                                    <div class="modal fade" id="modal-danger{{$my_user->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-danger">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Are you sure you want to delete <br> <strong>"{{ $my_user->name }}"</strong> ?</h4>
                                                </div>

                                                <form action="{{ route('delete_user', $my_user->user_id) }}" method="POST">
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
                                </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>User Name</th>
                                    <th>Restaurant Name</th>
                                    <th>User Email</th>
                                    <th>User Type</th>
                                    <th>Created At</th>
                                    <th>Edit</th>
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

    <!-- Add New User Modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-default">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                    <h4 class="modal-title">Add New User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="POST" action="{{ route('add_user')}}" enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Created By</label>
                            <div class="col-sm-8">
                                <input type="email" name="created_by_email" class="form-control" value="{{Auth::user()->email}}" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">User Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control" id="inputName" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">User Email</label>
                            <div class="col-sm-8">
                                <input type="Email" name="email" class="form-control" id="inputName2" placeholder="Email" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-8">
                                <input type="Password" name="password" class="form-control" id="inputName2" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Confirm Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">User Type</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="user_type" style="width: 100%;" required>
                                    <option value="Manager">Manager</option>
                                    <option value="Booking_Manager">Booking Manager</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Select Restaurant</label>
                            <div class="col-sm-8">
                                <select class="select2bs4" name="restaurant_id[]" multiple="multiple" id="walkin_tables" data-placeholder="Select Table"
                                    style="width: 100%; list-style: none;">
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{$restaurant->id}}" class="form-control">{{$restaurant->Restaurant_name}}</option>
                                    @endforeach
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

@section('scripts')
<script type="application/javascript">
    $(document).ready(function(e){
e.preventDefault;

$('select[multiple]').multiselect();

$('#walkin_tables').multiselect({
    columns: 1,
    placeholder: 'Select Restaurant',
    search: true
});

$('#user_restaurant').multiselect({
    columns: 1,
    placeholder: 'Select Restaurant',
    search: true
});
});
</script>

@endsection