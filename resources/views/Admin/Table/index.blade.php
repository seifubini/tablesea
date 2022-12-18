@extends('layouts.admin_header')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tables </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
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
                            @if(Auth::user()->user_type != "Administrator" || Auth::user()->user_type != "Client" || Auth::user()->user_type != "Booking_Manager")
                            <h3 class="card-title"><a href="{{ route('create_table', $restaurant_id) }}">
                                    <button type="button" class="btn btn-block btn-primary">Add Table</button>
                                </a>
                            </h3>
                            @endif
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Table Code</th>
                                    <th>Min Covers</th>
                                    <th>Max Covers</th>
                                    <th>Table Price</th>
                                    <th>Table is Booked</th>
                                    <th>Table Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach ($tables as $table)
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $table->table_name }}</td>
                                        <td>{{ $table->min_covers }}</td>
                                        <td>{{ $table->max_covers }}</td>
                                        <td>{{ $table->table_price }}</td>
                                        <td>{{ $table->table_is_booked}}</td>
                                        @if($table->table_status == 'active')
                                            <td><span class="right badge badge-success">Active</span></td>
                                        @else
                                            <td><span class="right badge badge-danger">InActive</span></td>
                                        @endif
                                        @if(Auth::user()->user_type != "Administrator" || Auth::user()->user_type != "Client" || Auth::user()->user_type != "Booking_Manager")
                                        <td>
                                            <a href="{{ route('tables.edit', $table->id) }}">
                                                <i class="fas fa-edit  fa-lg"></i>Edit
                                            </a>
                                        </td>
                                        @endif
                                </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Table Code</th>
                                    <th>Min Covers</th>
                                    <th>Max Covers</th>
                                    <th>Table Price</th>
                                    <th>Table is Booked</th>
                                    <th>Table Status</th>
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

@endsection
