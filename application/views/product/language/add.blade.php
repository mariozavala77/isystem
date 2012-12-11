@layout('layout.base')
@section('script')
    {{ HTML::script('js/product.js') }}
@endsection
@section('sidebar')
    @include('../block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>添加{{Config::get('application.support_language')[$language]}}文版本</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/product" title="">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/language/add?language='.$language.'&product_id='.$product->product_id) }}" title="">添加{{Config::get('application.support_language')[$language]}}文版本</a></li>
            </ul>
        </div>
        
        @include('../block.bread')

    </div>
    <!-- Breadcrumbs line ends -->
    <!-- Main content bigins -->
    <div class="wrapper">

        <form action="{{ URL::to('product/language/insert') }}" method="POST" class="main">
            <fieldset>
                <input type="hidden" name="language" value="{{$language}}">
                <input type="hidden" name="product_id" value="{{$product->product_id}}">
                <div class="widget fluid">
                    <div class="whead">
                        <h6>添加{{Config::get('application.support_language')[$language]}}文版本</h6>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">名称：</label></div>
                        <div class="grid5">{{$product->name}}</div>
                        <div class="clear"></div>
                        <div class="grid1" style="margin-left: 0"><label style="float: right">翻译：</label><em class="req">*</em></div>
                        <div class="grid5"><input type="text" name="name"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">关键词：</label></div>
                        <div class="grid5"><input type="text" name="keywords"/><label>用英文","分隔</label/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">简要描述：</label></div>
                        <div class="grid5"><textarea name="short_description"></textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><lable style="float: right">详细描述：</label></div>
                        <div class="grid11">
                            {{$product->description}}
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><lable style="float: right">翻译：</label><em class="req">*</em></div>
                        <div class="grid11">
                            <div class ="widget nomargin">
                            <textarea id="editor" name="description" rows="" cols="16"></textarea>
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
</div>
@endsection
