@layout('layout.base')
@section('script')
{{ HTML::script('js/product.js') }}
@endsection
@section('sidebar')
    @include('../block.sidebar');
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>添加产品</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="index.html">Dashboard</a></li>
                <li><a href="tables.html">Tables</a>
                    <ul>
                        <li><a href="tables.html" title="">Standard tables</a></li>
                        <li><a href="tables_control.html" title="">Tables with control</a></li>
                        <li><a href="tables_sortable.html" title="">Sortable and resizable</a></li>
                    </ul>
                </li>
                <li class="current"><a href="tables_dynamic.html" title="">Dynamic table</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>Orders</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>Tasks</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>Invoices</span>
                        <span><img src="images/elements/control/hasddArrow.png" alt=""></span>
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

        <form action="{{ URL::base() }}/product/insert" method="POST" class="main">
            <fieldset>
                <input type="hidden" name="language" value="en">
                <div class="widget fluid">
                    <div class="whead">
                        <h6>添加产品</h6>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">名称：</label></div>
                        <div class="grid5"><input type="text" name="name"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">SKU：</label></div>
                        <div class="grid2"><input type="text" name="sku"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">价格：</label></div>
                        <div class="grid1"><input type="text" name="min_price"/></div><div class="floatL" style="margin-left: 2.127659574%">~</div>
                        <div class="grid1"><input type="text" name="max_price"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">重量：</label></div>
                        <div class="grid2"><input type="text" name="weight"/></div>
                        <div class="grid1"><label style="float: right">尺寸：</label></div>
                        <div class="grid2"><input type="text" name="size"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><label style="float: right">图片：</label></div>
                        <div class="grid2"><a href="javascript:;">【 添加图片 】</a></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid1"><lable style="float: right">描述：</label></div>
                        <div class="grid11">
                            <div class ="widget nomargin">
                            <textarea id="editor" name="description" rows="" cols="16"></textarea>
                            </div>
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
