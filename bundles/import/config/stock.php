<?php

/**
 * 库存导入配置信息
 */
return [

    /*
    | -----------------------------------------------------------------------
    | 导入设置
    | -----------------------------------------------------------------------
    | 数据库字段名与表格列名映射
    | 顺序要跟模板表格对应的列保持一致
    */
    'import' => [
        'storage_id' => ['name' => '仓库', 'rule' => 'transduce:table,storage,area'],
        'product_id' => ['name' => 'SKU', 'rule' => 'transduce:table,products,sku'], 
        'code'       => ['name' => '编码', 'rule' => 'required'],
        'sellable'   => ['name' => '可销售的数量', 'rule' => 'required'],
        'unsellable' => ['name' => '不可销售的数量', 'rule' => 'required'],
    ],

    /*
    | -----------------------------------------------------------------------
    | 场景规则
    | -----------------------------------------------------------------------
    | 1.如果没有必填限制
    | <code>
    |     'scene' => [],
    | </code>
    | 
    | 2.默认场景
    | <code>
    |     'scene' => ['name', 'sku', 'language'],
    | </code>
    */
    'scenes' => [],

    // 存储
    'storage' => [
        'stock' => [
            'fields' => ['storage_id', 'code', 'product_id', 'sellable', 'unsellable', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'modified_at' => 'datetime' ],
            'uniques' => ['storage_id'=> 'stock', 'product_id' => 'stock', 'code' => 'stock'],
            'relation_field' => '',
            'relation_tables' => []
            ]
    ],


];
