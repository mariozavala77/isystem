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
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>新订单</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>新任务</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>快捷导航</span>
                        <span><img src="/images/elements/control/hasddArrow.png" alt=""></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li class="noBorderB"><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content bigins -->
    <div class="wrapper"> 
        <div style="margin-top: 35px">
            <a href="{{ URL::base() }}/user/permission/add" class="buttonM bDefault floatR"><span class="icon-home-5"></span><span>添加权限</span></a>
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
