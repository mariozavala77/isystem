<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | The URL used to access your application without a trailing slash. The URL
    | does not have to be set. If it isn't, we'll try our best to guess the URL
    | of your application.
    |
    */

    'url' => 'http://manager.eimo.co/',

    /*
    |--------------------------------------------------------------------------
    | Asset URL
    |--------------------------------------------------------------------------
    |
    | The base URL used for your application's asset files. This is useful if
    | you are serving your assets through a different server or a CDN. If it
    | is not set, we'll default to the application URL above.
    |
    */

    'asset_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Application Index
    |--------------------------------------------------------------------------
    |
    | If you are including the "index.php" in your URLs, you can ignore this.
    | However, if you are using mod_rewrite to get cleaner URLs, just set
    | this option to an empty string and we'll take care of the rest.
    |
    */

    'index' => '',

    /*
    |--------------------------------------------------------------------------
    | Application Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the encryption and cookie classes to generate secure
    | encrypted strings and hashes. It is extremely important that this key
    | remains secret and it should not be shared with anyone. Make it about 32
    | characters of random gibberish.
    |
    */

    'key' => '5eWYxF5h0G4NgMd9AzwcqQ8OfRbRJNjm',

    /*
    |--------------------------------------------------------------------------
    | Profiler Toolbar
    |--------------------------------------------------------------------------
    |
    | Laravel includes a beautiful profiler toolbar that gives you a heads
    | up display of the queries and logs performed by your application.
    | This is wonderful for development, but, of course, you should
    | disable the toolbar for production applications.
    |
    */

    'profiler' => false,

    /*
    |--------------------------------------------------------------------------
    | Application Character Encoding
    |--------------------------------------------------------------------------
    |
    | The default character encoding used by your application. This encoding
    | will be used by the Str, Text, Form, and any other classes that need
    | to know what type of encoding to use for your awesome application.
    |
    */

    'encoding' => 'UTF-8',

    /*
    |--------------------------------------------------------------------------
    | Default Application Language
    |--------------------------------------------------------------------------
    |
    | The default language of your application. This language will be used by
    | Lang library as the default language when doing string localization.
    |
    */

    'language' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    |
    | These languages may also be supported by your application. If a request
    | enters your application with a URI beginning with one of these values
    | the default language will automatically be set to that language.
    |
    */

    'languages' => array(),

    /*
    |--------------------------------------------------------------------------
    | SSL Link Generation
    |--------------------------------------------------------------------------
    |
    | Many sites use SSL to protect their users' data. However, you may not be
    | able to use SSL on your development machine, meaning all HTTPS will be
    | broken during development.
    |
    | For this reason, you may wish to disable the generation of HTTPS links
    | throughout your application. This option does just that. All attempts
    | to generate HTTPS links will generate regular HTTP links instead.
    |
    */

    'ssl' => true,

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | The default timezone of your application. The timezone will be used when
    | Laravel needs a date, such as when writing to a log file or travelling
    | to a distant star at warp speed.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | Here, you can specify any class aliases that you would like registered
    | when Laravel loads. Aliases are lazy-loaded, so feel free to add!
    |
    | Aliases make it more convenient to use namespaced classes. Instead of
    | referring to the class using its full namespace, you may simply use
    | the alias defined here.
    |
    */

    'aliases' => array(
        'Auth'          => 'Laravel\\Auth',
        'Authenticator' => 'Laravel\\Auth\\Drivers\\Driver',
        'Asset'         => 'Laravel\\Asset',
        'Autoloader'    => 'Laravel\\Autoloader',
        'Blade'         => 'Laravel\\Blade',
        'Bundle'        => 'Laravel\\Bundle',
        'Cache'         => 'Laravel\\Cache',
        'Config'        => 'Laravel\\Config',
        'Controller'    => 'Laravel\\Routing\\Controller',
        'Cookie'        => 'Laravel\\Cookie',
        'Crypter'       => 'Laravel\\Crypter',
        'DB'            => 'Laravel\\Database',
        'Eloquent'      => 'Laravel\\Database\\Eloquent\\Model',
        'Event'         => 'Laravel\\Event',
        'File'          => 'Laravel\\File',
        'Filter'        => 'Laravel\\Routing\\Filter',
        'Form'          => 'Laravel\\Form',
        'Hash'          => 'Laravel\\Hash',
        'HTML'          => 'Laravel\\HTML',
        'Input'         => 'Laravel\\Input',
        'IoC'           => 'Laravel\\IoC',
        'Lang'          => 'Laravel\\Lang',
        'Log'           => 'Laravel\\Log',
        'Memcached'     => 'Laravel\\Memcached',
        'Paginator'     => 'Laravel\\Paginator',
        'Profiler'      => 'Laravel\\Profiling\\Profiler',
        'URL'           => 'Laravel\\URL',
        'Redirect'      => 'Laravel\\Redirect',
        'Redis'         => 'Laravel\\Redis',
        'Request'       => 'Laravel\\Request',
        'Response'      => 'Laravel\\Response',
        'Route'         => 'Laravel\\Routing\\Route',
        'Router'        => 'Laravel\\Routing\\Router',
        'Schema'        => 'Laravel\\Database\\Schema',
        'Section'       => 'Laravel\\Section',
        'Session'       => 'Laravel\\Session',
        'Str'           => 'Laravel\\Str',
        'Task'          => 'Laravel\\CLI\\Tasks\\Task',
        'URI'           => 'Laravel\\URI',
        'Validator'     => 'Laravel\\Validator',
        'View'          => 'Laravel\\View',
    ),

    // 产品语言支持
    'support_language' => ['cn' => '中', 'en' => '英'],

    // 订单状态制定
    'order_status' => [
        '1' => ['name' => '待付款', 'define' => 'ORDER_PENDING'],
        '2' => ['name' => '未发货', 'define' => 'ORDER_UNSHIPPED'],
        '3' => ['name' => '已发货', 'define' => 'ORDER_SHIPPED'],
        '4' => ['name' => '部分发货', 'define' => 'ORDER_PARTIALLYSHIPPED'],
        '5' => ['name' => '已取消', 'define' => 'ORDER_CANCELED'],
        '6' => ['name' => '无法处理', 'define' => 'UNFULFILLABLE'],
    ],

    // 物流状态制定
    'ship_status' => [
        '0'
    ],

    // 订单配送紧急程度
    'order_shipment_level' => [
        '<span class="red">十分紧急</span>',
        '<span class="orange">紧急</span>',
        '<span class="green">标准</span>',
        '<span>一般</span>',
    ],

    // 渠道分类
    'channel_type' => [
        'Amazon'   => ['en' => 'Amazon', 'cn' => '亚马逊', 'img' => 'Amazon.jpg'],
        'AmazonUK' => ['en' => 'Amazon', 'cn' => '亚马逊伦敦', 'img' => 'AmazonUK.jpg'],
        'Ebay'     => ['en' => 'Ebay', 'cn' => '易趣', 'img' => 'AmazonUK.jpg'],
        'Magento'  => ['en' => 'Magento', 'cn' => 'Magento电子商务', 'img' => 'AmazonUK.jpg'],
        'Agent'    => ['en' => 'Agent', 'cn' => '代理商', 'img' => 'AmazonUK.jpg'],
    ],

    // 物流快递
    'logistic_company' => ['ups' => 'UPS', 'dhl' => 'DHL', 'fedex' => '联邦快递'],
);
