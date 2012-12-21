@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @include('../../block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>编辑类别</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('product') }}" title="">产品管理</a></li>
                <li><a href="{{ URL::to('product/category') }}" title="">分类管理</a></li>
                <li class="current"><a href="{{ URL::to('product/category/edit') }}/agent/edit" title="">编辑分类</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->


    <!-- Main content bigins -->
    <div class="wrapper">
        <form action="{{ URL::to('product/category/update') }}" method="POST" class="main">
            <fieldset>
                <input type="hidden" name='category_id' value="{{$category->id}}" />
                <div class="widget fluid">
                    <div class="whead"><h6>编辑分类</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>所在分类：</label></div>
                        <div class="grid9" id="category">
                            {{ Category::name($category->id) }}
                            <input type="hidden" name="parent_id" value="{{$category->parent_id}}">
                            <a id="category_edit" class="ml20">[ 修改 ]</a>
                        </div>
                            <script type="text/javascript">
                            $(function(){
                                $('#category_edit').click(function(){
                                    var edit = '<select name="parent_id" class="category"><option value="0">顶级分类</option>@foreach($categories as $c)<option value="{{$c->id}}">{{$c->name}}</option>@endforeach</select>';
                                    $('#category').html(edit).children().uniform();
                                });

                                // 选择分类
                                $('.category').live('change', function() {
                                    var select = $(this).parent();
                                    select.nextAll().remove();
                                    var category_id = $(this).val();
                                    if(category_id != '' && category_id != '0') {
                                        select.prev().find('select').attr('name','');
                                        $.ajax({
                                            url: '{{ URL::to('product/category/children')}}',
                                            data: {category_id: category_id},
                                            success: function( data) {
                                                if(data.length > 0) {
                                                    var new_select = '<select class="category"><option value>--请选择--</option>';
                                                    for(i in data) {
                                                        new_select += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                                                    }
                                                    new_select += '</select>';
                                                    select.after(new_select);
                                                    select.next().uniform().parent().css('margin-left', '5px');
                                                }
                                            }
                                        });
                                        select.find('select').attr('name', 'parent_id');
                                    }
                                });
                            });
                            </script>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>分类名：</label></div>
                        <div class="grid9"><input type="text" name="name" value="{{ $category->name }}" style="width: 14.89361702%"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>排序：</label></div>
                        <div class="grid9"><input type="text" name="sort" value="{{ $category->sort }}" style="width: 14.89361702%"/></div>
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
