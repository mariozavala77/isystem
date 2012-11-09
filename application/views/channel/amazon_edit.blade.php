@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
@include('block.sidebar');
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>编辑{{ $channel_type[$channel->type]['cn'] }}渠道</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/channel" title="渠道管理">渠道管理</a></li>
                <li class="current"><a href="{{ URL::base() }}/channel/edit?channel_id={{ $channel->id }}" title="编辑{{ $channel_type[$channel->type]['cn'] }}渠道">编辑{{ $channel_type[$channel->type]['cn'] }}渠道-{{ $channel->alias }}</a></li>
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
        <form action="{{ URL::base() }}/channel/update" method="POST" class="main">
            <fieldset>
                <div class="widget fluid">
                    <div class="whead"><h6>编辑{{ $channel_type[$channel->type]['cn'] }}渠道</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>渠道名称：</label></div>
                        <div class="grid9"><input type="text" name="name" style="width: 50%" required="true" value="{{$channel->name}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>渠道别名：</label></div>
                        <div class="grid9"><input type="text" name="alias" style="width: 50%" required="true" value="{{$channel->alias}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>卖家编号：</label></div>
                        <div class="grid9"><input type="text" name="accredit['SellerId']" style="width: 50%" required="true" value="{{$channel->accredit['SellerId']}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>AWS 访问密钥编号：</label></div>
                        <div class="grid9"><input type="text" name="accredit['AWSAccessKeyId']" style="width: 50%" required="true" value="{{$channel->accredit['AWSAccessKeyId']}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>密钥：</label></div>
                        <div class="grid9"><input type="text" name="accredit['Key']" style="width: 50%" required="true" value="{{$channel->accredit['Key']}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>商城编号：</label></div>
                        <div class="grid9"><input type="text" name="accredit['MarketplaceId.Id.1']" style="width: 50%" required="true" value="{{$channel->accredit['MarketplaceId.Id.1']}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>服务请求地址：</label></div>
                        <div class="grid9"><input type="text" name="accredit['Server']" style="width: 50%" required="true" value="{{$channel->accredit['Server']}}"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>渠道描述：</label></div>
                        <div class="grid9"><textarea rows="8" cols="" name="description" class="auto lim">{{$channel->description}}</textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow textC">
                        <input type="hidden" name="channel_id" value="{{ $channel->id }}">
                        <span><input type="submit" class="bBlue buttonM" value="保存" /></span>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- Main content ends -->

</div>
@endsection
