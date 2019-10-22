@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')
{{-- Page title --}}
@section('title')
    {!! $labels[0] !!}
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<?php use Illuminate\Support\Facades\Request; ?>

<section class="content-header">
    <h1>{!! $labels[0] !!}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#">{!! $labels[1] !!}</a>
        </li>
        <li class="active">
            {!! $labels[0] !!}
        </li>
    </ol>
</section>
<section class="content">
    <?php if(in_array('add', $permissions)) : ?>
        <form class = 'col s3' method = 'get' action = '{!!url(Request::segment(1))!!}/create'>
            <button class = 'btn btn-primary' type = 'submit'>Create {!! $labels[2] !!}</button>
        </form>
    <?php endif; ?>
    <br>
    <br>
    <section class="content p-l-r-15">
            <div class="row">
                <div class="panel panel-primary ">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-fw fa-users"></i> {!! $labels[2] !!} List
                        </h4>
                    </div>
                    <div class="panel-body">
                        <input type='hidden' name='_token' value='{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <div class="table-responsive">
                            <div class="searchContainer">
                                <form action='{!!url(Request::segment(1))!!}/search' method="POST" role="search">
                                   {{ csrf_field() }}
                                    <div class="input-group col-sm-3">
                                       <input type="text" class="form-control" name="search_data" placeholder="Search {!! $labels[0] !!}">
                                       <span class="input-group-btn">
                                           <button type="submit" class="btn btn-default">
                                               <span class="glyphicon glyphicon-search"></span>
                                           </button>
                                       </span>
                                    </div>
                                    <div style="float: left;margin-left: 2px;">
                                        @if($searchData)
                                            <a href='{!!url(Request::segment(1))!!}' class='btn btn-primary btn-inline'>   Clear
                                            </a>
                                        @endif
                                    </div>
                                    <div style="float: left; margin-top: 15px;">
                                       @if(isset($details))
                                        <p> The Search results for your query <b> {{ $query }} </b> are </p>
                                       @endif
                                    </div>
                                </form>
                            </div>
                            <br>
                            <table class = "table table-bordered" id="table1" style = 'background:#fff'>
                                <thead>
                                    <tr class="filters">
                                        <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                        <th>Actions</th>
                                        <?php endif; ?>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category Name</th>
                                        <th>Original Price</th>
                                        
                                        @if(Session('role') <3)
                                            <th>Merchant</th>
                                        @endif
                                        <th>Location</th>
                                        <th>Offer Start Date</th>
                                        <th>Offer End Date</th>
                                        <th>Discount Price</th>
                                        <th>Special Image</th>
                                        <th>special Image Compact</th>
                                        <th>Image</th>
                                        <th>Image Compact</th>
                                        <th>Status</th>
                                        {{-- <th>Category</th>
                                        <th>Stock Qty</th> 
                                         --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hase_menus as $hase_menu) 
                                    <tr>
                                        <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                            <td>
                                                <?php if(in_array('manage', $permissions)) : ?>
                                                    <a href="{!!url(Request::segment(1))!!}/{!!$hase_menu->product_id!!}/edit "><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit {!! $labels[2] !!}"></i></a>
                                                <?php endif; ?>
                                                <?php if(in_array('delete', $permissions)) : ?>
                                                    <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url(Request::segment(1))!!}/{!!$hase_menu->product_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete {!! $labels[2] !!}"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        
                                            <td>{!!$hase_menu->product_id!!}</td>
                                            <td>{!!$hase_menu->product_name!!}</td>     
                                            <td>{!!$hase_menu->category_name!!}</td>
                                            <td>{!!$hase_menu->base_price!!}</td>
                                            @if(Session('role') <3)
                                                <td>{!!$hase_menu->merchant_name!!}</td>
                                            @endif
                                            <td>{!!$hase_menu->location_name!!}</td>
                                            <td>
                                            <?php if($hase_menu->special_begin_date!=0)
                                                echo PermissionTrait::convertIntoDate($hase_menu->special_begin_date);
                                            else
                                                echo ""; ?>
                                            </td>
                                            <td>
                                            <?php if($hase_menu->special_expire_date!=0)
                                                echo PermissionTrait::convertIntoDate($hase_menu->special_expire_date);
                                            else
                                                echo ""; ?>
                                            </td>
                                            <td>{!!$hase_menu->special_price!!}</td>
                                        
                                            <td>
                                                <?php
                                                $menusecialLiveImageUrl = parse_url($hase_menu->special_image);
                                                ?>
                                                @if(isset($menusecialLiveImageUrl['scheme']))
                                                    @if($menusecialLiveImageUrl['scheme'] == 'https' || $menusecialLiveImageUrl['scheme'] == 'http')
                                                       <img src="{!!$hase_menu->special_image!!}" style="width: 80px; height: 40px;"/>
                                                    @endif
                                                @else
                                                    @if($hase_menu->special_image != "" && file_exists(public_path(env('image_dir_path').$hase_menu->special_image)))
                                                        <img src="{{asset(env('image_dir_path').$hase_menu->special_image)}}" style="width: 80px; height: 40px;"/>
                                                    @else
                                                        <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <?php
                                                $menusecialLiveImageCompactUrl = parse_url($hase_menu->special_image_compact);
                                                ?>
                                                @if(isset($menusecialLiveImageCompactUrl['scheme']))
                                                    @if($menusecialLiveImageCompactUrl['scheme'] == 'https' || $menusecialLiveImageCompactUrl['scheme'] == 'http')
                                                       <img src="{!!$hase_menu->special_image_compact!!}" style="width: 80px; height: 40px;"/>
                                                    @endif
                                                @else
                                                    @if($hase_menu->special_image_compact != "" && file_exists(public_path(env('image_dir_path').$hase_menu->special_image_compact)))
                                                        <img src="{{asset(env('image_dir_path').$hase_menu->special_image_compact)}}" style="width: 80px; height: 40px;"/>
                                                    @else
                                                        <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                                    @endif
                                                @endif
                                            </td>
                                        <td>
                                            <?php
                                            $menuLiveImageUrl = parse_url($hase_menu->product_image);
                                            ?>
                                            @if(isset($menuLiveImageUrl['scheme']))
                                                @if($menuLiveImageUrl['scheme'] == 'https' || $menuLiveImageUrl['scheme'] == 'http')
                                                   <img src="{!!$hase_menu->product_image!!}" style="width: 80px; height: 40px;"/>
                                                @endif
                                            @else
                                                @if($hase_menu->product_image != "" && file_exists(public_path(env('image_dir_path').$hase_menu->product_image)))
                                                    <img src="{{asset(env('image_dir_path').$hase_menu->product_image)}}" style="width: 80px; height: 40px;"/>
                                                @else
                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <?php
                                            $menuLiveImageCompactUrl = parse_url($hase_menu->product_image_compact);
                                            ?>
                                            @if(isset($menuLiveImageCompactUrl['scheme']))
                                                @if($menuLiveImageCompactUrl['scheme'] == 'https' || $menuLiveImageCompactUrl['scheme'] == 'http')
                                                   <img src="{!!$hase_menu->product_image_compact!!}" style="width: 80px; height: 40px;"/>
                                                @endif
                                            @else
                                                @if($hase_menu->product_image_compact != "" && file_exists(public_path(env('image_dir_path').$hase_menu->product_image_compact) ))
                                                    <img src="{{asset(env('image_dir_path').$hase_menu->product_image_compact)}}" style="width: 80px; height: 40px;"/>
                                                @else
                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($hase_menu->menu_status == 1)
                                                <span class="btn-success btn-xs">Enable</span>
                                            @else
                                                <span class="btn-danger btn-xs">Disable</span>
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title custom_align" id="Heading">Delete {!! $labels[2] !!}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                    delete this {!! $labels[2] !!}?
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
             {{ $hase_menus->links() }}
             <!-- row-->  
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/users_custom.js')}}"></script>
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
@stop