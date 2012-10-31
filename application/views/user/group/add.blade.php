@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/user/group/insert" method="POST" class="main">
            <fieldset>
                <div class="widget fluid">
                    <div class="whead"><h6>添加用户组</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>组名称:</label></div>
                        <div class="grid9"><input type="text" name="name" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>权限:</label></div>
                        <div class="grid9 check">
                            @foreach($permissions as $permission)
                            <input type="checkbox" id="{{ $permission->rule }}" name="permisson[{{ $permission->rule }}]" value="1"/>
                            <label for="{{ $permission->rule }}"  class="mr20">{{ $permission->description }}</label>
                            @endforeach
                        </div>
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
