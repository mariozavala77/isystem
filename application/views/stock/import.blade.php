@layout('layout.base')
@section('script')
    {{ HTML::script('js/plugins/uploader/plupload.js') }}
    {{ HTML::script('js/plugins/uploader/lang_cn.js') }}
    {{ HTML::script('js/plugins/uploader/plupload.html4.js') }}
    {{ HTML::script('js/plugins/uploader/plupload.html5.js') }}
    {{ HTML::script('js/stock.js') }}
@endsection
@section('sidebar')
    @include('../block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-add"></span>导入库存</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('stock') }}">库存管理</a></li>
                <li class="current"><a href="{{ URL::to('stock/import') }}">导入库存</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->


    <!-- Main content begins -->
    <div class="wrapper">
        <div class="widget">
            <div class="whead"><h6>批量导入库存</h6><div class="clear"></div></div>
            <div class="m20 ml20 mr20">
                <!-- upload images ends -->
                <div style="margin-top: 20px">
                    <span><span class="label label-info mr10">1</span>下载导入模板 [ 可选 ]：</span>
                    <a href="{{ URL::to('uploads/demo/stock.xls') }}" target="_blank" class="bDefault buttonS">
                        <span class="icon-download"></span><span>库存模板</span>
                    </a> 
                </div>
                <div style="margin-top: 20px">
                    <span><span class="label label-info mr10">2</span>导入库存数据：</span>
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

            // 文件导入
            var uploader = new plupload.Uploader({
                runtimes : 'html5, html4',
                browse_button : 'pickfiles',
                container : 'upload_file',
                max_file_size : '1mb',
                url : '{{ URL::to('stock/do_import') }}',
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
