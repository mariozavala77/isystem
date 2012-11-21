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

    'url' => '',

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

    // 爱查快递API所支持的快递列表
    'ickd_api' =>['aae' => 'AAE快递',
                  'anjie' => '安捷快递',
                  'anxinda' => '安信达快递',
                  'aramex' => 'Aramex国际快递',
                  'cces' => 'CCES快递',
                  'changtong' => '长通物流',
                  'chengguang' => '程光快递',
                  'chuanxi' => '传喜快递',
                  'chuanzhi' => '传志快递',
                  'citylink' => 'CityLinkExpress',
                  'coe' => '东方快递',
                  'cszx' => '城市之星',
                  'datian' => '大田物流',
                  'debang' => '德邦物流',
                  'dhl' => 'DHL快递',
                  'disifang' => '递四方速递',
                  'dpex' => 'DPEX快递',
                  'dsu' => 'D速快递',
                  'ees' => '百福东方物流',
                  'fedex' => '国际Fedex',
                  'fedexcn' => 'Fedex国内',
                  'feibang' => '飞邦物流',
                  'feibao' => '飞豹快递',
                  'feihang' => '原飞航物流',
                  'feiyuan' => '飞远物流',
                  'fengda' => '丰达快递',
                  'fkd' => '飞康达快递',
                  'fkdex' => '飞快达快递',
                  'gdyz' => '广东邮政物流',
                  'gongsuda' => '共速达物流|快递',
                  'guotong' => '国通快递',
                  'huayu' => '天地华宇物流',
                  'huitong' => '汇通快递',
                  'jiaji' => '佳吉快运',
                  'jiayi' => '佳怡物流',
                  'jiayunmei' => '加运美快递',
                  'jingguang' => '京广快递',
                  'jinyue' => '晋越快递',
                  'jldt' => '嘉里大通物流',
                  'kuaijie' => '快捷快递',
                  'lanbiao' => '蓝镖快递',
                  'lejiedi' => '乐捷递快递',
                  'lianhaotong' => '联昊通快递',
                  'longbang' => '龙邦快递',
                  'minhang' => '民航快递',
                  'nengda' => '港中能达快递',
                  'ocs' => 'OCS快递',
                  'pinganda' => '平安达',
                  'quanchen' => '全晨快递',
                  'quanfeng' => '全峰快递',
                  'quanjitong' => '全际通快递',
                  'quanritong' => '全日通快递',
                  'quanyi' => '全一快递',
                  'rpx' => 'RPX保时达',
                  'rufeng' => '如风达快递',
                  'santai' => '三态速递',
                  'scs' => '伟邦(SCS)快递',
                  'shengfeng' => '盛丰物流',
                  'shenghui' => '盛辉物流',
                  'shentong' => '申通快递（可能存在延迟）',
                  'sure' => '速尔快递',
                  'tiantian' => '天天快递',
                  'tnt' => 'TNT快递',
                  'tongcheng' => '通成物流',
                  'ups' => 'UPS',
                  'usps' => 'USPS快递',
                  'wanjia' => '万家物流',
                  'xinbang' => '新邦物流',
                  'xinfeng' => '信丰快递',
                  'yad' => '源安达快递',
                  'yafeng' => '亚风快递',
                  'yibang' => '一邦快递',
                  'yinjie' => '银捷快递',
                  'yousu' => '优速快递',
                  'ytfh' => '北京一统飞鸿快递',
                  'yuancheng' => '远成物流',
                  'yuantong' => '圆通快递',
                  'yuanzhi' => '元智捷诚',
                  'yuefeng' => '越丰快递',
                  'yunda' => '韵达快递',
                  'yuntong' => '运通中港快递',
                  'ywfex' => '源伟丰',
                  'zhaijisong' => '宅急送快递',
                  'zhongtie' => '中铁快运',
                  'zhongtong' => '中通快递',
                  'zhongxinda' => '忠信达快递',
                  'zhongyou' => '中邮物流',
                  'ems' => 'EMS快递',
                  'shunfeng' => '顺丰快递',
                  ],
    'kuaidi_api' => ['auspost' => '澳大利亚邮政(英文结果)',
                     'aae' => 'AAE',
                     'anxindakuaixi' => '安信达',
                     'baifudongfang' => '百福东方',
                     'bht' => 'BHT',
                     'youzhengguonei' => '包裹/平邮/挂号信',
                     'bangsongwuliu' => '邦送物流',
                     'cces' => '希伊艾斯(CCES)',
                     'coe' => '中国东方(COE)',
                     'city100' => '城市100',
                     'chuanxiwuliu' => '传喜物流',
                     'canpost' => '加拿大邮政Canada Post(英文结果)',
                     'canpostfr' => '加拿大邮政Canada Post(德文结果)',
                     'datianwuliu' => '大田物流',
                     'debangwuliu' => '德邦物流',
                     'dpex' => 'DPEX',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
                     'anxindakuaixi' => '安信达',
    ],

);
