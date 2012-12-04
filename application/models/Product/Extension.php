<?php

/**
 * 产品扩展表模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Extension.php  2012年11月13日 星期二 15时02分57秒Z $
 */
class Product_Extension {


    /**
     * 插入
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data) {
        return DB::table('products_extensions')->insert($data);
    }

    /**
     * 更新
     *
     * @param: $product_id integer ID
     * @param: $language   string  语言
     * @param: $data       array   数据
     *
     * return boolean
     */
    public static function update($product_id, $language, $data) {
        return DB::table('products_extensions')->where('product_id', '=', $product_id)
                                               ->where('language', '=', $language)
                                               ->update($data);
    }

    /**
     * 删除
     *
     * @param: $product_id integer ID
     *
     * return boolean
     */
    public static function delete($product_id) {
        return DB::table('products_extensions')->where('product_id', '=', $product_id)->delete();
    }

    /**
     * 产品附属列表
     *
     * @param: $fields array  显示字段
     * @param: $filter array  过滤
     *
     * return object
     */
    public static function filter( $fields, $filter = [] ) {

        $query = DB::table('products_extensions')->select($fields);

        foreach($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query->order_by('product_id', 'DESC');
    }
    
}

?>
