<?php

/**
 * 订单模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Order.php  2012年11月03日 星期六 12时47分04秒Z $
 */
class Order {

    /**
     * 检查订单是否存在
     *
     * 如果存在返回订单数据
     *
     * @param: $data array 筛选数据
     *
     * return object
     */
    public static function exists( $data ) {
    
        $table = DB::table('orders');

        foreach($data as $key => $value) {
            $table = $table->where($key, '=', $value);
        }

        return $table->first();
    }


    /**
     * 新增订单
     *
     * @param: $data array  新增数据
     *
     * return void
     */
    public static function insert( $data ) {
        DB::table('orders')->insert( $data );
    }

    /**
     * 更新订单
     *
     * @param: $order_id integer 订单ID
     * @param: $data     array   订单数据
     *
     * return void
     */
    public static function update( $order_id, $data ) {
        DB::table('orders')->where('id', '=', $order_id)->update($data);
    }

    /**
     * 订单列表
     *
     * @param: $fields array 字段
     *
     * return object
     */
    public static function filter( $fields ) {

        return DB::table('orders')->select( $fields );
    }

    /**
     * 向渠道同步订单信息
     *
     * @param: $order_id integer 订单ID
     * @param: $channel  object  渠道信息
     *
     * return void
     */
    public static function sync($order_id, $channel) {
        return false;
    }



}

?>
