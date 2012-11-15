<?php

/**
 * 订单产品表模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Item.php  2012年11月15日 星期四 15时15分33秒Z $
 */
class Item {

    /**
     * 获取订单产品
     *
     * @param: $order_id integer 订单ID
     *
     * return object
     */
    public static function get($order_id) {
        return DB::table('items')->where('order_id', '=', $order_id)->get()
    }

    /**
     * 插入订单产品信息
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data) {
        return DB::table('items')->insert($data);
    }

}

?>
