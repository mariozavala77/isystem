<!-- Sidebar begins -->
<div id="sidebar">
    <div class="mainNav">
        <!-- Main nav -->
        <ul class="nav">
            <li>
                <a href="{{ URL::base() }}" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/dashboard.png" alt="控制中心" /><span>控制中心</span></a>
            </li>
            <li class="sideBarDrop">
                <a href="javascript:void(0);" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/tables.png" alt="产品管理" /><span>产品管理</span></a>
                <ul class='leftUser'>
                    <li><a href="{{ URL::to('product') }}" class="sSettings" style="padding: 6px 5px 6px 34px">产品管理</a></li>
                    <li><a href="{{ URL::to('product/sale') }}" class="sSettings" style="padding: 6px 5px 6px 34px">销售管理</a></li>
                    <li><a href="{{ URL::to('product/category') }}" class="sSettings" style="padding: 6px 5px 6px 34px">分类管理</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ URL::base() }}/order" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/tables.png" alt="订单处理" /><span>订单处理</span></a>
            </li>
            <li>
                <a href="{{ URL::base() }}/task" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/tables.png" alt="任务管理" /><span>任务处理</span></a>
            </li>
            <li>
                <a href="{{ URL::base() }}/stock" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/tables.png" alt="库存信息" /><span>库存管理</span></a>
            </li>
            <li>
                <a href="{{ URL::base() }}/agent" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/ui.png" alt="代理管理" /><span>代理管理</span></a>
            </li>
            <li>
                <a href="{{ URL::base() }}/supplier" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/ui.png" alt="供货管理" /><span>供货管理</span></a>
            </li>
            <li class="sideBarDrop">
                <a href="javascript:void(0);" title="" class="active"><img src="{{ URL::base() }}/images/icons/mainnav/forms.png" alt="系统设置" /><span>系统设置</span></a>
                <ul class='leftUser'>
                    <li><a href="{{ URL::to('user') }}" class="sSettings" style="padding: 6px 5px 6px 34px">用户管理</a></li>
                    <li><a href="{{ URL::to('user/group') }}" class="sSettings" style="padding: 6px 5px 6px 34px">用户组设置</a></li>
                    <li><a href="{{ URL::to('user/permission') }}" class="sSettings" style="padding: 6px 5px 6px 34px">权限设置</a></li>
                    <li><a href="{{ URL::to('channel') }}" class="sSettings" style="padding: 6px 5px 6px 34px">渠道设置</a></li>
                    <li><a href="javasctipt:void(0);" class="sSettings" style="padding: 6px 5px 6px 34px">修改密码</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- Sidebar ends -->
