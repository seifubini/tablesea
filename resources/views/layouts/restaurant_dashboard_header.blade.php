<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TableSea Administration Page</title>

    <!-- Google Font: Source Sans Pro-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css')}}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.css') }}">
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/main.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Multiple Select Plugins -->
    <script src="{{ asset('multiple_select/jquery.multiselect.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('multiple_select/jquery.multiselect.css') }}">
    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    
    <!-- spinner style when clicking edit reservation modal -->
    <style type="text/css">
        #example1_wrapper button {
          background-color: #0065A3;
          border-color: #0065A3;
          color: #ffffff;
        }
        
        #cover-spin {
            position:fixed;
            width:100%;
            left:0;right:0;top:0;bottom:0;
            background-color: rgba(255,255,255,0.7);
            z-index:9999;
            display:none;
        }

        @-webkit-keyframes spin {
            from {-webkit-transform:rotate(0deg);}
            to {-webkit-transform:rotate(360deg);}
        }

        @keyframes spin {
            from {transform:rotate(0deg);}
            to {transform:rotate(360deg);}
        }

        #cover-spin::after {
            content:'';
            display:block;
            position:absolute;
            left:48%;top:40%;
            width:40px;height:40px;
            border-style:solid;
            border-color:#0065A3;
            border-top-color:transparent;
            border-width: 4px;
            border-radius:50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }

        #example1_wrapper button {
          background-color: #0065A3;
          border-color: #0065A3;
          color: #ffffff;
        }

    </style>
    
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="sidebar-mini sidebar-collapse">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center" style="background-color: #0065A3;">
        <img class="animation__shake" src="{{ asset('images/TableSeaWhite.PNG') }}" alt="AdminLTELogo" height="250px" width="250px">
    </div>
    
    <!-- spinner -->
    <div id="cover-spin" class="align-items-center"></div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #0065A3; color: #fff;">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="background-color: #0065A3; color: #fff;">
                    <i class="fas fa-bars" style="background-color: #0065A3; color: #fff;"></i>
                </a>
            </li>
            @if(Auth::user()->user_type !== "Administrator")
                <li class="nav-item active d-none d-sm-inline-block dropdown" style="background-color: #0065A3; color: #fff;">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"
                       style="background-color: #0065A3; color: #fff;">
                        Restaurants</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li>
                            <a href="{{url('/restaurants')}}" class="dropdown-item" style="color: #0065A3;">
                                Your Restaurants
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        @foreach($restaurants as $rest)
                            <li>
                                <a href="{{ route('create_reservation', $rest->id) }}" class="dropdown-item" style="color: #0065A3;">
                                    {{$rest->Restaurant_name}}
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item d-none d-sm-inline-block dropdown" style="background-color: #0065A3; color: #fff;">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       class="nav-link active dropdown-toggle" style="background-color: #0065A3; color: #fff;">
                        Reports</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li>
                            <a href="{{ url('/dashboard')}}" class="dropdown-item" style="color: #0065A3;">
                                Overall Reports
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        @foreach($restaurants as $restaurant)
                            <li>
                                <a href="{{ url('/restaurant_dashboard', $restaurant->id)}}" class="dropdown-item" style="color: #0065A3;">
                                    {{$restaurant->Restaurant_name}} Report
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="background-color: #0065A3; color: #fff; padding-left: 2%; padding-right: 1.5%; width: 370px;">
                  <div id="reportrange" class="form-inline form-control" style="background: #fff; color: #000; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: auto">
                          <i class="fa fa-calendar"></i>&nbsp;
                          <span></span> <i class="fa fa-caret-down"></i>
                  </div>
              </li>&nbsp;&nbsp;
                <form action="{{ route('dashboard_sort_date') }}" method="POST">
                    <div class="row">
                      @csrf
                      <li class="nav-item d-none d-sm-inline-block">
                        <input type="text" id="dashboard_range" name="dashboard_range" hidden style="display: none;">
                        <div class="row">
                          <div class="col-lg-4 col-4" style="padding-right: 1.5%;">
                            <div class="input-group date" id="timepicker1" data-target-input="nearest">
                                <input type="time" name="start_time" 
                                       class="form-control datetimepicker-input" data-target="#timepicker"/>
                            </div>
                          </div>
                          <div class="col-lg-4 col-4" style="padding-right: 1.5%;">
                            <div class="input-group date" id="timepicker2" data-target-input="nearest">
                              <input type="time" name="end_time" 
                                     class="form-control datetimepicker-input" data-target="#timepicker"/>
                          </div>
                          </div>
                          <div class="col-lg-4 col-4" style="padding-right: 1.5%;">
                              
                                <button type="submit" class="btn btn-outline-light">Search</button>
                              
                          </div>
                          
                        </div>
                      
                      </li>&nbsp;&nbsp;
                      
                    </div>
                  </form>
            @endif
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button" style="background-color: #0065A3; color: #fff;">
                <i class="fas fa-search" style="background-color: #0065A3; color: #fff;"></i>
              </a>
              <div class="navbar-search-block" style="background-color: #0065A3; color: #fff;">
                <form class="form-inline">
                  <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                      <button class="btn btn-navbar" type="submit" style="background-color: #0065A3; color: #fff;">
                        <i class="fas fa-search" style="background-color: #0065A3; color: #fff;"></i>
                      </button>
                      <button class="btn btn-navbar" type="button" data-widget="navbar-search" style="background-color: #0065A3; color: #fff;">
                        <i class="fas fa-times" style="background-color: #0065A3; color: #fff;"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>-->

            <!-- Account Dropdown Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" style="background-color: #0065A3; color: #fff;">
                    @if(Auth::user()->user_image == "")
                        <img src="{{ asset('images/user_avatar.jpeg')}}" class="user-image img-circle elevation-2" alt="User Image">
                    @else
                        <img src="{{ asset('images/users') }}/{{ Auth::user()->user_image }}" class="user-image img-circle elevation-2" alt="User Image">
                    @endif
                    <span class="d-none d-md-inline" style="background-color: #0065A3; color: #fff;">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="background-color: #0065A3; color: #fff;">
                    <!-- User image -->
                    <li class="user-header" style="background-color: #0065A3; color: #fff;">
                        @if(Auth::user()->user_image == "")
                            <img src="{{ asset('images/user_avatar.jpeg')}}" class="img-circle elevation-2" alt="User Image">
                        @else
                            <img src="{{ asset('images/users') }}/{{ Auth::user()->user_image }}" class="img-circle elevation-2" alt="User Image">
                        @endif
                        <p style="background-color: #0065A3; color: #fff;">
                            {{ Auth::user()->name }} - {{ Auth::user()->user_type }}
                            <small>Member since {{ date_format(date_create(Auth::user()->created_at), 'jS M Y')}}</small>
                        </p>
                    </li>
                    <!-- Menu Body
                    <li class="user-body">
                      <div class="row">
                        <div class="col-4 text-center">
                          <a href="#">Followers</a>
                        </div>
                        <div class="col-4 text-center">
                          <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                          <a href="#">Friends</a>
                        </div>
                      </div>-->
                    <!-- /.row
                  </li>-->
                    <!-- Menu Footer-->
                    <li class="user-footer">

                        <form action="{{ route('logout') }}" method="POST" >
                            @csrf
                            @if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Administrator")
                                <a href="{{ route('accounts.edit', Auth::user()->id)}}" class="btn btn-default btn-flat" style="background-color: #0065A3; color: #fff;">
                                    Profile</a>
                        @endif
                        <!-- --><button type="submit" class="btn btn-default btn-flat float-right" style="background-color: #0065A3; color: #fff;">
                                {{ __('Logout') }}</button>

                        </form>

                    </li>
                </ul>
            </li>

        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="{{ asset('images/TableSeaNew.jpg')}}" width="100%" alt="Table Sea Logo"
                 class="img-responsive" style="opacity: .8; padding: 0; height: auto; max-height: 150px; object-fit: contain;">
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional)
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">

                </div>
                <div class="info" style="color: #0065A3;">
                    <a href="" class="d-block" style="color: #0065A3; text-decoration: none;">
                        </a>
                </div>
            </div>-->

            <!-- SidebarSearch Form
            <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                  </button>
                </div>
              </div>
            </div>-->

            <!-- Sidebar Menu -->
            <nav class="mt-0" style="width: 150px;">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    @if(Auth::user()->user_type == "Restaurant")
                        <li class="nav-item">
                            <a href="{{ url('/restaurant_dashboard', $restaurant_id)}}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt" style="color: #fff;"></i>
                                <p style="color: #fff;">
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            @if(request()->is('create_reservation*') || request()->is('sort_by_date'))
                                <a class="nav-link" href="{{ route('create_reservation', $restaurant_id)}}" style="background-color: #0065A3; color: #fff;">
                                    <i class="nav-icon fas fa-plus-circle" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Reserve Table
                                    </p>
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('create_reservation', $restaurant_id)}}">
                                    <i class="nav-icon fas fa-plus-circle" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Reserve Table
                                    </p>
                                </a>
                            @endif
                        </li>
                        <li class="nav-item">
                            @if(request()->is('table_reservations*'))
                                <a class="nav-link active" href="{{ route('table_reservations', $restaurant_id)}}" style="background-color: #0065A3; color: #fff;">
                                    <i class="nav-icon fas fa-weight" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        List
                                    </p>
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('table_reservations', $restaurant_id)}}">
                                    <i class="nav-icon fas fa-weight" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        List
                                    </p>
                                </a>
                            @endif
                        </li>
                        <li class="nav-item">
                            @if(request()->is('grid*'))
                                <a class="nav-link active" href="{{ route('grid', $restaurant_id)}}" style="background-color: #0065A3; color: #fff;">
                                    <i class="nav-icon fas fa-calendar-check" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Calendar
                                    </p>
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('grid', $restaurant_id)}}">
                                    <i class="nav-icon fas fa-calendar-check" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Calendar
                                    </p>
                                </a>
                            @endif

                        </li>
                        <li class="nav-item">
                            @if((request()->is('tables*')) || (request()->is('create_table*')))
                                <a class="nav-link active" href="{{ route('create_table', $restaurant_id)}}" style="background-color: #0065A3; color: #fff;">
                                    <i class="nav-icon fas fa-table" style="background-color: #0065A3; color: #fff;"></i>
                                    <p style="background-color: #0065A3; color: #fff;">
                                        Tables
                                    </p>
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('create_table', $restaurant_id)}}">
                                    <i class="nav-icon fas fa-table" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Tables
                                    </p>
                                </a>
                            @endif
                        </li>
                        <!-- --> <li class="nav-item">
                            @if(request()->is('guests*'))
                                <a class="nav-link active" href="{{ route('guests', $restaurant_id)}}" style="background-color: #0065A3; color: #fff;">
                                    <i class="nav-icon fas fa-user-friends" style="background-color: #0065A3; color: #fff;"></i>
                                    <p style="background-color: #0065A3; color: #fff;">
                                        Guests
                                    </p>
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('guests', $restaurant_id)}}">
                                    <i class="nav-icon fas fa-user-friends" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Guests
                                    </p>
                                </a>
                            @endif
                        </li>
                    @endif
                    @if(Auth::user()->user_type == "Administrator")
                        <li class="nav-item">
                            @if(request()->is('administrator*'))
                                <a href="{{ url('/administrator')}}" class="nav-link active" style="background-color: #0065A3; color: #fff;">
                                    <i class="nav-icon fas fa-cogs" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Settings
                                        <i class="right fas fa-angle-left" style="color: #fff;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{ url('/administrator')}}" style="background-color: #0065A3; color: #fff;">
                                            <i class="nav-icon fa fa-user-friends nav-icon" style="color: #fff;"></i>
                                            <p style="color: #fff;">Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/manage_restaurants')}}">
                                            <i class="nav-icon fa fa-tasks" style="color: #0065A3;"></i>
                                            <p style="color: #0065A3;">Manage Restaurants</p>
                                        </a>
                                    </li>
                                </ul>
                            @else
                                <a href="{{ url('/administrator')}}" class="nav-link active" style="background-color: #0065A3; color: #fff;">
                                    <i class="nav-icon fas fa-cogs" style="color: #fff;"></i>
                                    <p style="color: #fff;">
                                        Settings
                                        <i class="right fas fa-angle-left" style="color: #fff;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/administrator')}}" >
                                            <i class="nav-icon fa fa-user-friends" style="color: #0065A3;"></i>
                                            <p style="color: #0065A3;">Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{ url('/manage_restaurants')}}" style="background-color: #0065A3; color: #fff;">
                                            <i class="nav-icon fa fa-tasks nav-icon" style="color: #fff;"></i>
                                            <p style="color: #fff;">Manage Restaurants</p>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content">

            @yield('content')

        </section>

        @yield('scripts')

    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
      <footer class="main-footer">
          <strong>Copyright &copy; 2021 </strong>
          <div class="float-right d-none d-sm-inline-block">
              <b>Version</b> 2.0
          </div>
      </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- Sortable JS -->

<!-- jQuery -->
<script src="{{ asset('multiple_select/jquery.multiselect.js')}}"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<!-- Bootstrap -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<!-- AdminLTE -->
<script src="{{ asset('dist/js/adminlte.js')}}"></script>
<!-- InputMask-->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- OPTIONAL SCRIPTS-->
<script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Bootstrap Switch-->
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<!-- BS-Stepper
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>-->
<!-- jQuery UI -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- Tempusdominus Bootstrap 4-->

<!-- dropzonejs
<script src="{{ asset('plugins/dropzone/min/dropzone.min.js')}}"></script>-->
<!-- AdminLTE for demo purposes-->
<script src="{{ asset('dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes)-->
<!-- Page specific scripts -->
<script type="text/javascript">
    $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#dashboard_range').val(start.format('YYYY-MM-D') + '/' + end.format('YYYY-MM-D'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

    });
</script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<script>
    $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
</script>
</body>
</html>
