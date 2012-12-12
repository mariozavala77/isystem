@layout('layout.base')
@section('script')
    {{ HTML::script('js/task.js') }}
@endsection
@section('sidebar')
    @include('../block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-pin"></span>任务处理</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::to('task') }}">任务处理</a></li>
            </ul>
        </div>

        @include('block.bread')
        
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">
        <div class="mt15">
            <a href="javascript:void(0);" class="buttonM bDefault floatR" id="bulidtask"><span class="icon-plus-3"></span><span>新增任务</span></a>
            <div class="clear"></div>
        </div>        
        <div class="widget">
            <div class="whead"><h6>任务列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable tDefault" id="task_list_table"></table>
            </div>
        </div>
    </div>
    <!-- delete confirm begins-->
    <div id="task_finish_confirm" style="display:none" title="提示">
        <p>你确认标记此任务已完成?</p>
    </div>
    <!-- delete confirm ends-->
    <div id="task_finish" style="display:none" title="提示">
        <p><label>备注：</label>
           <textarea rows="8" cols="" name="textarea" class="auto" placeholder="备注" id="message"></textarea></p>
    </div>
</div>
<!-- 创建任务 begins -->
<div id="task_confirm" style="display:none" title="创建新任务">
    <div class="formRow">
        <label>任务类型：</label>
        <select name="type" id="task_type" >
            <option value="product">产品类</option>
            <option value="product_sale">代理商销售</option>
            <option value="order">订单类</option>
        </select>
    </div>
    <div class="formRow">
        <label>任务分派：</label>
        <select name="to_uid" id="task_to_uid">
            @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->username}}</option>
            @endforeach
        </select>
    </div>
    <div class="formRow">
        <div class="grid12">
            <div class="sliderSpecs">
                <label for="task_level" style="font-size:12px">紧急程度：</label><input type="text" id="task_level" />
                <div class="clear"></div>
            </div>
            <div class="uMin"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>实际id：</label>
        <input type="text" name="entity_id" id="task_entity_id" placeholder="订单ID或者产品ID" class="validate[required]">
    </div>
    <div class="formRow">
        <label>任务内容：</label>
        <textarea name="content" id="task_content" class="validate[required]"></textarea>
    </div>
    <div class="formRow">
            <div class="grid12">
                <input type="radio" id="task_channel" name="task_channel" value="1"/><label for="radio1"  class="mr20">任务</label>
                <input type="radio" id="task_channel_sun" name="task_channel" checked="checked" value="0" /><label for="radio2"  class="mr20">子任务</label>
            </div>
        <div class="clear"></div>
    </div>
</div>
<!-- 创建任务 ends -->
@endsection
