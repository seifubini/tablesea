@extends('layouts.admin_header')

@section('content')


<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Restaurant Types </h1>
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
                <h3 class="card-title"><a href="{{ url('/add_type')}}">
                	<button type="button" class="btn btn-block btn-primary">Add New Restaurant Type</button>
                </a>
            </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                  	<th>No</th>
                    <th>Restaurant Type Name</th>
                    <th>Restaurant Type Photo</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($types as $type)
                  <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $type->Restaurant_Type_Name}}</td>
                    <td><img src="{{ asset('images/Restaurant_Type')}}/{{ $type->Restaurant_Type_Photo}}" height="100px" width="100px"></td>
                  </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                  	<th>No</th>
                    <th>Restaurant Type Name</th>
                    <th>Restaurant Type Photo</th>
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

@endsection