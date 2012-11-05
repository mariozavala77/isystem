<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>FBN</title>
    <!--[if IE]> {{ HTML::style('css/ie.css') }} <![endif]-->
    {{ HTML::style('css/styles.css') }}
    {{ HTML::script('js/files/jquery.min.js') }}
    {{ HTML::script('js/plugins/forms/ui.spinner.js') }}
    {{ HTML::script('js/files/jquery-ui.min.js') }}
    {{ HTML::script('js/files/bootstrap.js') }}
    {{ HTML::script('js/plugins/ui/jquery.tipsy.js') }}
    {{ HTML::script('js/plugins/forms/jquery.uniform.js') }}
    {{ HTML::script('js/plugins/forms/jquery.mousewheel.js') }}
    {{ HTML::script('js/plugins/wizards/jquery.form.wizard.js') }}
    {{ HTML::script('js/plugins/wizards/jquery.validate.js') }}
    {{ HTML::script('js/plugins/wizards/jquery.form.js') }}
    {{ HTML::script('js/plugins/ui/jquery.jgrowl.js') }}
    {{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
    {{ HTML::script('js/plugins/forms/jquery.cleditor.js') }}
    {{ HTML::script('js/common.js') }}
    @yield('script')
</head>
<body>
    <!-- Top line begins -->
    <div id="top">
        <div class="wrapper">
            <a href="{{ URL::base() }}" title="FBN SYSTEM" class="logo"><img src="{{ URL::base() }}/images/logo.png" alt=""></a>

            <!-- Right top nav -->
            <div class="topNav">
              <ul class="userNav">
                @if( Sentry::check() )
                <li><a href="{{ URL::to('account/logout') }}" title="登出" class="logout"></a></li>
                @endif
              </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- Top line ends -->
    @yield('sidebar')
    @yield('content')
</body>
</html>
