@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/ui/jquery.fancybox.js') }}
{{ HTML::script('js/taskinfo.js') }}
@endsection
@section('sidebar')
@include('../block.sidebar')
@endsection
@section('content')
<script type="text/javascript">
var user_id = '{{$user_id}}';
var task_id = '{{$task->id}}';
var nowtime = '{{$nowtime}}';
var entity_id = '{{$task->entity_id}}';
var tasks_handle = 0;
var task_mod ='{{$task->type}}';
</script>
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>控制中心</span>
        <div class="clear"></div>
    </div>
    <div class="breadLine">
        <div class="bc">
            <ul class="breadcrumbs" id="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::to('task/info?task_id='.$task->id) }}">任务详细</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a title="" href="#"><i class="icos-list"></i><span>新订单</span> <strong>(+58)</strong></a></li>
                <li><a title="" href="#"><i class="icos-check"></i><span>新任务</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>快捷导航</span>
                        <span><img alt="" src="/images/elements/control/hasddArrow.png"></span>
                    </a>
                    <ul>
                        <li><a title="" href="#"><span class="icos-add"></span>New invoice</a></li>
                        <li><a title="" href="#"><span class="icos-archive"></span>History</a></li>
                        <li class="noBorderB"><a title="" href="#"><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    <div class="wrapper">
    <!--任务详细-->
    <div class="fluid">
    <div class="grid6">
        <div class="widget">
            <div class="whead">
                <h6>任务详细信息</h6>  
                <div class="clear"></div>
            </div>
            <div class="justTable">
                <table class="tDefault" width="100%"><tbody>
                    <tr>
                        <td>创建时间：</td>
                        <td>{{$task->created_at}}</td>
                        <td>创建者：</td>
                        <td>@if (empty($task->from_uid))
                                系统
                            @else
                                @foreach($users as $user)
                                    @if($user->id == $task->from_uid)
                                        {{$user->username}}
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>任务级别：</td>
                        <td>{{$task->level}}</td>
                    </tr>
                    <tr>
                        @if (empty($task->handle))
                            <td colspan="3">任务尚未解决
                                </td>
                        @else
                            <td>解决时间：</td><td colspan="2">{{$task->modified_at}}
                            </td>
                        @endif
                            <td>任务领取：</td>
                            <td colspan="2">@if (empty($task->to_uid))
                                    无人处理
                                @else
                                    @foreach($users as $user)
                                        @if($user->id == $task->to_uid)
                                            {{$user->username}}
                                        @endif
                                    @endforeach
                                @endif
                        </td>
                    </tr>
                    <tr><td colspan="6" align="center">任务详情</td></tr>
                    <tr><td colspan="6" align="center">{{$task->content}}</td></tr>
                    </tbody></table>
            </div>
        </div>
        <div class="divider"><span></span></div>
        <!--任务操作-->
        <ul class="middleNavA">
            <li><a title="创建新任务" href="javascript:void(0);" id="bulidtask" class="tipS"><img alt="创建新任务" src="/images/icons/color/order-149.png"><span>创建新任务</span></a></li>
            <li><a title="创建仓储以及添加库存" href="javascript:void(0);" class="tipS"><img alt="" src="/images/icons/color/issue.png"><span>创建仓储</span></a></li>
        </ul>
        <div class="divider"><span></span></div>
        <!--任务操作-->
        <div class="widget">
                <div class="whead">
                    <h6>任务备注</h6>
                    <div class="on_off">
                        <a href="javascript:void(0);" title="点击刷新任务备注"><span class="icon-reload-CW"></span></a>  
                    </div>
                    <div class="clear"></div>
                </div>

                <ul class="messagesTwo" id="msg_lists">
                    <li class="by_user">
                        <div class="messageArea" style="margin:0px">
                            <div class="infoRow">
                                <span class="name"><strong>system</strong> says:</span>
                                <span class="time">now</span>
                                <div class="clear"></div>
                            </div>
                            <img alt="lodding" style="float: left" src="/images/elements/loaders/1.gif">
                        </div>
                        <div class="clear"></div>
                    </li>
                </ul>
            </div>
            <div class="enterMessage">
                <input type="text" placeholder="Enter your message..." name="enterMessage" id="enterMessage">
                <div class="sendBtn">
                    <input type="submit" value="发送留言" class="buttonS bLightBlue" name="sendMessage" id="sendMessage">
                </div>
            </div>
    </div>
    <!--订单详细 或者 产品详细-->
        <div class="widget grid6">
            <div class="whead">
                        <h6>产品池商品信息</h6>
                        <div class="on_off">
                            <a href="javascript:void(0);" title="点击刷新商品信息"><span class="icon-reload-CW"></span></a>  
                        </div>
            <div class="clear"></div>
            </div>
            <div id="info" class="justTable">
            </div>
        </div>
    </div>
</div>
<!-- delete confirm begins-->
<div id="task_finish_confirm" style="display:none" title="提示">
<p><label>备注：</label>
<textarea rows="8" cols="" name="textarea" class="auto" placeholder="备注" id="message"></textarea></p>
</div>
<!--delete confirm ends-->
<div id="task_finish" style="display:none" title="提示">
    <p>你确认标记此任务已完成?</p>
</div>
@endsection