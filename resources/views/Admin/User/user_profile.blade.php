@extends('layouts.profile_header')

@section('content')

<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">User Profile </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Admin</a></li>
              <li class="breadcrumb-item active">Restaurant</li>
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

<!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                	@if(Auth::user()->user_image == "")
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('images/user_avatar.jpeg')}}"
                       alt="User profile picture">
                    @else
                    <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('images/users') }}/{{ Auth::user()->user_image }}"
                       alt="User profile picture">
                    @endif
                </div>

                <h3 class="profile-username text-center">{{ Auth::user()->name}}</h3>

                <p class="text-muted text-center">Member since {{ date_format(date_create(Auth::user()->created_at), 'jS M Y')}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Phone Number</b> <a class="float-right">{{ Auth::user()->user_phone_number}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Email Address</b> <a class="float-right">{{ Auth::user()->email}}</a>
                  </li>
                </ul>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->
          <div class="col-md-7">
            <div class="card card-primary card-outline">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <h3> Edit User Profile </h3>

                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">

                <div class="tab-pane" id="settings">
                 <form method="POST" action="{{ route('accounts.update', Auth::user()->id) }}" enctype="multipart/form-data" class="form-horizontal">
                      @csrf
                      @method('PUT')
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" name="name" class="form-control" id="inputName" value="{{Auth::user()->name}}" placeholder="Name" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Phone Number</label>
                        <div class="col-sm-10">
                          <input type="text" name="user_phone_number" value="{{Auth::user()->user_phone_number}}" class="form-control" id="inputName2" placeholder="Phone Number" required disabled>
                        </div>
                      </div>
                     <div class="form-group row">
                         <label for="inputName2" class="col-sm-2 col-form-label">New Password</label>
                         <div class="col-sm-10">
                             <input type="password" name="password" class="form-control" placeholder="New Password">
                         </div>
                     </div>
                     <div class="form-group row">
                         <label for="inputName2" class="col-sm-2 col-form-label">Confirm Password</label>
                         <div class="col-sm-10">
                             <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                         </div>
                     </div>
                      <div class="form-group row">
                        <label for="inputSkills exampleInputFile" class="col-sm-2 col-form-label">Profile Image</label>
                        <div class="col-sm-10">
                        <div class="custom-file">
                        <input type="file" name="user_image" value="{{Auth::user()->user_image}}" class="custom-file-input" id="exampleInputFile" required>
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                        </div>
                      </div>
                      <br>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->



@endsection
