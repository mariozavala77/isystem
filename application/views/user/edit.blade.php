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
        <form action="{{ URL::base() }}/user/group/update" method="POST" class="main">
            <fieldset>
                <input type="hidden" name='group_id' value="{{$group->id}}" />
                <div class="widget fluid">
                    <div class="whead"><h6>编辑用户组</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>组名称:</label></div>
                        <div class="grid9"><input type="text" name="name" value="{{ $group->name }}" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>权限:</label></div>
                        <div class="grid9 check">
                            @foreach($rules as $rule)
                            <input type="checkbox" id="{{ $rule->rule }}"  @if(in_array($rule->rule, $group->permissions))checked="checked"@endif name="permission[{{ $rule->rule }}]" value="1"/>
                            <label for="{{ $rule->rule }}"  class="mr20">{{ $rule->description }}</label>
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
