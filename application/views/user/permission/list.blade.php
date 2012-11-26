@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
{{ HTML::script('js/permission.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2">权限设置</span></span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="index.html">控制中心</a></li>
                <li><a href="{{ URL::to('user') }}">用户管理</a></li>
                <li class="current"><a href="{{ URL::to('user/permission') }}">权限设置</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content bigins -->
    <div class="wrapper"> 
        <div class="mt15">
            <a href="{{ URL::base() }}/user/permission/add" class="buttonM bDefault floatR"><span class="icon-plus-3"></span><span>添加权限</span></a>
            <div class="clear"></div>
        </div>
        <!-- group begins -->
        <div class="widget">
            <div class="whead"><h6>权限列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="permission_list_table"></table>
            </div>
            <div class="clear"></div>
         </div>
         <!-- permission ends -->

         <!-- delete confirm begins-->
         <div id='permission_delete_confirm' style="display: none" title="提示">
            <p>你确定删除此权限？</p>
         </div>
         <!-- delete confirm ends -->
    </div>
    <!-- Main content ends-->
</div>
@endsection
