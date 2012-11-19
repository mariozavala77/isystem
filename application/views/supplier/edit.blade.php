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
        <span class="pageTitle"><span class="icon-user-2"></span>编辑代理</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::base() }}/supplier" title="">供货管理</a></li>
                <li class="current"><a href="{{ URL::base() }}/supplier/edit" title="">编辑供货</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>新订单</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>新任务</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>快捷导航</span>
                        <span><img src="/images/elements/control/hasddArrow.png" alt=""></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li class="noBorderB"><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    <!-- Breadcrumbs line ends -->


    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::base() }}/supplier/update" method="POST" class="main">
            <fieldset>
                <input type="hidden" name='supplier_id' value="{{$supplier->id}}" />
                <div class="widget fluid">
                    <div class="whead"><h6>编辑供货</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>名称：</label></div>
                        <div class="grid9"><input type="text" name="company" value="{{ $supplier->company }}" style="width: 20.89361702%" required="required" /></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>电话：</label></div>
                        <div class="grid9"><input type="text" name="phone" value="{{ $supplier->phone }}" style="width: 14.89361702%" required="required" /></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Email：</label></div>
                        <div class="grid9"><input type="email" name="email" value="{{ $supplier->email }}" style="width: 14.89361702%" required="required" /></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>地址：</label></div>
                        <div class="grid9"><input type="text" name="address" value="{{ $supplier->address }}" style="width: 40.89361702%" required="required" /></div>
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
