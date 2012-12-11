@layout('layout.base')
@section('script')
    {{ HTML::script('js/sale.js') }}
@endsection
@section('sidebar')
@include('block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-box"></span>销售列表</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('product') }}">产品管理</a></li>
                <li><a href="{{ URL::to('product/sale') }}">销售列表</a></li>
                <li class="current"><a>操作提示</a></li>
            </ul>
        </div>
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Tips begins -->
    <div id="action_tips" style="display: none">
        <p>{{$tips}}</p>
    </div>
    <!-- Tips ends -->

    <script type="text/javascript">
        $(function(){
            $('#action_tips').dialog({
                autoOpen: true,
                width: '400px',
                beforeclose: function() {
                    return false;
                },
                buttons: {
                    "返回": function() {
                        history.go(-1);
                    }
                }
            });

            $('#action_tips').dialog('open');

        });
    </script>
</div>
@endsection
