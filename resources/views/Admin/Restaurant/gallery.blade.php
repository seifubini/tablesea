@extends('layouts.backend_header')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Restaurant Gallery </h1>
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
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <p>{{ $message }}</p>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">

            <div class="col-12">

                <div class="card card-primary">

                    <div class="card-header">
                        <button type="button" class="btn btn-outline-light" data-toggle="modal" data-target="#modal-default">
                            Add Image
                        </button>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="btn-group w-100 mb-2">
                                <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> All items </a>
                                @foreach($restaurants as $restaurant_name)
                                <a class="btn btn-info" href="javascript:void(0)" data-filter="{{ $restaurant_name->Restaurant_name }}">
                                    {{ $restaurant_name->Restaurant_name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <div class="filter-container p-0 row">
                                @foreach($images as $image)
                                <div class="filtr-item col-sm-2" data-category="{{$image->Restaurant_name}}" data-sort="white sample">
                                    <a href="javascript:void(0)" data-toggle="lightbox" data-title="sample {{$image->Restaurant_name}} - white">
                                        <img src="{{ asset('images/gallery') }}/{{ $image->restaurant_image_path}}" height="250px"
                                             class="img-fluid mb-2" alt="white sample"/>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Add image modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Image</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- form start -->
                    <form method="POST" action="{{ route('my_gallery.store') }}" enctype="multipart/form-data">
                        <!-- /.input group -->
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Restaurant Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" value="{{Auth::user()->email}}"
                                   placeholder="{{Auth::user()->email}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Select Restaurant</label>
                            <select class="form-control select2" name="restaurant_id" style="width: 100%;">
                                @foreach($restaurants as $name)
                                    <option value="{{ $name->id }}">{{ $name->Restaurant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="created_by" value="{{Auth::user()->id}}" style="display: none;">
                        <div class="form-group">
                            <label for="exampleInputFile">Restaurant Photo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="Restaurant_photo" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose Photo</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
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
