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
        <span class="pageTitle"><span class="icon-user-2"></span>编辑代理</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/agent" title="">代理管理</a></li>
                <li class="current"><a href="{{ URL::base() }}/agent/edit" title="">编辑代理</a></li>
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


    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/agent/update" method="POST" class="main">
            <fieldset>
                <input type="hidden" name='agent_id' value="{{$agent->id}}" />
                <div class="widget fluid">
                    <div class="whead"><h6>编辑代理</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>名称：</label></div>
                        <div class="grid9"><input type="text" name="company" value="{{ $agent->company }}" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>电话：</label></div>
                        <div class="grid9"><input type="text" name="phone" value="{{ $agent->phone }}" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Email：</label></div>
                        <div class="grid9"><input type="text" name="email" value="{{ $agent->email }}" style="width: 20.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>地址：</label></div>
                        <div class="grid9"><input type="text" name="address" value="{{ $agent->address }}" style="width: 20.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>通信密钥：</label></div>
                        <div class="grid9 glyph"><input type="text" name="secret" style="width: 30.89361702%;float: left;" id="secret" readonly="readonly" value="{{ $agent->secret }}"/><span class="icos-refresh4" style="cursor:pointer;" title="点击更新密钥" id="bulid_secret"></span></div>
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
