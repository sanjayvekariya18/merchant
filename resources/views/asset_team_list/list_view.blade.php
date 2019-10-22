@extends('layouts/default')

{{-- Page title --}}
@section('title')
		Asset Team List
		@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
		<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{asset('assets/kendoui/styles/kendo.uniform.min.css')}}">
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">
<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
		<h1>Asset Team List</h1>
		<ol class="breadcrumb">
				<li>
						<a href="index ">
								<i class="fa fa-fw fa-home"></i> Dashboard
						</a>
				</li>
				<li class="active">
						Asset Team List
				</li>
		</ol>
</section>
<section class="content">
		<section class="content p-l-r-15">
			<div class="row">
				 <div class="panel panel-primary ">
						<div class="panel-heading">
							 <h4 class="panel-title">    
									<i class="fa fa-fw fa-users"></i> Asset Team List
							 </h4>
						</div>
						<div class="panel-body">							
							 <div id="assetTeamListGrid"></div>
						</div> 
						
				 </div>
			</div>
	 </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>

@section('footer_scripts')
<script id="assetGridSearch" type="text/x-kendo-template">
      <label class="search-label" for="searchBox">Search Grid:</label>
      <input type="search" id="assetSearchBox" class="k-textbox" style="width: 250px"/>
      <input type="button" id="assetBtnSearch" class="k-button" value="Search"/>
      <input type="button" id="assetBtnReset" class="k-button" value="Reset"/>
</script>
<script src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetTeamList.js')}}"></script>
<script type="text/javascript">
		assetTeamListData();
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