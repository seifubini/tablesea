@extends('layouts.main')

@section('content')

<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6" style="padding-left: 1%; font-family: Lato, sans-serif; font-weight: bold; font-size: 30px;">
            <h1 class="m-0">Promotions you Win </h1>
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
    @endif
	
		<br>

		<!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      	<div class="row" style="padding-left: 1%; padding-right: 1%;">
<!-- Default box -->
<div class="col-lg-12">
      <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                  	<th>No</th>
                    <th>Promotion Name</th>
                    <th>Restaurant Name</th>
                    <th>Restaurant Address</th>
                    <th>User Name</th>
                    <th>Win Expire Date</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                  	@foreach ($wins as $win)
                    <td>{{ ++$i }}</td>
                    <td>{{ $win->win_result}}</td>
                    <td>{{ $win->Restaurant_name}}</td>
                    <td>{{ $win->Restaurant_address}}</td>
                    <td>{{ $win->name}}</td>
                    <td>{{ date_format(date_create($win->created_at), 'jS M Y') }}</td>
                  </tr>
                  @endforeach
                  
                  </tbody>
                  <tfoot>
                  <tr>
                  	<th>No</th>
                    <th>Result Name</th>
                    <th>Restaurant Name</th>
                    <th>Restaurant Address</th>
                    <th>User Name</th>
                    <th>Win Date</th>
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