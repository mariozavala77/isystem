@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
{{ HTML::script('js/agent.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->    
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-box-remove"></span>代理管理</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::base()}}/agent" title="">代理管理</a></li>
            </ul>
        </div>
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">
        <div class="mt15">
            <a href="{{ URL::base() }}/agent/add" class="buttonM bDefault floatR"><span class="icon-plus-3"></span><span>添加代理</span></a>
            <div class="clear"></div>
        </div>

        <!--agent begins-->
        <div class="widget">
            <div class="whead"><h6>代理列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="agent_list_table"></table>
            </div>
            <div class="clear"></div>
        </div>
        <!-- agent ends -->

        <!--delete confirm begins -->
        <div id='agent_delete_confirm' style='display: none' title='提示'>
            <p>你确认删除此代理？</p>
        </div>
    </div>
    <!-- Main content ends -->
</div>
<!-- Content ends -->    
@endsection