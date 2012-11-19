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
        <div class="fluid">
            <div class="grid6 widget">
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
                                @if($task->to_uid == $user_id)
                                    <a class="buttonS bGreen tipS" href="javascript:void(0);" title="标记已经解决任务" id="handle">已近解决</a>
                                @endif
                                </td>
                        @else
                            <td>解决时间：</td><td colspan="2">{{$task->modified_at}}
                                @if($task->from_uid == $user_id and $task->handle == 1)
                                    <a class="buttonS bRed tipS" href="javascript:void(0);" title="问题尚未解决重新打开" id="open">重新打开</a>
                                @endif
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
            <div class="grid6">
                <ul class="middleNavA">
                    <li><a title="创建新任务" href="javascript:void(0);" id="bulidtask" class="tipS"><img alt="创建新任务" src="/images/icons/color/order-149.png"><span>创建新任务</span></a></li>
                    <li><a title="订单发货" href="javascript:void(0);" class="tipS"><img alt="" src="/images/icons/color/issue.png"><span>订单发货</span></a></li>
                    <li><a title="订单同步" href="javascript:void(0);" class="tipS"><img alt="" src="/images/icons/color/issue.png"><span>订单同步</span></a></li>
                </ul>
            </div>
        </div>
        <div class="divider"><span></span></div>
        <!-- order info dialog begins -->
    <div id="order_info_dialog">
        <div class="widget">
            <ul class="tbar tabs" style="border-bottom: 0px">
                <li><a href="#tab_info">详细信息</a></li>
                <li><a href="#tab_ship">物流信息</a></li>
            </ul>
            <div class="tab_container">
            <div id="tab_info" class="tab_content nopadding">
            <table cellpadding="0" cellspacing="0" border="0" class="dTable dataTable" >
                <tbody>
                  <tr>
                    <td>ID:</td>
                    <td field="entity_id"></td>
                    <td>金额:</td>
                    <td field="total_price"></td>
                    <td>配送紧急程度:</td>
                    <td colspan="3"field="shipment_level"></td>
                  </tr>
                  </tr>
                  <tr>
                    <td>订单状态:</td>
                    <td field="status"></td>
                    <td>处理状态:</td>
                    <td field="broken"></td>
                    <td>同步状态:</td>
                    <td field="is_sync"></td>
                    <td>是否是AFN订单:</td>
                    <td field="auto"></td>
                  </tr>
                  <tr>
                    <td>购买人:</td>
                    <td field="name"></td>
                    <td>购买时间:</td>
                    <td field="purchased_at"></td>
                    <td>购买渠道:</td>
                    <td field="channel"></td>
                    <td>支付方式:</td>
                    <td field="payment_method"></td>
                  </tr>
                  <tr>
                    <td>配送地址：</td>
                    <td colspan="7" field="shipping"></td>
                  </tr>
                  <tr>
                    <td>产品信息：</td>
                    <td colspan="7" field="products"></td>
                  </tr>
                  <tr>
                    <td>生成时间：</td>
                    <td field="created_at"></td>
                    <td>更新时间：</td>
                    <td field="updated_at"></td>
                    <td>修改时间：</td>
                    <td colspan="3" field="modified_at"></td>
                  </tr>
                </tbody>
            </table>
            </div>
            <div id="tab_ship" class="tab_content nopadding">
                <table>
                </table>
            </div>
            </div>
        </div>
    </div>
    <!-- order info dialog ends -->
    <div class="divider"><span></span></div>
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
<!-- delete confirm begins-->
<div id="task_finish_confirm" style="display:none" title="提示">
<p><label>备注：</label>
<textarea rows="8" cols="" name="textarea" class="auto" placeholder="备注" id="message"></textarea></p>
</div>
<!--delete confirm ends-->
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
<div id="task_finish" style="display:none" title="提示">
    <p>你确认标记此任务已完成?</p>
</div>
    <!-- order batch operation begins -->
    <div id="order_batch_ship_dialog" title="订单发货" style="display: none">
        <form action="{{ URL::to('order/ship') }}" method="POST">
            <div class="widget fluid" style="margin-top: 0px">
                <div class="formRow ">
                    <div class="grid3">&nbsp;</div>
                    <div class="grid1">配送公司：</div>
                    <div class="grid2"><input name="ship_company" type="text"/></div>
                    <div class="grid1">配送方式：</div>
                    <div class="grid2"><input name="ship_method" type="text"/></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="textC mt10"><input type="submit" class="buttonS bBlue" value="发货" /></div>
        </form>
    </div>
    <!-- order batch operation ends -->
@endsection