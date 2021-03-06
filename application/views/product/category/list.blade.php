@layout('layout.base')
@section('script')
{{ HTML::script('js/category.js') }}
@endsection
@section('sidebar')
@include('../block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-clipboard-2"></span>分类列表</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('product') }}">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/category') }}">分类管理1</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">
        <div class="mt15">
            <a href="{{ URL::to('product/category/add') }}" class="buttonM bDefault floatR ml10"><span class="icon-plus-2"></span><span>添加分类</span></a>
            <div class="clear"></div>
        </div>
        <div class="widget">
            <div class="whead"><h6>分类列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="category_list_table"></table>
            </div>
        </div>
    </div>
    <!-- Main content ends-->

    <!-- delete confirm begins-->
    <div id="category_delete_confirm" style="display:none" title="提示">
        <p>你确定删除此类别？</p>
    </div>
    <!-- delete confirm ends-->
    <!-- delete confirm begins-->
    <div id="category_add_confirm" style="display:none" title="新增分类">
        <div class="formRow fluid">
            <div class="grid3"><label>分类名称：</label></div>
            <div class="grid9"><input type="text" name="category" id="category" class="validate[required]" /></div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- delete confirm ends-->
</div>
@endsection
