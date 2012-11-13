@layout('layout.base')
@section('script')
{{ HTML::script('js/product.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>产品管理</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::base() }}/product" title="">产品管理</a></li>
            </ul>
        </div>
        
        @include('block.bread');

    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">

        <div style="margin-top: 35px">
            <a href="{{ URL::to('product/import') }}" class="buttonM bDefault floatR ml10"><span class="icon-home-5"></span><span>导入产品</span></a>
            <a href="{{ URL::to('product/add') }}" class="buttonM bDefault floatR ml10"><span class="icon-home-5"></span><span>添加产品</span></a>
            <div class="clear"></div>
        </div>
        <!-- products list begins -->
        <div class="widget">
            <div class="whead"><h6>产品列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable checkAll tMedia" id="product_list_table" width="100%"></table>
            </div>
        </div>
        <!-- products list ends -->
    </div>
    <!-- Main content ends -->

    <div id="product_delete_confirm">
        <p>您确认删除此产品，点击确定将会对此产品包括其他语言版本一并删除。</p>
    </div>
</div>
<!-- Content ends -->    
@endsection

