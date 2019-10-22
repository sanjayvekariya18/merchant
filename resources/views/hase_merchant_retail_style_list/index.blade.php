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
    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
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
            <a href="#"> Merchants </a>
        </li>
        <li class="active">
            {!! $labels[0] !!}
        </li>
    </ol>
</section>
<section class="content">
    <form class = 'col s3' method = 'get' action = '{!!url(Request::segment(1))!!}/create'>
        <div class="row">
            <div class="col-md-2">
                <?php if(in_array('add', $permissions)) : ?>
                    <button class = 'btn btn-primary' type = 'submit'>Assign {!! $labels[0] !!}</button>
                <?php endif; ?>
            </div>
            <div class="col-md-3">
                <?php if(session('merchantId') == 0){ ?>
                    <select name="merchant_type_id" id="merchant_type_id" class="form-control select2" style="width:100%">
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
        
    </form>
    <input type='hidden' id='cuisine_crete_url' value='{!!url("hase_retail_style_type")!!}/create' />
    
    <input type='hidden' id='industry_crete_url' value='{!!url("hase_shop_retail_style_type")!!}/create' />

    <form style="float:right" class = 'col s3' method = 'get' action = '{!!url("hase_cuisine_types")!!}' id="style_create">
        <button id='style_create_button' class = 'btn btn-primary' type = 'submit'>Create New {!! $labels[1] !!}</button>
    </form>
    <br>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> {!! $labels[0] !!} List
                    </h4>
                </div>
                <div class="panel-body">
                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                    <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="table-responsive">
                        <div class="searchContainer">
                            <form action='{!!url("hase_style_list")!!}/search' method="POST" role="search">
                                {{ csrf_field() }}
                                <div class="input-group col-sm-3" style="float: left;">
                                    <input type="text" class="form-control" name="search_style" placeholder="Search Tag Style" value="{{$searchStyle}}">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default">
                                           <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </span>
                                </div>
                                <div style="float: left;margin-left: 2px;">
                                    @if($searchStyle)
                                        <a href='{!!url("hase_style_list")!!}' class='btn btn-primary btn-inline'>   Clear
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
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr class="filters">
                                    @if(session('merchantId') == 0)
                                        <th class="hidden">Merchant Type ID</th>
                                    @endif
                                    <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                        <th>actions</th>
                                    <?php endif; ?>
                                    <th>Id</th>
                                    <th>Merchant Name</th>
                                    <th>location Name</th>
                                    <th>{!! $labels[0] !!} Type Name</th>
                                    <!-- <th>Status</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hase_merchant_retail_style_lists as $hase_merchant_retail_style_list) 
                                <tr>
                                    @if(session('merchantId') == 0)
                                        <td class="hidden">{!!$hase_merchant_retail_style_list->merchant_type_id!!}</td>
                                    @endif
                                    <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                        <td>
                                            <?php if(in_array('manage', $permissions)) : ?>
                                                <a href="{!!url(Request::segment(1))!!}/{!!$hase_merchant_retail_style_list->style_list_id!!}/edit ">
                                                    <i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Style"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(in_array('delete', $permissions)) : ?>
                                                <!-- <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url(Request::segment(1))!!}/{!!$hase_merchant_retail_style_list->list_id!!}/delete">
                                                    <i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Category"></i>
                                                </a> -->
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td>{!!$hase_merchant_retail_style_list->style_list_id!!}</td>
                                    <td>{!!$hase_merchant_retail_style_list->merchant_name!!}</td>
                                    <td>{!!$hase_merchant_retail_style_list->location_name!!}</td>
                                    <td>{!!$hase_merchant_retail_style_list->style_name!!}</td>
                                    <!-- <td>
                                        @if($hase_merchant_retail_style_list->style_enable)
                                            <span class="btn-success btn-xs">Enable</span>
                                        @else
                                            <span class="btn-danger btn-xs">Disable</span>
                                        @endif
                                    </td> -->
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
                            <h4 class="modal-title custom_align" id="Heading">Delete {!! $labels[0] !!}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this {!! $labels[0] !!}?
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
        {{ $hase_merchant_retail_style_lists->links() }}
    </section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')

<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseStylesIndex.js')}}"></script>

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