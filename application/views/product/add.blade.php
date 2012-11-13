@layout('layout.base')
@section('script')
    {{ HTML::script('js/plugins/uploader/plupload.js') }}
    {{ HTML::script('js/plugins/uploader/lang_cn.js') }}
    {{ HTML::script('js/plugins/uploader/plupload.html4.js') }}
    {{ HTML::script('js/plugins/uploader/plupload.html5.js') }}
    {{ HTML::script('js/plugins/uploader/jquery.plupload.queue.js') }}
    {{ HTML::script('js/product.js') }}
@endsection
@section('sidebar')
    @include('../block.sidebar');
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>添加产品</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/product" title="">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/add') }}" title="">添加管理</a></li>
            </ul>
        </div>
        
        @include('block.bread');

    </div>
    <!-- Breadcrumbs line ends -->
    <!-- Main content bigins -->
    <div class="wrapper">

        <form action="{{ URL::base() }}/product/insert" method="POST" class="main">
            <fieldset>
                <input type="hidden" name="language" value="en">
                <div class="widget fluid">
                    <div class="whead">
                        <h6>添加产品</h6>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">名称：</label></div>
                        <div class="grid5"><input type="text" name="name"/></div>
                        <div class="grid1"><label style="float: right">分类：</label></div>
                        <div class="grid1">
                            <select name="category">
                                <option>-- 分类 -- </option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">关键词：</label></div>
                        <div class="grid5"><input type="text" name="keywords"/><label>用英文逗号","分隔</label></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">简要描述：</label></div>
                        <div class="grid5"><textarea name="short_description"></textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">SKU：</label></div>
                        <div class="grid2"><input type="text" name="sku"/></div>
                        <div class="grid1"><label style="float: right">重量：</label></div>
                        <div class="grid2"><input type="text" name="weight"/></div>
                        <div class="grid1"><label style="float: right">尺寸：</label></div>
                        <div class="grid2"><input type="text" name="size"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">成本价格：</label></div>
                        <div class="grid1"><input type="text" name="cost"/></div>
                        <div class="grid1"><label style="float: right">价格范围：</label></div>
                        <div class="grid1"><input type="text" name="min_price"/></div><div class="floatL" style="margin-left: 2.127659574%">~</div>
                        <div class="grid1"><input type="text" name="max_price"/></div>
                        <div class="grid1"><label style="float: right">供应商：</label></div>
                        <div class="grid1">
                            <select name="category">
                                <option>-- 分类 -- </option>
                            </select>
                        </div>
                        <div class="grid1"><label style="float: right">开发者：</label></div>
                        <div class="grid1">
                            <select name="category">
                                <option>-- 分类 -- </option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">产品图片：</label></div>
                        <div class="grid11">
                            <div class="widget" id="upload_images" style="margin-top: 10px"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="grid1"></div>
                        <div class="grid10 nomargin">
                            <div class="gallery nopadding">
                                <ul id="images" class="textL"></ul>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><lable style="float: right">详细描述：</label></div>
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

<script type="text/javascript">
    $(function(){
            // 批量上传图片
            $("#upload_images").pluploadQueue({
                runtimes : 'html5,html4',
                url : '{{ URL::base() }}/product/images',
                max_file_size : '1mb',
                unique_names : true,
                filters : [
                    {title : "JPG文件", extensions : "jpg"}
                ]
            });

            var uploader = $('#upload_images').pluploadQueue();

            uploader.bind('FileUploaded', function(up, files, info){
                var response = jQuery.parseJSON(info.response);
                console.log(response);
                $('#images').append('<li style="height: 60px"><img src="{{ URL::to('/') }}'+ response.result+'" style="width: 60px; height: 60px"></li>');
            });



        
    });
</script>

</div>
@endsection
