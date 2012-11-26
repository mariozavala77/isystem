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
        <span class="pageTitle"><span class="icon-user-2"></span>用户管理</span>
        <div class="clear"></div>
    </div>
    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('user') }}">用户管理</a></li>
                <li class="current"><a href="{{ URL::to('user/add') }}">添加用户</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/user/insert" method="POST" class="main">
            <fieldset>
                <div class="widget fluid">
                    <div class="whead"><h6>添加用户</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>帐号:</label></div>
                        <div class="grid9"><input type="text" name="username" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>密码:</label></div>
                        <div class="grid9"><input type="password" name="password" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>邮箱:</label></div>
                        <div class="grid9"><input type="text" name="email" style="width: 20.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>角色:</label></div>
                        <div class="grid9">
                        <select name="role" style="width: 14.89361702%">
                            <option value="">--请选择--</option>
                            @foreach($groups as $group)
                            <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
                            @endforeach
                        </select>
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
