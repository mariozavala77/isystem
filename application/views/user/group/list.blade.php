@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
{{ HTML::script('js/group.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-users">用户组管理</span></span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="index.html">控制中心</a></li>
                <li><a href="{{ URL::to('user') }}">用户管理</a></li>
                <li class="current"><a href="{{ URL::to('user/group') }}">用户组管理</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content bigins -->
    <div class="wrapper"> 

        <div class="mt15">
            <a href="{{ URL::base() }}/user/group/add" class="buttonM bDefault floatR"><span class="icon-plus-3"></span><span>添加用户组</span></a>
            <div class="clear"></div>
        </div>
        <!-- group begins -->
        <div class="widget">
            <div class="whead"><h6>用户组列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="group_list_table"></table>
            </div>
            <div class="clear"></div>
        </div>
        <!-- group ends -->
    </div>
    <!-- Main content ends -->

    <!-- delete confirm dialog begins -->
    <div id="group_delete_confirm" style="display: none" title="提示">
        <p>你确认删除此用户组？</p>
    </div>
    <!-- delete confirm dialog ends -->
</div>
<!-- Content ends -->
@endsection
