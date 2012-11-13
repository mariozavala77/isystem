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
    @include('../block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>导入产品</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('product') }}">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/import') }}">导入产品</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>新订单</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>新任务</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>快捷导航</span>
                        <span><img src="/images/elements/control/hasddArrow.png" alt=""></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li class="noBorderB"><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    <!-- Breadcrumbs line ends -->


    <!-- Main content begins -->
    <div class="wrapper">
        <div class="widget">
            <div class="whead"><h6>批量导入产品</h6><div class="clear"></div></div>
            <div class="m20 ml20 mr20">
                <!-- upload images begins -->
                <div>
                    <span><span class="label label-info mr10">1</span>上传图片：</span> 
                    <div class="widget" style="margin-top: 10px">
                        <div id="upload_images">你的浏览器不支持批量上传。</div> 
                    </div>
                </div>
                <!-- upload images ends -->
                <div style="margin-top: 20px">
                    <span><span class="label label-info mr10">2</span>下载产品模板 [ 可选 ]：</span>
                    <a href="{{ URL::to('uploads/demo/products.xls') }}" target="_blank" class="bDefault buttonS">
                        <span class="icon-download"></span><span>产品模板</span>
                    </a> 
                </div>
                <div style="margin-top: 20px">
                    <span><span class="label label-info mr10">3</span>导入产品数据：</span>
                    <div class="m10" id="upload_file">
                        <a id="pickfiles" class="bDefault buttonS" href="javascript:void(0);">选择文件</a>
                        <a id="uploadfiles" class="bDefault buttonS" href="javascript:void(0);">导入文件</a>
                        <span id="filelist"></span>
                    </div>
                </div>
            </div>
            <!-- import products ends -->
        </div>
    </div>
    <!-- Main content ends-->

    <script type="text/javascript">
        $(function() {

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

            // 文件导入
            var uploader = new plupload.Uploader({
                runtimes : 'html5, html4',
                browse_button : 'pickfiles',
                container : 'upload_file',
                max_file_size : '1mb',
                url : '{{ URL::base() }}/product/do_import',
                filters : [
                    {title : "Excel文件", extensions : "xls"},
                ],
            });


            $('#uploadfiles').click(function(e) {
                uploader.start();
                e.preventDefault();
            });

            uploader.init();

            $('#pickfiles').click(function() {
                for(i in uploader.files) {
                    uploader.removeFile(uploader.files[i]);
                }

                $('#filelist').html('');
            });

            uploader.bind('FilesAdded', function(up, files) {
                $('#pickfiles').html('重新选择');
                $.each(files, function(i, file) {
                    $('#filelist').append('<span id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' + '</span>');
                });
            });

            uploader.bind('UploadProgress', function(up, file) {
                $('#' + file.id + " b").html(file.percent + "%");
            });

            uploader.bind('Error', function(up, err) {
                $('#pickfiles').html('重新选择');
                $('#filelist').append('<span class="redBack">' + err.message + '</span>');
            });

            uploader.bind('FileUploaded', function(up, file, info) {
                $('#' + file.id + " b").html("100%");
                var response = jQuery.parseJSON(info.response);
                if(response.status == 'success') {
                    $('#filelist').html('<span class="greenBack">导入成功</span>');
                } else {
                    $('#filelist').html('<span class="redBack">' + response.message + '</span>');
                }
            });

        });
    </script>
</div>
@endsection
