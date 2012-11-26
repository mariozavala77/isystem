@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-plus-3"></span>添加代理</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/agent" title="">代理管理</a></li>
                <li class="current"><a href="{{ URL::base() }}/agent/add" title="">添加代理</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->


    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/agent/insert" method="POST" class="main">
            <fieldset>
                <div class="widget fluid">
                    <div class="whead"><h6>添加代理</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>名称：</label></div>
                        <div class="grid9"><input type="text" name="company" style="width: 14.89361702%" required="required" autofocus="autofocus"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>电话：</label></div>
                        <div class="grid9"><input type="text" name="phone" style="width: 14.89361702%" required="required"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>邮箱:</label></div>
                        <div class="grid9"><input type="email" name="email" style="width: 20.89361702%" required="required"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>地址：</label></div>
                        <div class="grid9"><input type="text" name="address" style="width: 20.89361702%" required="required"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>通信密钥：</label></div>
                        <div class="grid9 glyph"><input type="text" name="secret" style="width: 30.89361702%;float: left;" id="secret" readonly="readonly"/><span class="icos-refresh4" style="cursor:pointer;" title="点击更新密钥" id="bulid_secret"></span></div>
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
<script type="text/javascript">
$(document).ready(function() {
    $('#bulid_secret').click(function(){
        $.get('/agent/secret',function(response){
            $('#secret').val(response.message);
        },'json');
    });
});
</script>
@endsection
