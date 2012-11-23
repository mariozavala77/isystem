<?php
/**
 * 销售产品SKU模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Product_Sale_Sku {


    /**
     * 销售列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = []) {
        $query = DB::table('products_sale_sku as psku')->left_join('products_sale as ps', 'ps.id', '=', 'psku.psid')
                                                       ->select($fields);

        foreach($filter as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 插入数据
     * @param: $data array 插入的数据
     * return boolean
     */
    public static function insert($data){
        return DB::table('products_sale_sku')->insert_get_id( $data );
    }

    /**
     * 更新数据
     *
     * @param: $product_sale_sku_id intgrean 代理商认购商品上架的id
     * @param: $data                array    更新的数据
     * return boolen
     */
    public static function update($product_sale_sku_id, $data){
        return DB::table('products_sale_sku')->where('id', '=', $product_sale_sku_id)->update($data);
    }

    /**
     * 在售商品详细信息
     *
     */
    public static function info($sale_id){
        return DB::table('products_sale_sku')->where('id', '=', $sale_id)->first();
    }

    public static function dobatch($data){

    }

    public static function batchinsert($data){

    }

    public static function batchupdate($data){
        
    }
}