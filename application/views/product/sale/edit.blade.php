@layout('layout.base')
@section('script')
    {{ HTML::script('js/sale.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>编辑产品</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('product') }}" title="">产品管理</a></li>
                <li><a href="{{ URL::to('product/sale') }}" title="">销售列表</a></li>
                <li class="current"><a href="{{ URL::to('product/sale/edit/?id=') . $product->id }}" title="">编辑产品</a></li>
            </ul>
        </div>

        @include('block.bread')

    </div>
    <!-- Breadcrumbs line ends -->
    <!-- Main content bigins -->
    <div class="wrapper">

        <form action="{{ URL::to('product/sale/update') }}" method="POST" class="main">
            <fieldset>
                <input type="hidden" name="sale_id" value="{{$product_sale->sale_id}}">
                <div class="widget fluid">
                    <div class="whead">
                        <h6>编辑产品</h6>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">名称：</label><em class="req">*</em></div>
                        <div class="grid5"><input type="text" name="title" value="{{$product_sale->title}}"/></div>
                        <div class="grid5">{{$product->name}}</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">SKU：</label></div>
                        <div class="grid2">{{$product_sale->sku}}</div>
                        <div class="grid2">{{$product->sku}}</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">价格：</label><em class="req">*</em></div>
                        <div class="grid1"><input type="text" name="price" value="{{$product_sale->price}}"/></div>
                        <div class="grid2">{{$product->min_price}} ~ {{$product->max_price}}</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">关键词：</label></div>
                        <div class="grid11"><textarea name="keywords">{{$product_sale->keywords}}</textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">短描述：</label></div>
                        <div class="grid11"><textarea name="short_description">{{$product_sale->short_description}}</textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">详细描述：</label></div>
                        <div class="grid11">{{$product->description}}</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">详细描述：</label><em class="req">*</em></div>
                        <div class="grid11">
                            <div class ="widget nomargin">
                            <textarea id="editor" name="description" rows="" cols="16">{{$product_sale->description}}</textarea>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow textC">
                        <span><input type="submit" class="bBlue buttonM" value="保存" /></span>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- Main content ends -->

<script type="text/javascript">
</script>
</div>
@endsection
