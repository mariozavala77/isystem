@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
@include('../block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>产品详情</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/product" title="">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/info?product_id=' . $product_id) }}" title="">产品详情</a></li>
            </ul>
        </div>
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->
    <!-- Main content bigins -->
    <div class="wrapper">
        <div class="justTable" id="info"><table width="100%" class="tDefault"><tbody><tr><td>商品名称：</td><td colspan="5">刻有花鸟虫鱼的Iphone4s手机壳123</td></tr><tr><td>SKU：</td><td>SKU000-011</td><td>类别：</td><td>IPhone5手机壳</td><td>开发者：</td><td>test</td></tr><tr><td>供应商：</td><td colspan="4">深圳新沃德有限公司</td></tr><tr><td>成本价：</td><td>9.00</td><td>认领价：</td><td>0</td><td>售价范围：</td><td>10.01-12.50</td></tr><tr><td>关键词：</td><td colspan="3">壳,手机壳</td><td>重量：</td><td>3</td></tr><tr><td>短描述：</td><td colspan="3">这是一个销售第一的独一无二的手机壳</td><td>尺寸：</td><td>12*23*32</td></tr><tr><td align="center" colspan="6">商品图库</td></tr><tr><td align="center" colspan="6"><ul class="gallery nopadding"><li><a rel="group" class="lightbox" title="k00001_1.jpg" href="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"><img alt="k00001_1.jpg" src="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"></a></li><li><a rel="group" class="lightbox" title="k00001_1.jpg" href="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"><img alt="k00001_1.jpg" src="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"></a></li><li><a rel="group" class="lightbox" title="k00001_1.jpg" href="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"><img alt="k00001_1.jpg" src="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"></a></li><li><a rel="group" class="lightbox" title="k00001_1.jpg" href="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"><img alt="k00001_1.jpg" src="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"></a></li><li><a rel="group" class="lightbox" title="k00001_1.jpg" href="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"><img alt="k00001_1.jpg" src="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"></a></li><li><a rel="group" class="lightbox" title="k00001_1.jpg" href="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"><img alt="k00001_1.jpg" src="http://ufc.com/uploads/images/products/695/906/fbe/eebb9d35c5c3136a2d2ff75/k00001_1.jpg"></a></li></ul></td></tr><tr><td align="center" colspan="6">商品详情</td></tr><tr><td colspan="6">这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的 这是一个神奇的产品你懂的1231</td></tr></tbody></table></div>
    </div>
    <!-- Main content ends -->
</div>
@endsection
