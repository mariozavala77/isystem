@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @rander('block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/user/permission/update" method="POST" class="main">
            <fieldset>
                <input type="hidden" name='permission_id' value="{{ $permission->id }}" />
                <div class="widget fluid">
                    <div class="whead"><h6>编辑权限</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>权限操作:</label></div>
                        <div class="grid9"><input type="text" name="description" value="{{ $permission->description }}" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>权限标识:</label></div>
                        <div class="grid9"><input type="text" name="rule" value="{{ $permission->rule }}" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <span><input type="submit" class="bBlue buttonM" value="保存" /></span>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- Main content ends -->

</div>
@endsection
