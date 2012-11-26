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
        <span class="pageTitle"><span class="icon-box"></span>产品管理</span>
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
        
        @include('block.bread')

    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">

        <div class="mt15">
            <a href="{{ URL::to('product/import') }}" class="buttonM bDefault floatR ml10"><span class="icon-home-5"></span><span>导入产品</span></a>
            <a href="{{ URL::to('product/add') }}" class="buttonM bDefault floatR ml10"><span class="icon-home-5"></span><span>添加产品</span></a>
            <div class="clear"></div>
        </div>
        <!-- products list begins -->
        <div class="widget fluid" id="plist">
            <div class="whead"><h6>产品列表</h6><div class="clear"></div></div>
            <div class="hiddenpars">
                 <div class="cOptions">
                    <a href="javascript:void(0);" class="tOptions tipS doFullscreen" caction="plist" title="全屏"><img src="{{ URL::base() }}/images/icons/fullscreen"/></a>
                    <a href="javascript:void(0);" ckey="plist_search" class="tOptions tipS" title="搜索"><img src="{{ URL::base() }}/images/icons/search" alt=""/></a>
                    <a href="javascript:void(0);" ckey="plist_options" class="tOptions tipS" title="设置"><img src="{{ URL::base() }}/images/icons/options" alt=""/></a>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="dTable checkAll tMedia" id="product_list_table" width="100%"></table>
            </div>
        </div>
<script type="text/javascript">
$(function() {

    // 初始化搜索DIV
    $('a[ckey="plist_search"]').click(function(){
        if(!initsearch) initSearch();
    });

    // 订单搜索
    $('#product_search').live('click', function() {
        $('.product_search').each(function() {
            var value = $(this).val();
            var index = $(this).attr('index');
        
            if(value != '') {
                pTable.fnSetFilter(index, value);
            }
        });

        pTable.fnDraw();
    });

    // 重置搜索
    $('#product_search_reset').live('click', function() {
        pTable.fnFilterClear();
    });

    // 搜索分类处理
    $('.category').live('change', function() {
        var select = $(this).parent();
        $('.category').removeClass('product_search').removeAttr('index');
        $(this).addClass('product_search').attr('index', '4');
        select.nextAll().remove();
        var category_id = $(this).val();
        if(category_id != '') {
            $.ajax({
                url: '{{ URL::to('product/category/children')}}',
                data: {category_id: category_id},
                success: function( data) {
                    if(data.length > 0) {
                        var new_select = '<select class="category"><option value>--请选择--</option>';
                        for(i in data) {
                            new_select += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        }
                        new_select += '</select>';
                        select.after(new_select);
                        select.next().uniform().parent().css('margin-left', '5px');
                    }
                }
            });
        }
    });

});

function initSearch() {
    initsearch = true;

    var category = '<select class="category"><option value>--请选择--</option>@foreach($categories as $category)<option value="{{$category->id}}">{{$category->name}}</option>@endforeach</select>';
    $('#filter_product_category').html(category).children().uniform();
    $('#filter_product_sku').html('<input class="product_search" type="text" index="3"/>');
    $('#filter_product_name').html('<input class="product_search" type="text" index="2">');

}
</script>
        <!-- products list ends -->
    </div>
    <!-- Main content ends -->

    <div id="product_delete_confirm">
        <p>您确认删除此产品，点击确定将会对此产品包括其他语言版本一并删除。</p>
    </div>
</div>
<!-- Content ends -->    
@endsection

