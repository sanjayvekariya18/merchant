@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Promotions
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>

    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Promotions</h1>
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
            Promotions
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <?php if(in_array('add', $permissions)) : ?>
                <a href='{!!url("hase_promotion")!!}/create' class='btn btn-primary btn-inline'>
                Create New Promotion</a>
            <?php endif; ?>
        </div>
        <div class="col-md-3">
            <?php if(session('merchantId') == 0){ ?>
                <select name="merchant_type_id" id="merchant_type_id" class="form-control select21" style="width:100%">
                    @foreach($merchant_parent_types as $merchant_parent_type)
                            <option value="{{$merchant_parent_type->merchant_type_id}}"
                                <?php 
                                if($merchant_parent_type->merchant_type_id == $merchantType){
                                    echo "selected";
                                }
                                ?>
                            >{{$merchant_parent_type->merchant_type_name}}</option>
                    @endforeach
                </select>
            <?php }?>
        </div>
    </div>
    <br>
    
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Promotions List
                    </h4>
                </div>
                <div class="panel-body">
                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                    <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="table-responsive">
                        <div class="searchContainer">
                            <form action='{!!url("hase_promotion")!!}/search' method="POST" role="search">
                               {{ csrf_field() }}
                                <div class="input-group col-sm-3" style="float: left;">
                                   <input type="text" class="form-control" name="search_promotion" placeholder="Search Promotion" value="{{$searchPromotion}}">
                                   <span class="input-group-btn">
                                       <button type="submit" class="btn btn-default">
                                           <span class="glyphicon glyphicon-search"></span>
                                       </button>
                                   </span>
                                </div>
                                <div style="float: left;margin-left: 2px;">
                                    @if($searchPromotion)
                                        <a href='{!!url("hase_promotion")!!}' class='btn btn-primary btn-inline'>   Clear
                                        </a>
                                    @endif
                                </div>
                                <br>
                                <div style="margin-top: 15px;clear: both;">
                                   @if(isset($details))
                                    <p> The Search results for your query <b> {{ $query }} </b> are </p>
                                   @endif
                                </div>
                            </form>
                        </div>
                        <br>
                        
                        <table class="table table-bordered" id="promotionTable1">
                            <thead>
                                <tr class="filters">
                                    @if(session('merchantId') == 0)
                                        <th class="hidden">Merchant Type ID</th>
                                    @endif
                                    <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                    <th>Id</th>
                                    <th>Promotion Image</th>
                                    <th>Promotion Image compact</th>
                                    @if(Session('role') < 4 )
                                        <th>Merchant Name</th>
                                    @endif
                                    <th>Location Name</th>
                                    <th>Offer Name</th>
                                    <th>Featured Priority</th>
                                    <th>Hottest Priority</th>
                                    <th>Offer Start</th>
                                    <th>Offer Expire</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hase_promotions as $hase_promotion) 
                                <tr>
                                    @if(session('merchantId') == 0)
                                        <td class="hidden">{!!$hase_promotion->root_id!!}</td>
                                    @endif
                                    <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                    <td>
                                        <?php if(in_array('manage', $permissions)) : ?>
                                            <a href = '{!!url("hase_promotion")!!}/{!!$hase_promotion->promotion_id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Promotion"></i></a>
                                        <?php endif; ?>  
                                        @if($hase_promotion->status == 1)
                                            <?php if(in_array('delete', $permissions)) : ?>
                                                <a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("hase_promotion")!!}/{!!$hase_promotion->promotion_id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Promotion"></i></a>
                                            <?php endif; ?>
                                        @endif
                                    </td>
                                    <?php endif; ?> 
                                    <td>{!!$hase_promotion->promotion_id!!}</td>
                                    <td>
                                        <?php
                                        $promotionLiveImageUrl = parse_url($hase_promotion->image_url);
                                        ?>
                                        @if(isset($promotionLiveImageUrl['scheme']))
                                            @if($promotionLiveImageUrl['scheme'] == 'https' || $promotionLiveImageUrl['scheme'] == 'http')
                                               <img src="{!!$hase_promotion->image_url!!}" style="width: 80px; height: 40px;"/>
                                            @endif
                                        @else
                                            @if($hase_promotion->image_url != "" && file_exists(public_path(env('image_dir_path').$hase_promotion->image_url)))
                                                <img src="{{asset(env('image_dir_path').$hase_promotion->image_url)}}" style="width: 80px; height: 40px;"/>
                                            @else
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <?php
                                        $promotionLiveImageCompactUrl = parse_url($hase_promotion->image_url_compact);
                                        ?>
                                        @if(isset($promotionLiveImageCompactUrl['scheme']))
                                            @if($promotionLiveImageCompactUrl['scheme'] == 'https' || $promotionLiveImageCompactUrl['scheme'] == 'http')
                                               <img src="{!!$hase_promotion->image_url_compact!!}" style="width: 80px; height: 40px;"/>
                                            @endif
                                        @else
                                            @if($hase_promotion->image_url_compact != "" && file_exists(public_path(env('image_dir_path').$hase_promotion->image_url_compact)))
                                                <img src="{{asset(env('image_dir_path').$hase_promotion->image_url_compact)}}" style="width: 80px; height: 40px;"/>
                                            @else
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                            @endif
                                        @endif
                                    </td>
                                    @if(Session('role') < 4 )
                                        <td>{!!$hase_promotion->merchant_name!!}</td>
                                    @endif
                                    <td>{!!$hase_promotion->location_name!!}</td>
                                    <td>{!!$hase_promotion->offer_details!!}</td>
                                    <td>{!!$hase_promotion->offer_featured!!}</td>
                                    <td>{!!$hase_promotion->offer_hottest!!}</td>
                                    <td><?php echo PermissionTrait::convertIntoDate($hase_promotion->offer_begin); ?></td>
                                    <td><?php echo PermissionTrait::convertIntoDate($hase_promotion->offer_expire); ?></td>
                                    <td>
                                        @if($hase_promotion->status == 1)
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Promotion</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Promotion?
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
        {{ $hase_promotions->links() }}
    </section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HasePromotionIndex.js')}}"></script>

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