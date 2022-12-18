@extends('layouts.admin_header')

@section('content')


<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Restaurants and Users </h1>
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


        <!-- /.card-header -->
              <div class="card-body">
                <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                <div id="accordion1">
                  @foreach ($restaurants as $restaurant)
                  <div class="card card-custom">
                    <div class="card-header" style="background-color: #0065A3; color: #fff;">
                      <div class="row">
                        <div class="col-sm-1" style="float: left;">
                          <p style="color: #fff; float: left;">{{ ++$i }}</p>
                        </div>
                        <div class="col-sm-3 open_users_modal" title="{{ $restaurant->Restaurant_name}}" 
                          id="{{ url('get_restaurant_users', $restaurant->id) }}" data-href="{{$restaurant->id}}" 
                          style="float: left;">
                          <h4 class="card-title w-100">
                            <a class="d-block w-100 text-white" data-toggle="collapse" href="#collapseOne{{$restaurant->id}}">
                              {{ $restaurant->Restaurant_name}}
                            </a>
                          </h4>
                        </div>
                        <div class="col-sm-3 open_users_modal" title="{{ $restaurant->Restaurant_name}}" 
                          id="{{ url('get_restaurant_users', $restaurant->id) }}" data-href="{{$restaurant->id}}" 
                          style="float: left;">
                          <h4 class="card-title w-100">
                            <a class="d-block w-100 text-white" data-toggle="collapse" href="#collapseOne{{$restaurant->id}}">
                              {{ $restaurant->Restaurant_email}}
                            </a>
                          </h4>
                        </div>
                        <div class="col-sm-2" style="float: left;">
                          @if($restaurant->Restaurant_photo == "")
                          <h4 class="card-title w-100">N/A</h4>
                          @else
                          <img src="{{ asset('images/restaurant') }}/{{ $restaurant->Restaurant_photo}}" height="75" width="100">
                          @endif
                        </div>
                        <div class="col-sm-2" style="float: right;">
                          <button type="button" class="btn btn-custom" style="background-color: #5BC464;" data-toggle="modal" data-target="#modal-default_add_user{{$restaurant->id}}">
                            <i class="fa fa-plus" aria-hidden="true" title="Add User" style="color: #fff;"></i>
                          </button>
                          <button type="button" class="btn btn-custom" style="background-color: #5BC464;" data-toggle="modal" data-target="#modal-default_edit_restaurant{{$restaurant->id}}">
                            <i class="fa fa-edit" aria-hidden="true" title="Edit Restaurant" style="color: #fff;"></i>
                          </button>
                          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-danger_restaurant{{$restaurant->id}}">
                            <i class="fa fa-trash" aria-hidden="true" title="Delete Restaurant" style="color: #fff;"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div id="collapseOne{{$restaurant->id}}" class="collapse fade" data-parent="#accordion1">
                      <div class="card-body">
                        <!-- Restaurant users table -->
                        <!-- Default box -->
                          <div class="col-lg-12">
                            <div class="card">
                            <!-- All Reservations Table -->
                              <!-- /.card-header -->
                              <div class="card-body">
                                <table id="example1" class="table table-hover table-head-fixed text-nowrap">
                                  <thead>
                                  <tr>
                                      <th>#</th>
                                      <th>User Name</th>
                                      <th>User Email</th>
                                      <th>User Role</th>
                                      <th>User Image</th>
                                      <th>Created At</th>
                                      <th>Action</th>
                                  </tr>
                                  </thead>
                                  <tbody id="restaurant_users_table{{$restaurant->id}}">
                                    
                                  </tbody>
                              </table>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>

