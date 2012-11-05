@layout('layout.base')
@section('script')
    {{ HTML::script('js/plugins/uploader/plupload.js') }}
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

    <!-- Main content begins -->
    <div class="wrapper">
        <div class="widget">
            <div class="whead"><h6>导入产品</h6><div class="clear"></div></div>
            <div class="inContainer">
                <div class="inTo">
                    <h5>操作步骤：</h5>
                    <span>批量上传产品图片</span>
                    <span>下载<a class="label label-info">产品导入模板</a>录入产品信息</span>
                    <span>检查录入内容是否正确</span>
                    <span>上传导入文件，产品导入完成</span>
                </div>
            </div>
            <!-- upload images begins -->
            <div class="m20 ml20 mr20">
                <h5>批量上传产品图片：</h5> 
                <div class="widget">
                    <div id="upload_images">你的浏览器不支持批量上传。</div> 
                </div>
            </div>
            <!-- upload images ends -->
            <!-- import products begins -->
            <div class="m20 ml20 mr20" id="upload_file">
                <h5>导入产品文件：</h5>
                <div class="fluid">
                    <div class="grid1 textR">
                        <label>导入文件:</label>
                    </div>  
                    <div class="grid3">
                        <a class="bDefault buttonM" id="pickfile">
                            <span class="icon-add"></span>
                            <span>选择文件</span>
                        </a>

                        <a class="bDefault buttonM" id="uploadfile">
                            <span class="icon-upload-2"></span>
                            <span>上传文件</span>
                        </a>
                        <div id="filelist"></div>
                    </div>
                </div>
            </div>
            <!-- import products ends -->
        </div>
    </div>
    <!-- Main content ends-->
    <script type="text/javascript">
        $(function() {


            /*
            $('#file').fileupload({
                dataType: 'json',
                add: function (e, data) {
                    data.submit();
                },
                done: function (e, data) {
                    $.jGrowl(data.result.message);
                }
            });
            */

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

            // 产品信息上传
            var uploader = new plupload.Uploader({
                runtimes : 'html5, html4',
                browse_button : 'pickfile',
                container : 'upload_file',
                max_file_size : '1mb',
                url : 'upload.php',
                filters : [
                    {title : "Excel文件", extensions : "xls"}
                ],
            });

            uploader.bind('Init', function(up, params) {
                $('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
            });

            $('#upload_file').click(function(e) {
                uploader.start();
                e.preventDefault();
            });

            uploader.init();

        });
    </script>
</div>
@endsection
