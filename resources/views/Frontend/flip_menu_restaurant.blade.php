@extends('layouts.front_header')

@section('content')


<div class="col-12">
            <div class="card card-primary">
              <div class="card-header">
                <h4 class="card-title">Our Flip Menu List</h4>
              </div>
              <div class="card-body">
                <div class="row">
                   @foreach($menus as $menu)
                  <div class="col-sm-2">
                    <a href="https://via.placeholder.com/1200/FFFFFF.png?text=1" data-toggle="lightbox" data-title="sample 1 - white" data-gallery="gallery">
                      <img src="{{ asset('images/menu') }}/{{ $menu->menu_picture}}" class="img-fluid mb-2" alt="white sample"/>
                    </a>
                    <h5>{{ $menu->menu_name}}</h5>
                  </div>
                  @endforeach
                  
                </div>
              </div>
            </div>
          </div>

@endsection