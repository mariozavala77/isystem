@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @include('../block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-plus-3"></span>添加权限</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('user') }}">用户管理</a></li>
                <li><a href="{{ URL::to('user/permission') }}">权限管理</a></li>
                <li class="current"><a href="{{ URL::to('user/permission/add') }}">添加权限</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->


    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/user/permission/insert" method="POST" class="main">
            <fieldset>
                <div class="widget fluid">
                    <div class="whead"><h6>添加权限</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>操作:</label></div>
                        <div class="grid9">
                            <input type="text" name="description" style="width: 14.89361702%"/><span class="ml10 grey">添加订单</span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>标识:</label></div>
                        <div class="grid9">
                            <input type="text" name="rule" style="width: 14.89361702%"/><span class="ml10 grey">order_add</span>
                        </div>
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
@endsection
