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
                  @if(Auth::user()->user_type == "Restaurant")
                  @if($restaurant_count < Auth::user()->no_of_restaurants )
                  <h3 class="card-title" style="float: left;">
                      <a href="{{url('/restaurants/create')}}">
                          <button type="button" class="btn btn-block btn-custom" style="background-color: #0065A3; color: #fff;">
                              Add New Restaurant</button>
                      </a>
                  </h3>
                  @else
                  <h3 class="card-title" style="float: left;">
                      <a href="#">
                          <button type="button" class="btn btn-block btn-custom" disabled="disabled" style="background-color: #0065A3; color: #fff;">
                              Add New Restaurant</button>
                      </a>
                  </h3>
                  @endif
                  <h3 class="card-title" style="padding-left: 5%;">
                          <button type="button" class="btn btn-block btn-custom" style="background-color: #0065A3; color: #fff;"
                                  data-toggle="modal" data-target="#modal-default">
                              Add New User</button>
                  </h3>
                  
                  <h3 class="card-title" style="padding-left: 5%;">
                      <button type="button" class="btn btn-block btn-custom" style="background-color: #0065A3; color: #fff;"
                              data-toggle="modal" data-target="#modal-default_membership">
                          Add New Membership</button>
                  </h3>
                  
                  <h3 class="card-title" style="padding-left: 5%;">
                      <button type="button" class="btn btn-block btn-custom" style="background-color: #0065A3; color: #fff;"
                              data-toggle="modal" data-target="#modal-default_guest_type">
                          Add New Guest Type</button>
                  </h3>
                  
                @endif
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                  	<th>No</th>
                    <th>Restaurant Name</th>
                    <th>Restaurant Country</th>
                    <th>Restaurant City</th>
                    <th>Restaurant Address</th>
                    <th>Restaurant Phone</th>
                    <th>Reservation Update Type</th>
                    <th>Restaurant Capacity</th>
                    <th>Restaurant Hours</th>
                    <th>Restaurant Duration</th>
                    <th>Restaurant Photo</th>
                      @if(Auth::user()->user_type == "Restaurant")
                    <th>Action</th>
                      @endif
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                  	@foreach ($restaurants as $restaurant)
                    <td>{{ ++$i }}</td>
                    <td>{{ $restaurant->Restaurant_name}}</td>
                    <td>{{ $restaurant->Restaurant_Country}}</td>
                    <td>{{ $restaurant->Restaurant_City}}</td>
                    <td>{{ $restaurant->Restaurant_address}}</td>
                    <td>{{ $restaurant->Restaurant_phone}}</td>
                    <td>{{ $restaurant->Reservation_update_type}}</td>
                    <td>{{ $restaurant->Restaurant_max_capacity }} People</td>
                    <td>
                        {{date_format(date_create($restaurant->restaurant_opening_hour), 'H:i') }}
                        - {{date_format(date_create($restaurant->restaurant_closing_hour), 'H:i')}}
                    </td>
                    <td>{{ $restaurant->Restaurant_duration }} minutes</td>
                    @if($restaurant->Restaurant_photo == "")
                    <td>N/A</td>
                    @else
                    <td><img src="{{ asset('images/restaurant') }}/{{ $restaurant->Restaurant_photo}}" height="75" width="100"></td>
                    @endif
                    @if(Auth::user()->user_type == "Restaurant")
                    <td>
                      <a href="{{ route('restaurants.edit', $restaurant->id) }}" style="color: #0065A3;">
                            <i class="fas fa-edit fa-lg" style="color: #0065A3;"></i>Edit
                        </a>
                    </td>
                    @endif
                  </tr>
                  @endforeach

                  </tbody>
                  <tfoot>
                  <tr>
                  	<th>No</th>
                    <th>Restaurant Name</th>
                    <th>Restaurant Country</th>
                    <th>Restaurant City</th>
                    <th>Restaurant Address</th>
                    <th>Restaurant Phone</th>
                    <th>Reservation Update Type</th>
                    <th>Restaurant Capacity</th>
                    <th>Restaurant Hours</th>
                    <th>Restaurant Duration</th>
                    <th>Restaurant Photo</th>
                      @if(Auth::user()->user_type == "Restaurant")
                    <th>Action</th>
                      @endif
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
                                <select class="select2bs4" name="restaurant_id[]" multiple="multiple" id="restaurant_names" data-placeholder="Select Restaurant"
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


<!-- Add New Membership Modal -->
<div class="modal fade" id="modal-default_membership">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                <h4 class="modal-title">Add New Membership</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="POST" action="{{ route('membership.store')}}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Created By</label>
                        <div class="col-sm-6">
                            <input type="email" name="created_by_email" class="form-control" value="{{Auth::user()->email}}" readonly required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Membership Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="membership_name" class="form-control" id="inputName" placeholder="Name" required>
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
                        Add Membership</button>
                </form>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


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

@section('scripts')
<script type="application/javascript">
    $(document).ready(function(e){
        e.preventDefault;
        
        $('select[multiple]').multiselect();
        
        $('#restaurant_names').multiselect({
        columns: 1,
        placeholder: 'Select Tables',
        search: true
        });
    });
</script>

@endsection