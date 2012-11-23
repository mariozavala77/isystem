<?php

/**
 * 订单跟踪表模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Track.php  2012年11月15日 星期四 15时03分26秒Z $
 */
class Track {

    /**
     * 插入跟踪信息
     *
     * @param: $data array 跟踪数据
     *
     * return boolean
     */
    public static function insert($data) {
        return DB::table('track')->insert($data);
    }

    /**
     * 通过订单ID获取产品发货信息
     *
     * @param: $order_id integer 订单ID
     * 
     * return array
     */
    public static function items($order_id) {
        return DB::table('track')->left_join('items', 'track.item_id', '=', 'items.id')
                                 ->where('track.status', '=', '0')
                                 ->where('track.order_id', '=', $order_id)
                                 ->get(['items.entity_id', 'track.quantity', 'track.company', 'track.method', 'track.tracking_no']);
    }

    /**
     * 获取产品的已发货数量
     *
     * @param: $item_id integer 订单产品ID
     *
     * return integer
     */
    public static function itemCount( $item_id ) {
        $track = DB::query('select sum(quantity) as count from track where item_id = '. $item_id . ' group by item_id');

        return isset($track[0]->count) ? $track[0]->count : 0;
    }

    /**
     * 获取订单的已发货数量
     *
     * @param: $order_id integer 订单ID
     *
     * return integer
     */
    public static function orderCount( $order_id ) {
        $track = DB::query('select sum(quantity) as count from track where order_id = '. $order_id . ' group by order_id');

        return isset($track[0]->count) ? $track[0]->count : 0;
    }

    public static function update($filter, $data){

        $query = DB::table('track');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->update($data);
    }
}
