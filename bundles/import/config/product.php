<?php

/**
 * 产品表格相关信息
 */
return [

    // 允许的语言版本
    'language' => [ 'cn', 'en', 'de' ],

    // 表格对应key
    'keys' => [ 
        'name', 'sku', 'language', 'category_id', 'cost', 'min_price', 'max_price', 
        'supplier_id', 'devel_id', 'weight', 'size', 'images', 'keywords', 'short_descrption',
        'description',
        ],

     // 表格头信息
    'head' => [ 
        '名称', 'SKU', '语言', '分类', '成本价', '最低价格','最高价格', '供应商', '开发人',
        '重量','尺寸', '图片', '关键词', '简要描述', '详细描述'
        ],

    // 验证信息
    'rules' => [
           'cn' => [ // 中文版本验证 针对产品开发部门
                '0'  => 'required|max:255',                         // 名称
                '1'  => 'required|max:20',                          // SKU
                '2'  => 'required|max:2',                           // 语言
                '3'  => 'required',                                 // 分类
                '4'  => 'required|match:/^\d+\.\d{2}$/',            // 成本
                '5'  => 'required|match:/^\d+\.\d{2}$/',            // 最低价格
                '6'  => 'required|match:/^\d+\.\d{2}$/',            // 最高价格
                '7'  => 'required',                                 // 供应商
                '8'  => 'required',                                 // 开发人
                '9'  => 'required',                                 // 重量
                '10' => 'required',                                 // 尺寸 
                '11' => 'required|match:/^(\w+\_\d+\.(jpg)\;)+$/',  // 图片
                '12' => 'required',                                 // 描述
                ],

            // 中文版本以外 针对客服部门
            'default' => [
                '0'  => 'required|max:255',  // 名称
                '1'  => 'required|max:20',   // SKU
                '2'  => 'required',          // 语言
                '12' => 'required',          // 描述
                '13' => 'required|max:255',  // 关键词
                '14' => 'required',          // 短描述
                ],
            ],
];
?>
