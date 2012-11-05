@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @include('../block.sidebar');
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
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
