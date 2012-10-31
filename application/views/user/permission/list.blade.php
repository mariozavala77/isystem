@layout('layout.base')
@section('script')
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- COntent begins -->
<div id="content">
    {{ HTML::link('user/permission/add', '权限添加') }}
</div>
@endsection
