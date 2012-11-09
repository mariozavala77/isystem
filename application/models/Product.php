<?php

/**
 * 产品模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Product.php  2012年11月01日 星期四 11时21分46秒Z $
 */
class Product {

    /**
     * 产品列表
     *
     * @param: $fields array  显示字段
     * @param: $lang   string 语言
     * @param: $filter array  过滤
     *
     * return object
     */
    public static function filter( $fields, $lang = 'en', $filter = [] ) {

        return DB::table('products as p')->left_join('products_extension as pe', 'p.id', '=', 'pe.product_id')
                                         ->select($fields);
    }

    /**
     * 添加产品
     *
     * @param: $data array 提交数据
     *
     * return boolean
     */
    public static function insert( $data ) {

        $product_data = [
            'sku'       => $data['sku'],
            'min_price' => $data['min_price'],
            'max_price' => $data['max_price'],
            'weight'    => $data['weight'] * 1000,
            'size'      => $data['size'],
            ];

        $product_id = static::_insertProduct( $product_data );

        if($product_id) {
            $product_extension_data = [
                'product_id'  => $product_id,
                'name'        => $data['name'],
                'language'    => $data['language'],
                'description' => $data['description'],
                'created_at'  => date('Y-m-d H:i:s'),
                ];

            static::_insertProductExtension( $product_extension_data );

            if($data['images']) {
                Product_Image::insert($product_id, explode(';', $data['images']));
            }
        }

        return $product_id ? true : false;
    }

    /**
     * 写入products表
     *
     * @param: $data array 插入数据
     *
     * return integer
     */
    private static function _insertProduct( $data ) {
        return DB::table('products')->insert_get_id($data);
    }

    /**
     * 写入proudcts_extension表
     *
     * @param: $data array 插入数据
     *
     * return void
     */
    private static function _insertProductExtension( $data ) {
        DB::table('products_extension')->insert( $data );;
    }
}
?>
