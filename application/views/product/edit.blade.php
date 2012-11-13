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
    @include('block.sidebar');
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
                <li><a href="{{ URL::base() }}/product" title="">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/edit/?product_id=4') }}" title="">编辑产品</a></li>
            </ul>
        </div>
        
        @include('block.bread');

    </div>
    <!-- Breadcrumbs line ends -->
    <!-- Main content bigins -->
    <div class="wrapper">

        <form action="{{ URL::to('product/update') }}" method="POST" class="main">
            <fieldset>
                <input type="hidden" name="language" value="cn">
                <input type="hidden" name="product_id" value="{{$product->product_id}}">
                <div class="widget fluid">
                    <div class="whead">
                        <h6>编辑产品</h6>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">名称：</label><em class="req">*</em></div>
                        <div class="grid5"><input type="text" name="name" value="{{$product->name}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">SKU：</label><em class="req">*</em></div>
                        <div class="grid2"><input type="text" name="sku" value="{{$product->sku}}"/></div>
                        <div class="grid1"><label style="float: right">重量：</label></div>
                        <div class="grid2"><input type="text" name="weight" value="{{$product->weight}}"/></div>
                        <div class="grid1"><label style="float: right">尺寸：</label></div>
                        <div class="grid2"><input type="text" name="size" value="{{$product->size}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">成本价：</label><em class="req">*</em></div>
                        <div class="grid1"><input type="text" name="cost" value="{{$product->cost}}"/></div>
                        <div class="grid1"><label style="float: right">认领价：</label><em class="req">*</em></div>
                        <div class="grid1"><input type="text" name="price" value="{{$product->price}}"/></div>
                        <div class="grid1"><label style="float: right">范围：</label><em class="req">*</em></div>
                        <div class="grid1"><input type="text" name="min_price" value="{{$product->min_price}}"/></div><div class="floatL" style="margin-left: 2.127659574%">~</div>
                        <div class="grid1"><input type="text" name="max_price" value="{{$product->max_price}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">供应商：</label><em class="req">*</em></div>
                        <div class="grid2">
                            <select name="supplier_id">
                                <option value>--请选择--</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}" @if($supplier->id == $product->supplier_id)selected="selected"@endif>{{$supplier->company}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid1"><label style="float: right">开发者：</label><em class="req">*</em></div>
                        <div class="grid1">
                            <select name="devel_id">
                                <option value>--请选择--</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}" @if($user->id == $product->devel_id)selected="selected"@endif>{{$user->username}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid1"><label style="float: right">分类：</label><em class="req">*</em></div>
                        <div class="grid5">
                            <div id="category">
                                {{ Category::name($product->category_id) }} 
                                <input type="hidden" name="category_id" value="{{$product->category_id}}"> 
                                <a class="ml10" id="category_edit">[ 修改 ]</a>
                            </div>
                            <script type="text/javascript">
                            $(function(){
                                $('#category_edit').click(function(){
                                    var edit = '<select name="category_id" class="category"><option value>--请选择--</option>@foreach($categories as $category)<option value="{{$category->id}}">{{$category->name}}</option>@endforeach</select>';
                                    $('#category').html(edit).children().uniform();
                                });
                            });
                            </script>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">产品图片：</label></div>
                        <div class="grid11">
                            <div class="widget" id="upload_images" style="margin-top: 0px"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="grid1">&nbsp;</div>
                        <div class="grid10 nomargin">
                            <div class="gallery nopadding">
                                <ul id="images" class="textL">
                                    @foreach($product->images as $image)
                                    <li style="height: 60px">
                                        <img src="{{ UploadHelper::path('/uploads/images/products/', $image->image, true) }}" style="width: 60px; height: 60px"/>
                                        <input name="images[]" value="{{$image->image}}" type="hidden">
                                        <div class="actions" style="display: none; height: 60px; width: 60px">
                                            <a class="remove" style="margin: 35px 34px"><img src="/images/icons/delete.png" /></a>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><lable style="float: right">详细描述：</label><em class="req">*</em></div>
                        <div class="grid11">
                            <div class ="widget nomargin">
                            <textarea id="editor" name="description" rows="" cols="16">{{$product->description}}</textarea>
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
            console.log(files);
            console.log(info);
            $('#images').append('<li style="height: 60px">' +
                                '   <img src="{{ URL::to('/') }}'+ response.result+'" style="width: 60px; height: 60px">' +
                                '   <input name="images[]" value="'+ files.name+'" type="hidden">' +
                                '</li>');
        });

        // 选择分类
        $('.category').live('change', function() {
            var select = $(this).parent();
            select.nextAll().remove();
            var category_id = $(this).val();
            if(category_id != '') {
                $.ajax({
                    url: '{{ URL::to('product/category/children')}}',
                    data: {category_id: category_id},
                    success: function( data) {
                        if(data.length > 0) {
                            var new_select = '<select name="category_id" class="category"><option value>--请选择--</option>';
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
</script>
</div>
@endsection
