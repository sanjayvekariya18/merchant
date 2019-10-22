<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
<script src="assets/kendoui-treeview-categories/js/kendoui/tree/SohyperTree.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui-treeview-categories/js/kendoui/styles/kendo.sohyper.css">

<div class="demo-section" style="float: left;">
    <h3 class="title">Activity Tree View</h3>
        <div id="kendoSelectDiv">
            <select id="selectActivity"></select>
        </div>
        <div id="moduleActivity" class="demo-section"></div>
</div>
        <div id="result_treeviewActivity" style="float: left; display: none;" name="treeviewActivity" style="display: none;"></div><br/>
        <div id="activityResultText" style="display: none;"></div>
<div class="demo-section" style="float: left;">
    <h3 class="title">Regions Tree View</h3>
        <div id="kendoSelectDiv">
            <select id="selectRegion"></select>
        </div>
        <div id="moduleRegion" class="demo-section"></div>
</div>
        <div id="result_treeviewRegion" style="float: left; display: none;" name="treeviewRegion"></div><br/>
        <div id="regionResultText" style="display: none;"></div>
        <script type="text/javascript" src="{{asset('assets/js/custom_js/HaseImageActivityRegionTree.js')}}"></script>
<script type="text/javascript">
imageCategoryView();
</script>