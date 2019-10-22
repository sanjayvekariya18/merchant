@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Hase Location
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Locations</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Merchants </a>
        </li>
        <li class="active">
            Locations
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
                <a href='{!!url("hase_location")!!}/create' class='btn btn-primary btn-inline'>Create New Location
            </a>
            <?php endif; ?>
            <?php if(session('merchantId') == 0): 
                if(session('merchantType') == 8) { ?>
                    <input id="restaurantToggle" type="checkbox" value="8" data-on-text="Restaurant" data-off-text="Shop" checked data-on-color="success" data-off-color="success" class="btn-inline">
                <?php } else { ?>
                    <input id="shopToggle" type="checkbox" value="2" data-off-text="Restaurant" data-on-text="Shop" checked data-on-color="success" data-off-color="success" class="btn-inline">
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-square-o"></i> Location List
                    </h4>
                </div>
                <div class="panel-body">
                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                    <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="table-responsive">
                        <div class="searchContainer">
                            <form action='{!!url("hase_location")!!}/search' method="POST" role="search">
                               {{ csrf_field() }}
                                <div class="input-group col-sm-3">
                                   <input type="text" class="form-control" name="search_location" placeholder="Search Location">
                                   <span class="input-group-btn">
                                       <button type="submit" class="btn btn-default">
                                           <span class="glyphicon glyphicon-search"></span>
                                       </button>
                                   </span>
                                </div>
                                <div style="float: left; margin-top: 15px;">
                                   @if(isset($details))
                                    <p> The Search results for your query <b> {{ $query }} </b> are </p>
                                   @endif
                                </div>
                            </form>
                        </div>
                        <br>
                        <table class="table table-bordered" id="locationTable1">
                            <thead>
                                <?php if(session('merchantId') == 0): ?>
                                <th class="hidden">Merchant Type ID</th>
                                <?php endif; ?>
                                <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                    <th>Actions</th>
                                <?php endif; ?>
                                <th>ID</th>
                                <th>Name</th>
                                <?php if(session('merchantId') == 0): ?>
                                <th>Merchant</th>
                                <?php endif; ?>
                                <th>Image</th>
                                <th>Neighborhood</th>
                                <th>Territories</th>
                                <th>Postcode</th>
                                <th>Telephone</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                <?php 
                                    /*echo "<pre>";
                                    print_r($hase_locations_data->toArray());
                                    die();*/
                                ?>
                                @foreach($hase_locations_data as $hase_location)
                                <tr>
                                    <?php if(session('merchantId') == 0): ?>
                                    <td class="hidden">{!!$hase_location->root_id!!}</td>
                                    <?php endif; ?>
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                        <td>
                                            <?php if(in_array("manage", $permissions)): ?>
                                                <a href = '{!!url("hase_location")!!}/{!!$hase_location->location_id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Location"></i></a>
                                            <?php endif; ?>
                                            <!-- <a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("hase_location")!!}/{!!$hase_location->location_id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Location"></i></a> -->
                                        </td>
                                    <?php endif; ?>
                                    <td>{!!$hase_location->location_id!!}</td>
                                    <td>{!!$hase_location->location_name!!}</td>
                                    <?php if(session('merchantId') == 0): ?>
                                        <td>{!!$hase_location->merchant_name!!}</td>
                                    <?php endif; ?>
                                    <td>
                                        <?php
                                        $locationLiveImageUrl = parse_url($hase_location->location_image);
                                        ?>
                                        @if(isset($locationLiveImageUrl['scheme']))
                                            @if($locationLiveImageUrl['scheme'] == 'https' || $locationLiveImageUrl['scheme'] == 'http')
                                               <img src="{!!$hase_location->location_image!!}" style="width: 80px; height: 40px;"/>
                                            @endif
                                        @else
                                            @if($hase_location->location_image != "" && is_file(env('image_dir_path').$hase_location->location_image) )
                                                <img src="{{asset(env('image_dir_path').$hase_location->location_image)}}" style="width: 80px; height: 40px;"/>
                                            @else
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{!!$hase_location->city_name!!}</td>
                                    <td>{!!$hase_location->state_name!!}</td>
                                    <td>{!!$hase_location->location_postcode!!}</td>
                                    <td>{!!$hase_location->location_telephone!!}</td>
                                    <td>
                                        @if ($hase_location->location_status == 1)
                                            Enabled
                                        @else
                                            Disabled
                                        @endif
                                    </td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- /.modal-dialog -->
            <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title custom_align" id="Heading">Delete Location</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                delete this Location ?
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <a href="#" class="btn btn-danger">
                                <span class="glyphicon glyphicon-ok-sign"></span> Yes
                            </a>
                            <button type="button" class="btn btn-success" data-dismiss="modal">
                                <span class="glyphicon glyphicon-remove"></span> No
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{ $hase_locations_data->links() }}
    </section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseLocation.js')}}"></script>
<script type="text/javascript">
    @if(Session::has('type'))
       toastr.options = {
    "closeButton": true,
    "positionClass": "toast-top-right",
    "showDuration": "1000",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "swing",
    "showMethod": "show"
    };
    var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
       @endif
</script>
<!-- end of page level js -->
@stop