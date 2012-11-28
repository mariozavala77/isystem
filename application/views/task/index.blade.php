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
@endsection
