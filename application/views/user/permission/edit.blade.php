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
        <span class="pageTitle"><span class="icon-pencil"></span>编辑权限</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('user') }}">用户管理</a></li>
                <li><a href="{{ URL::to('user/permission') }}">权限管理</a></li>
                <li class="current"><a href="{{ URL::to('user/permission/edit') }}">编辑权限</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/user/permission/update" method="POST" class="main">
            <fieldset>
                <input type="hidden" name='permission_id' value="{{ $permission->id }}" />
                <div class="widget fluid">
                    <div class="whead"><h6>编辑权限</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid2 textR"><span>权限操作:</span></div>
                        <div class="grid9"><input type="text" name="description" value="{{ $permission->description }}" style="width: 18.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid2 textR"><span>权限标识:</span></div>
                        <div class="grid9"><input type="text" name="rule" value="{{ $permission->rule }}" style="width: 18.89361702%"/></div>
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
