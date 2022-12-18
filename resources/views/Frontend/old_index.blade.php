@extends('layouts.front_header')

@section('content')


<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> Welcome to TechBiz <small>Restaurants Listing</small></h1>
          </div><!-- /.col 
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Layout</a></li>
              <li class="breadcrumb-item active">Top Navigation</li>
            </ol>-->
         <!-- </div> /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

  <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">
        	@foreach($restaurants as $restaurant)
          <div class="col-lg-6">
          	<div class="card">
            <div class="col-sm-12">
                    <div class="position-relative">
                      <img src="{{ asset('images/restaurant') }}/{{ $restaurant->Restaurant_photo}}" alt="Photo 1" class="img-fluid">
                      <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-success text-lg">
                            {{ $restaurant->Restaurant_name}}
                        </div>
                      </div>

                     <strong><i class="fa fa-eye" aria-hidden="true"></i>
                     	<a href="{{ route('restaurants.show', $restaurant->id) }}"> 
                     		{{ $restaurant->Restaurant_name}} </strong> </a>
                        
                     		<!--<a href="#" data-toggle="modal" data-target="#modal-lg"></a>-->
                     	<br /><i class="fas fa-lg fa-building" aria-hidden="true"></i> 
                     {{ $restaurant->Restaurant_address}} <br />
                      <small>.ribbon-wrapper.ribbon-xl .ribbon.text-xl</small>
                    </div>
                  </div>
              </div>
            <!-- /.card -->
          </div>
          @endforeach
          <!-- /.col-md-6 -->
          
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->


<div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Choose Your Menu Type</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-lg-6">
            			<img src="{{ asset('images/flip_menu.jpg')}}" alt="Photo 1" class="img-fluid">
            			<a href="{{ url('/menu_selector', $restaurant->id, '$flip_menu') }}"><h5>Flip/Picture Menu</h5></a>
            		</div>
            		<div class="col-lg-6">
            			<img src="{{ asset('images/flip_menu.jpg')}}" alt="Photo 1" class="img-fluid">
            			<a href="{{ route('restaurants.show', $restaurant->id) }}"><h5>Order Menu</h5></a>
            		</div>
            	</div>
            </div>
            
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

@endsection

