@extends('layouts.admin_header')

@section('content')


    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <h2 class="text-black">Edit Table Floor Plan </h2>
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
    <div class="col-md-9">

            <div class="card card-default" style="height: 600px;">
                <div class="card-header" style="background-color: #0065A3; color: #fff;">
                    @if(Auth::user()->user_type == "Administrator" || Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager")
                    <button id="saveReorder" class="btn btn-custom" style="background-color: #fff; color: #0065A3; float: right;">
                            <span><i class="fas fa-edit" style="color: #0065A3;"></i></span>
                            <span>Save Table Layout</span>
                    </button>
                    
                    <button class="btn btn-custom" style="background-color: #fff; color: #0065A3; float:left;"
                        data-toggle="modal" data-target="#modal-lg">
                        <span><i class="fas fa-plus" style="color: #0065A3;"></i></span>
                        <span>Add Table</span>
                    </button>
                    @endif
                    <!-- <button class="btn btn-custom" style="background-color: #fff; color: #0065A3; float:right;">
                        <a href="{{ route('create_table', $restaurant_id) }}" style="text-decoration: none; color: #0065A3;">
                            <span><i class="fas fa-arrow-left" style="color: #0065A3;"></i></span>
                            <span>Back</span>
                        </a>
                    </button>-->
                </div>
                <!-- /.card-header -->
                <div id="box" class="card-body" style="margin:0; padding:0; list-style-type:none; background-image:
                url('{{ asset('images/table-bg.png') }}')">
                    <section class="reorder_ul reorder-photos-list" style="margin: 0; padding: 0; list-style: none;">
                        <input type="hidden" style="display: none;" id="_token" value="{{ csrf_token() }}">
                        @foreach($tables as $table)
                            <div id="li_{{$table->id}}" class="ui-sortable-handle connectedSortable"
                                 style="padding: 0; float:left; margin:5px 3px;">
                                <a href="javascript:void(0);" style="float:none; color: #000;" class="image_link">
                                    <img src="{{ asset('table_shapes') }}/{{ $table->table_shape}}" width="30px" height="30px">
                                </a>
                                <p class="text-center" style="font-size: 12px;">{{$table->table_name}}</p>
                            </div>
                        @endforeach
                    </section>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

    </div>
            </div>
        </div>
    </div>
    
    <!-- Add Table Modal -->
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0065A3; color: #fff;">
                    <h4 class="modal-title">Add Table</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('tables.store') }}" enctype="multipart/form-data">
                        <!-- /.input group -->
                        @csrf

                        <div class="card-body">
                            <input type="hidden" name="restaurant_id" value="{{$restaurant_id}}">
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="exampleInputEmail1">Table Name</label>
                                    <input type="text" name="table_name" class="form-control" id="table_name" placeholder="Table Name" required>
                                </div>
                                @if($rest_type == 'Automatic')
                                <div class="col-6">
                                    <label for="exampleInputEmail1">Table Min Price</label>
                                    <input type="number" name="table_price" class="form-control" id="table_price" placeholder="Table Price" required>
                                </div>
                                @endif
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="exampleInputEmail1">Min Covers</label>
                                    <input type="number" name="min_covers" id="min_covers" class="form-control" value="1" placeholder="Min Covers" required>
                                </div>
                                <div class="col-6">
                                    <label for="exampleInputEmail1">Max Covers</label>
                                    <input type="number" name="max_covers" id="max_covers" class="form-control" placeholder="Max Covers" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Select Table Type</label>
                                <input type="text" readonly name="table_shape" value="" class="form-control" id="table_shape">
                                <div id="table_shapes" class="input-group row">
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="1 seat.png">
                                            <img src="{{ asset('table_shapes/1 seat.png') }}" width="50px" height="60px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="2 seats.png">
                                            <img src="{{ asset('table_shapes/2 seats.png') }}" width="50px" height="65px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="3 seats.png">
                                            <img src="{{ asset('table_shapes/3 seats.png') }}" width="70px" height="60px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="4 seats.png">
                                            <img src="{{ asset('table_shapes/4 seats.png') }}" width="70px" height="70px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="5 seats.png">
                                            <img src="{{ asset('table_shapes/5 seats.png') }}" width="65px" height="70px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="6 seats.png">
                                            <img src="{{ asset('table_shapes/6 seats.png') }}" width="65px" height="70px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="7 seats.png">
                                            <img src="{{ asset('table_shapes/7 seats.png') }}" width="75px" height="80px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="8 seats.png">
                                            <img src="{{ asset('table_shapes/8 seats.png') }}" width="90px" height="80px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="9 seats.png">
                                            <img src="{{ asset('table_shapes/9 seats.png') }}" width="100px" height="90px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="10 seats.png">
                                            <img src="{{ asset('table_shapes/10 seats.png') }}" width="100px" height="90px">
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" name="table_shape" class="btn btn-custom" value="large table.png">
                                            <img src="{{ asset('table_shapes/large table.png') }}" width="100px" height="90px">
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox"  name="table_status" class="form-check-input" id="exampleCheck1" required>
                                <label class="form-check-label" id="table_status_label" for="exampleCheck1">Table is Active</label>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        @if(Auth::user()->user_type == "Restaurant" || Auth::user()->user_type == "Manager")
                        <div class="card-footer text-center">
                            <button type="submit" id="save_button" class="btn btn-custom btn-block" style="background-color: #0065A3; color: #fff;">
                                Save</button>
                        </div>
                        @endif
                    </form>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('scripts')

