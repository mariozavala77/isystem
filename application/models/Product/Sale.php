<?php

/**
 * 销售产品模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Sale.php  2012年11月06日 星期二 16时38分09秒Z $
 */
class Product_Sale {

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
        $query = DB::table('products_sale')->select($fields);

        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 插入数据
     *
     * @param: $data array 插入的数据
     *
     * return integer
     */
    public static function insert($data)
    {
        return DB::table('products_sale')->insert_get_id($data);
    }

    /**
     * 更新数据
     *
     * @param: $sale_id integer 代理商认购商品的id
     * @param: $data    array   更新的数据
     *
     * return boolean
     */
    public static function update($sale_id, $data)
    {
        return DB::table('products_sale')->where('id', '=', $sale_id)->update($data);
    }

    /**
     * 获取在售商品的id
     *
     * 通过代理商id，产品池id，销售渠道id进行获取
     *
     * @param: $agent_id   integer 代理商id
     * @param: $product_id integer 产品池中产品id
     *
     * return integer|boolean 正确则返回整数否则返回false
     */
    public static function getId($agent_id, $product_id)
    {
        return DB::table('products_sale')->where('product_id', '=', $product_id)
                                         ->where('agent_id', '=', $agent_id)
                                         ->only('id');

    }

    /**
     * 在售商品详细信息
     *
     * @param: $sale_id integer 销售ID
     *
     * return object
     */
    public static function info($sale_id)
    {
        return DB::table('products_sale')->where('id', '=', $sale_id)->first();
    }

    public static function mapping($sku, $product_id)
    {

        $sale_id = Product_Sale_Sku::filter('sale_id', ['sku' => $sku])->lists('sale_id');
        
        $data = ['product_id' => $product_id];

        $sale_result = DB::table('products_sale')->where_in('id', $sale_id)
                                                 ->update($data);

        $sku_result  = DB::table('products_sale_sku')->where('sku', '=', $sku)
                                                     ->update($data);
        return $sku_result && $sale_result;
    }
}
