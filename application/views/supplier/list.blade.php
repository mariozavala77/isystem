@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
{{ HTML::script('js/supplier.js') }}
@endsection
@section('sidebar')
@include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->    
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>供货商管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::base()}}/supplier" title="">供货商管理</a></li>
            </ul>
        </div>
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">
        <div>
            <a href="{{ URL::base() }}/supplier/add" class="buttonM bDefault floatR"><span class="icon-home-5"></span><span>添加供货商</span></a>
            <div class="clear"></div>
        </div>

        <!--agent begins-->
        <div class="widget" style="margin-top:5px;">
            <div class="whead"><h6>供货商列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="supplier_list_table"></table>
            </div>
            <div class="clear"></div>
        </div>
        <!-- agent ends -->

        <!--delete confirm begins -->
        <div id='supplier_delete_confirm' style='display: none' title='提示'>
            <p>你确认删除此供货商？</p>
        </div>
    </div>
    <!-- Main content ends -->
</div>
<!-- Content ends -->    
@endsection