<script type="application/javascript">

    $(document).ready(function(e){
        e.preventDefault;

        $("#table_shapes button").click(function() {
            var fired_button = $(this).val();

            $("#table_shape").val(fired_button);

        });
    });
    
    $(document).ready(function(e){
        e.preventDefault;

        $("section.reorder-photos-list").sortable({ tolerance: 'pointer' });
        $('.image_link').css("cursor","move");
        $('.ui-sortable-handle').draggable({
            containment: "#box"
        });
        var rest_id = "{{$restaurant_id}}";
        const get_positions_url = "{{ route('get_table_positions', $restaurant_id) }}";

        //console.log(get_positions_url);
        //console.log(rest_id);

        $.ajax({
            url: get_positions_url,
            type: 'GET',
            dataType: 'json', // added data type
            success: function(tables) {
                //console.log(tables);

                var count = 0;

                $("section.reorder-photos-list div").each( function() {
                    //var div_id = $(this).attr("id");

                    var positions = tables[count]['table_position'];
                    var posArr = positions.split(',');
                    var top = posArr[0];
                    var left = posArr[1];
                    var new_left = left.substring(1, left.length);

                    var height = $(this).height();
                    var width = $(this).width();
                    var t_top = top.substr(5);
                    var t_left = new_left.substr(6);

                    $(this).css({
                        position: 'absolute',
                        margin: '5px 3px',
                        top: t_top+ 'px',
                        left: t_left+'px',
                    });

                    count++;
                });

            }

        });

        return false;
    });

        $("#saveReorder").click(function( e ){

            e.preventDefault();

                $("section.reorder-photos-list").sortable('destroy');

                var order = [];
                var posit = [];
                var offsets = [];

                $("section.reorder-photos-list div").each(function() {
                    order.push($(this).attr('id').substring(3));
                    posit.push($(this).position());
                    offsets.push($(this).offset());
                });
                var positions = JSON.stringify(posit);
                var offset = JSON.stringify(offsets);
            //console.log(positions);
            //console.log(order);
            //console.log(offset);

            const _token = $("input#_token").val();
            const update_url = "{{ route('update_table_layout') }}";
            //console.log(update_url);

            $.ajax({
                    url: update_url,
                    method:"PATCH",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: " " + order + "",
                        positions: " " + positions + ""
                    },
                    success: function(response){
                        toastr.success('Table Layout Updated Successfully.');
                        //console.log(response);
                        window.location.reload();
                    }
                });
            return false;

        });
</script>

@endsection
