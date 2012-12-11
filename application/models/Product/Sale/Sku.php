<?php
/**
 * 销售产品SKU模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Product_Sale_Sku 
{

    /**
     * 销售列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = [])
    {
        $query = DB::table('products_sale_sku as pss')->left_join('products_sale as ps', 'ps.id', '=', 'pss.sale_id')
                                                      ->left_join('agents as a', 'a.id', '=', 'ps.agent_id')
                                                      ->left_join('channels as c', 'c.id', '=', 'pss.channel_id')
                                                      ->select($fields);

        foreach($filter as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 插入数据
     *
     * @param: $data array 插入的数据
     *
     * return boolean
     */
    public static function insert($data)
    {
        return DB::table('products_sale_sku')->insert_get_id( $data );
    }

    /**
     * 更新数据
     *
     * @param: $sale_sku_id integer 代理商认购商品上架的id
     * @param: $data        array   更新的数据
     * return boolen
     */
    public static function update($sale_sku_id, $data)
    {
        return DB::table('products_sale_sku')->where('id', '=', $sale_sku_id)->update($data);
    }

    /**
     * 在售商品详细信息
     *
     */
    public static function info($sale_id)
    {
        return DB::table('products_sale_sku as pss')->left_join('products_sale as ps', 'ps.id', '=', 'pss.sale_id')
                                                     ->select(['pss.*', 'ps.title', 'ps.price', 'ps.keywords', 'ps.short_description', 'ps.description'])
                                                     ->where('pss.id', '=', $sale_id)
                                                     ->first();
    }

    /**
     * 是否上架在售
     *
     * @param: $product_id integer 产品ID
     * @param: $channel_id integer 渠道ID
     *
     * return integer
     */
    public static function onSale($product_id, $channel_id)
    {
        return DB::table('products_sale_sku')->where('product_id', '=', $product_id)
                                             ->where('channel_id', '=', $channel_id)
                                             ->only('id');
    }

    /**
     * 通过在售产品SKU获取产品池ID
     *
     * @param: $sku string 在售产品SKU
     *
     * return integer
     */
    public static function map($sku) {
        return DB::table('products_sale_sku')->where('sku', '=', $sku)->only('product_id');
    }

    public static function existence($sale_id, $channel_id){
        return DB::table('products_sale_sku')->where('sale_id', '=', $sale_id)
                                             ->where('channel_id', '=', $channel_id)
                                             ->only('id');
    }
}