<!-- Add new user to restaurant controller -->
<!-- Add New User Modal -->
<div class="modal fade" id="modal-default_add_user{{$restaurant->id}}">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                <h4 class="modal-title">Add New User to {{ $restaurant->Restaurant_name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="POST" action="{{ route('add_user')}}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Restaurant Owner Email</label>
                        <div class="col-sm-8">
                            <input type="email" name="created_by_email" class="form-control" value="{{$restaurant->email}}" readonly required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control" id="inputName" placeholder="Name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="Email" name="email" class="form-control" id="inputName2" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-4 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type="Password" name="password" class="form-control" id="inputName2" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-4 col-form-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-4 col-form-label">User Type</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="user_type" style="width: 100%;" required>
                                <option selected disabled>Select Role</option>
                                <option value="Manager">Manager</option>
                                <option value="Booking_Manager">Booking Manager</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="restaurant_id[]" value="{{$restaurant->id}}" style="display: none;">

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


<!-- show or edit restaurant Modal Starts Here -->
<div class="modal fade" id="modal-default_edit_restaurant{{$restaurant->id}}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ $restaurant->Restaurant_name}} Profile</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-sm-6">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  @if($restaurant->Restaurant_logo != "")
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('images/restaurant/logo') }}/{{ $restaurant->Restaurant_logo}}"
                       alt="User profile picture">
                  @else
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('images/restaurant') }}/{{ $restaurant->Restaurant_photo}}"
                       alt="User profile picture">
                  @endif
                </div>

                <h3 class="profile-username text-center">{{ $restaurant->Restaurant_name}}</h3>

                <p class="text-muted text-center">{{ $restaurant->Restaurant_type}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Address</b> <a class="float-right">{{$restaurant->Restaurant_Country}}, {{ $restaurant->Restaurant_address}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Phone Number</b> <a class="float-right">{{$restaurant->Restaurant_phone}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right">{{$restaurant->Restaurant_email}}</a>
                  </li>
                </ul>

                @if($restaurant->featured == 'yes')
                <a href="{{ route('featured', $restaurant->id) }}" class="btn btn-success btn-block"><b>Featured</b></a>
                @else
                <a href="{{ route('featured', $restaurant->id) }}" class="btn btn-warning btn-block"><b>Not Featured</b></a>
                @endif

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-sm-6">
            <div class="card card-primary card-outline">
            
            <div class="card-body">
              <form method="POST" action="{{ route('update_restaurant') }}" enctype="multipart/form-data">
                @csrf

              <div class="form-group">
                <label for="inputName">Restaurant Owner Email</label>
                <input type="email" name="email" value="{{ $restaurant->email}}" class="form-control" required disabled>
              </div>
              <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}" style="display: none;">
              <div class="form-group">
                <label for="inputStatus">Featured</label>
                <select id="inputStatus" class="form-control custom-select" name="featured" required>
                  @if($restaurant->featured == 'yes')
                  <option value="yes" selected>Yes</option>
                  <option value="no">No</option>
                  @else
                  <option value="yes">Yes</option>
                  <option value="no" selected>No</option>
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label for="inputClientCompany">Number of Restaurants</label>
                <input type="number" name="no_of_restaurants" id="inputClientCompany" class="form-control" value="{{$restaurant->no_of_restaurants}}">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">
                  Update
                </button>
              </div>
            </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Delete Restaurant Modal Starts Here-->
<div class="modal fade" id="modal-danger_restaurant{{$restaurant->id}}">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure you want to delete <br>
                    <strong>"{{ $restaurant->Restaurant_name}}"</strong> ?</h4>
            </div>
            <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST">
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
                </div>
              </div>
              <!-- /.card-body -->


              <!-- <div class="card-header">
                <h3 class="card-title"><a href="{{ url('/administrator/create')}}">
                	<button type="button" class="btn btn-block btn-primary">Add New User</button>
                </a>
            </h3>
              </div>-->
              <!-- /.card-header -->
              
              <!-- /.card-body -->
            </div>
      <!-- /.card -->
  </div>
  </div>
</div>
</div>

<!-- Modal Starts Here-->
<div class="modal fade" id="modal-danger_user">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure you want to delete <br>
                    <strong id="username"></strong> ?</h4>
            </div>
            <form id="delete_form" action="" method="POST">
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
@section('scripts')

<script>
  $('.open_users_modal').click(function(e){
    e.preventDefault;

    var restaurant_users_url = $(this).attr('id');
    var restaurant = $(this).attr('title');
    var restaurant_id = $(this).attr('data-href');
    var restaurant_name = restaurant+' Users';
    $("#restaurant_name").text(restaurant_name);

    $.ajax({
        url: restaurant_users_url,
        type: 'GET',
        success: function(restaurant_users) {
          var users = $.parseJSON(restaurant_users);
          
          if(users.length == 0)
          {
            toastr.error("this restaurant has no user !!");
          }
          else{

            var len = users.length;
            var wrapper = $("#restaurant_users_table"+restaurant_id);
            $(wrapper).empty();

            for(let f = 0; f<len; f++ )
            {
              const no = f + 1;
              const user_id = users[f]['id'];
              const name = users[f]['name'];
              const email = users[f]['email'];
              const user_type = users[f]['user_type'];
              const user_image = users[f]['user_image'];
              const created_at = users[f]['created_at'];

              console.log(name);
              console.log(f);
              console.log(no);

              $(wrapper).append("<tr>");
              $(wrapper).append("<td>" + no + "</td>");
              $(wrapper).append("<td>" + name + "</td>");
              $(wrapper).append("<td>" + email + "</td>");
              $(wrapper).append("<td>" + user_type + "</td>");
              if(user_image == null)
              {
                $(wrapper).append("<td>" + "N/A" + "</td>");
              }
              else
              {
                $(wrapper).append("<td>" + "<img src='' height='50' width='70' id='img_"+user_id+"'>" + "</td>");
                $("#img_"+user_id).attr('src', "{{ asset('images/users')}}" + "/" + user_image);
              }
              $(wrapper).append("<td>" + created_at + "</td>");
              $(wrapper).append("<td>" + "<button type='submit' title='delete' style='border: none; background-color:transparent;' data-toggle='modal' data-target='#modal-danger_user' id='"+user_id+"'>" + 
                "<i class='fas fa-trash fa-lg text-danger delete_btn'></i>" + 
                "</button>" + "</td>");
              $(wrapper).append("</tr>");

              var delete_link = "{{route('delete_user', '')}}"+"/"+user_id;
              $("#username").text(name);
              console.log(delete_link);

              $("#delete_form").attr('action', delete_link);
            }
            
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            var err = eval("(" + xhr.responseText + ")");
            //console.log(xhr.status);
            console.log(err);
        }
    });

  });

  /**$(".delete_btn").click(function(){

    var user_id = $(this).attr('id');
    alert(user_id);
  });*/
</script>

@endsection
