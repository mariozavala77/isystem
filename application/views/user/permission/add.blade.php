@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @include('../block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
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
