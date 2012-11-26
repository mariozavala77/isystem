@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
{{ HTML::script('js/channel.js') }}
@endsection
@section('sidebar')
@include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-new_window"></span>渠道管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::base()}}/channel" title="渠道管理">渠道管理</a></li>
            </ul>
        </div>

        @include('block.bread')
        
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content bigins -->
    <div class="wrapper">
        <div class="mt15">
            <a href="javascript:void(0);" class="buttonM bDefault floatR" id="channel_add_modal_open"><span class="icon-plus-3"></span><span>添加渠道</span></a>
            <div class="clear"></div>
        </div>

        <!--agent begins-->
        <div class="widget">
            <div class="whead"><h6>渠道列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="channel_list_table"></table>
            </div>
            <div class="clear"></div>
        </div>
        <!-- agent ends -->

        <!--delete confirm begins -->
        <div id='channel_delete_confirm' style='display: none' title='提示'>
            <p>你确认删除此渠道？</p>
        </div>
        <!--产品添加-->
        <div id="channel_add_modal" style='display: none' title='渠道类别'>
            <ul class="middleNavA" style="margin: 4px 0 0;">
                <li><a title="添加亚马逊美国渠道" href="{{ URL::base() }}/channel/add?type=Amazon"><img alt="Amazon" src="{{ URL::base() }}/images/channel/Amazon.jpg"><span></span></a></li>
                <li><a title="添加亚马逊英国渠道" href="{{ URL::base() }}/channel/add?type=AmazonUK"><img alt="Amazon.uk" src="{{ URL::base() }}/images/channel/AmazonUK.jpg"><span></span></a></li>
            </ul>
        </div>
    </div>
    <!-- Main content ends -->
</div>
<!-- Content ends -->    
@endsection
