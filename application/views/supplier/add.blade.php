@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
@include('block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-plus-3"></span>添加供货商</span>
        <div class="clear"></div>
    </div>
    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/supplier" title="">供货管理</a></li>
                <li class="current"><a href="{{ URL::base() }}/supplier/add" title="">添加供货商</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->


    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/supplier/insert" method="POST" class="main">
            <fieldset>
                <div class="widget fluid">
                    <div class="whead"><h6>添加供货</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>名称：</label></div>
                        <div class="grid9"><input type="text" name="company" style="width: 20.89361702%" required="required" autofocus="autofocus"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>电话：</label></div>
                        <div class="grid9"><input type="text" name="phone" style="width: 14.89361702%" required="required"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>邮箱:</label></div>
                        <div class="grid9"><input type="email" name="email" style="width: 20.89361702%" required="required"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>地址：</label></div>
                        <div class="grid9"><input type="text" name="address" style="width: 40.89361702%" required="required"/></div>
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
