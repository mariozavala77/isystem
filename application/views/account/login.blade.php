@layout('layout.base')
@section('script')
{{ HTML::script('js/login.js') }}
@endsection
@section('content')
<!-- Login wrapper begins -->
<div class="loginWrapper">
    {{ Form::open('', 'GET', ['id' => 'login']) }}
        <div class="relative">
            <input type="text" name="username" placeholder="帐号" class="username loginUsername" id="username">
        </div>
        <div class="relative">
            <input type="password" name="password" placeholder="密码" class="password loginPassword" id="password">
        </div>
        <div class="logControl">
            <div class="memory">
                <input name="remember" type="checkbox" value="1" class="check" id="remember" style="opacity: 0; ">
                <label for="remember">下次自动登录</label>
            </div>
            <input type="submit" name="submit" value="登录" class="buttonM bBlue">
            <div class="clear"></div>
        </div>
    {{ Form::close() }}
</div>
<!-- Login wrapper ends -->
@endsection
