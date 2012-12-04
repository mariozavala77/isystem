@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/ui/jquery.fancybox.js') }}
{{ HTML::script('js/taskinfo.js') }}
{{ HTML::script('js/task_sku_mapping.js') }}
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
var task_mod ='product_sale';
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

        @include('block.bread')
        
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
                            <td colspan="3">任务尚未解决</td>
                        @else
                            <td>解决时间：</td><td colspan="2">{{$task->modified_at}}</td>
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
                    <tr><td align="center">任务详情：</td><td colspan="5" align="center">{{$task->content}}</td></tr>
                    </tbody></table>
            </div>
        </div>
        @if (empty($task->handle))
        <div class="divider"><span></span></div>
        <div id="sku_mapping" class="widget fluid">
            <div class="whead">
                <h6>任务操作</h6>  
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid2 textR"><label style="float: right;">产品搜索：</label></div>
                <div class="grid8 textR" style="position: relative;"><input type="text" id="search_product" title="填入你需要搜索的产品"/>
                    <div style="position: absolute; border: 1px solid #D7D7D7; padding: 5px; background: #fff; min-width:97%;max-height: 240px;overflow-x: hidden;overflow-y: auto; display:none; z-index: 1000" class="textL" id="search_product_reslut"></div>
                </div>
                <div class="grid2 textL"><input type="submit" value="产品绑定" class="buttonM bGreen formSubmit" id="mapping"></div>
                <div class="clear"></div>
            </div>
        </div>
        @endif
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
                        <h6>附加信息</h6>
                        <div class="on_off">
                            <a href="javascript:void(0);" title="点击刷新附加信息"><span class="icon-reload-CW"></span></a>  
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
<!--代理商详细-->
<div id="agent_info" style="display:none" title="代理商信息">
</div>
<div id="agent_info_examine" style="display:none" title="销售产品审核">
    <div class="formRow">
            <div class="grid12">
                <input type="radio" id="info_pass" name="info_status" value="1" checked="checked"/><label for="info_pass"  class="mr20">通过</label>
                <input type="radio" id="info_not_pass" name="info_status" value="2" /><label for="info_not_pass"  class="mr20">不通过</label>
            </div>
        <div class="clear"></div>
    </div>
    <div class="formRow" style="display:none" id="examine_content_form">
        <label>原因：</label>
        <textarea name="content" id="examine_content" class="validate[required]"></textarea>
    </div>    
</div>
<style>
#search_product_reslut .highlighted{
    background-color: #3875d7; color: #fff;
}
</style>
@endsection
