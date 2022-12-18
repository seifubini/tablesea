@extends('layouts.admin_header')

@section('content')


<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Subscribers</h1>
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
    @elseif($message = Session::get('error'))
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
                  <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal-default">
                    Create Subscription
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
                    <th>Restaurant Name</th>
                    <th>Restaurant Email</th>
                    <th>Subscription Code</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Remaining</th>
                    <th>Created Date</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($packages as $package)
                      <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $package->user_name }}</td>
                        <td>{{ $package->restaurant_name }}</td>
                        <td>{{ $package->user_email }}</td>
                        <td>{{ $package->subscription_code }}</td>
                        <td>{{ date_format(date_create($package->subscription_start_date), 'jS M Y') }}</td>
                        <td>{{ date_format(date_create($package->subscription_expire_date), 'jS M Y') }}</td>
                        <td>{{ $package->number_of_days }} Days</td>
                        <td>
                          @php
                          $date1 = time();
                          $date2 = strtotime($package->subscription_expire_date);
                          $date_diff = $date2 - $date1;
                          $date_diff = $date_diff / (60 * 60 * 24);
                          if($date_diff > 1)
                          {
                            $diff = round($date_diff);
                          }
                          elseif($date_diff < 1 && $date_diff > 0)
                          {
                            $diff = 1;
                          }
                          elseif($date_diff == 0)
                          {
                            $diff = 0;
                          }
                          else
                          {
                            $diff = 0;
                          }
                          @endphp
                          {{$diff}} Days
                        </td>
                        <td>{{ date_format(date_create($package->created_at), 'jS M Y h:i A') }}</td>
                        <td>
                          @if($package->subscription_status == 'Active')
                            <span class="right badge badge-success">Active</span>
                          @else
                            <span class="right badge badge-danger">Expired</span> 
                          @endif
                        </td>
                        <td>
                          <a class="btn btn-app" data-toggle="modal" data-target="#modal-default_edit{{$package->id}}" 
                            style="border: none; background-color: transparent;">
                            <i class="fas fa-edit fa-lg"></i>
                          </a>
                        </td>
                      </tr>
                      <!-- edit subscription package -->
                      <div class="modal fade" id="modal-default_edit{{$package->id}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Subscription</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <!-- form start -->
                              <form method="POST" action="{{ route('package_manager.update', $package->id) }}
                                "enctype="multipart/form-data">
                                      <!-- /.input group -->
                                      @csrf
                                      @method('PUT')

                                      <div class="card-body">
                                        <div class="form-group">
                                          <label for="exampleInputPassword1">Restaurant Name</label>
                                          <input type="text" readonly class="form-control" id="exampleInputName1" value="{{ $package->restaurant_name}}">
                                        </div>
                                        <div class="form-group">
                                          <label>Select Restaurant</label>
                                          <select class="form-control select2" disabled style="width: 100%;">
                                            @foreach($restaurants as $restaurant)
                                            @if($restaurant->id == $package->restaurant_id)
                                              <option value="{{ $restaurant->id }}" selected>
                                              {{$restaurant->Restaurant_name}}/{{$restaurant->Restaurant_address}}</option>
                                            @else
                                              <option value="{{ $restaurant->id }}">
                                              {{$restaurant->Restaurant_name}}/{{$restaurant->Restaurant_address}}</option>
                                            @endif
                                            @endforeach
                                          </select>
                                        </div>
                                        <input type="hidden" name="restaurant_id" style="display: none;" 
                                          value="{{$package->restaurant_id}}" >
                                        <input type="hidden" name="identity" style="display: none;" 
                                          value="{{$package->id}}" >
                                          <input type="hidden" name="updated_by" value="{{Auth::user()->id}}" >
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Subscription Start Date</label>
                                            <input type="date" value="{{$package->subscription_start_date}}" 
                                              class="form-control" required name="subscription_start_date">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Subscription End Date</label>
                                            <input type="date" value="{{$package->subscription_expire_date}}" 
                                              class="form-control" required name="subscription_expire_date">
                                        </div>
                                        <input type="hidden" name="created_by" value="{{Auth::user()->id}}" >
                                        <div class="form-check">
                                          @if($package->subscription_status == 'Active')
                                            <input type="checkbox" checked name="subscription_status" class="form-check-input" id="exampleCheck1">
                                          @else
                                            <input type="checkbox" name="subscription_status" class="form-check-input" id="exampleCheck1">
                                          @endif
                                          <label class="form-check-label" for="exampleCheck1">Item is Active</label>
                                        </div>
                                      </div>
                                      <!-- /.card-body -->

                                      <div class="card-footer">
                                        <button type="submit" class="btn btn-block btn-primary">Update</button>
                                      </div>
                                    </form>
                            </div>
                            
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
                    <th>Restaurant Name</th>
                    <th>Restaurant Email</th>
                    <th>Subscription Code</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Remaining</th>
                    <th>Created Date</th>
                    <th>Status</th>
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

<!-- create new subscription package -->
<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Subscription</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- form start -->
              <form method="POST" action="{{ route('package_manager.store') }}" enctype="multipart/form-data">
                <!-- /.input group -->
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label>Select Restaurant Owner</label>
                    <select class="form-control select2" name="restaurant_id" required style="width: 100%;">
                      @foreach($restaurants as $restaurant)
                      <option value="{{ $restaurant->id }}">{{$restaurant->name}}/ {{$restaurant->Restaurant_name}}/{{$restaurant->Restaurant_address}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                      <label for="exampleInputEmail1">Subscription Start Date</label>
                      <input type="date" class="form-control" required name="subscription_start_date">
                  </div>
                  <div class="form-group">
                      <label for="exampleInputEmail1">Subscription End Date</label>
                      <input type="date" class="form-control" required name="subscription_expire_date">
                  </div>
                  <input type="hidden" name="created_by" value="{{Auth::user()->id}}" >
                  <div class="form-check">
                    <input type="checkbox" required name="subscription_status" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Item is Active</label>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-block btn-primary">Create</button>
                </div>
              </form>
      </div>
      
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection