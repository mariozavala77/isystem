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
     * return integer
     */
    public static function insert( $data ) {
        return DB::table('orders')->insert_get_id( $data );
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

        // 如果修改订单状态需要更新
        /*
           修改更新时间用于平台同步

        */

        DB::table('orders')->where('id', '=', $order_id)->update($data);
    }

    /**
     * 订单列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = []) {
        $query = DB::table('orders')->left_join('channels', 'orders.channel_id', '=', 'channels.id')
                                    ->select( $fields );

        foreach($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query->order_by('orders.id', 'DESC');
    }

    /**
     * 订单详情
     *
     * @param: $order_id integer 订单ID
     *
     * return object
     */
    public static function info($order_id) {
        return DB::table('orders')->where('id', '=', $order_id)->first();
    }

    /**
     * 同步所有订单
     *
     * @param: $channels object 渠道信息
     *
     * return void
     */
    public static function sync($channels) {
        foreach($channels as $channel) {
            $interface = $channel->type;
            $options = unserialize($channel->accredit);
            $api = new ChannelAPI($interface, $options);

            // 订单处理
            $data = $api->order()->sync(['LastUpdatedAt' => $channel->synced_at]);
            if(!empty($data['data'])) {
                foreach($data['data'] as $datum) {

                    $datum['channel_id'] = $channel->id;

                    // 如果订单存在
                    if($order_info = static::exists(['entity_id' => $datum['entity_id'], 'channel_id' => $datum['channel_id'] ])) {
                        // 如果渠道端状态有更新，覆盖数据
                        if( $datum['updated_at'] > $order_info->updated_at && $datum['status'] != $order_info->status ) {
                            foreach($datum as $key => $value) {
                                if(empty($value)) {
                                    unset($datum[$key]);
                                }
                            }
                            static::update($order_info->id, $datum);
                        // 如果内控端状态有更新，更新渠道
                        } else if( $datum['updated_at'] < $order_info->updated_at && $datum['status'] != $order_info->status ) { 
                            // 同步发货和取消订单
                            if(in_array($order_info->status, [ORDER_CANCELED, ORDER_SHIPPED, ORDER_PARTIALLYSHIPPED])) {
                                if($order_info->status == ORDER_CANCELED) {
                                    $action = 'cancel';
                                } else {
                                    $action = 'ship';
                                }
                                $queue = [
                                    'type'       => 'order',
                                    'action'     => $action,
                                    'entity_id'  => $order_info->id,
                                    'status'     => 0,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                Queue::insert($queue);
                            }
                        }
                    // 订单不存在插入订单
                    } else {
                        $datum['created_at']  = date('Y-m-d H:i:s');
                        $datum['modified_at'] = date('Y-m-d H:i:s');
                        $datum['is_synced'] = 1;
                        static::insert($datum);
                    }
                }
            }
        }

        Item::sync($channels);
        Channel::update($channel->id, ['synced_at' => $data['synced_at']]);
        return ['status' => 'success', 'message' => ''];
    }

    /**
     * 订单更新推送到渠道
     *
     * @param: $order_id integer 订单ID
     * @param: $status   integer 推送状态
     *
     */
    public static function push() {
    
        return false;
    }

    /**
     * 获取订单所属国家
     *
     * return object
     */
    public static function country() {
        $countries = Cache::get('isystem.order.countries');

        if(!$countries) {
            $countries =  DB::table('orders')->where('shipping_country', '!=', '')
                                             ->group_by('shipping_country')
                                             ->lists('shipping_country');

            Cache::put('app.order.countries', $countries, 3600);
        }

        return $countries;
    }

    /**
     * 获取可以发货的订单
     *
     * @param: $order_ids array 订单ID
     *
     * return array
     */
    public static function ship($order_ids) {
        return DB::table('orders')->where('status', '=', ORDER_UNSHIPPED)
                                  ->where('is_auto', '=', '0')  // 不是FBA订单
                                  ->where_in('id', $order_ids)
                                  ->lists('id');
    }

    /**
     * 批量发货处理
     *
     * @param: $company string 快递公司 
     * @param: $method  string 投递方式
     * @param: $tracking array  订单跟踪信息
     *
     * return void
     */
    public static function doBatchShip($company, $method, $tracking) {

        foreach($tracking as $order_id => $tracking_no) {
            if(!empty($tracking_no)) {
                $items = Item::get($order_id);
                foreach($items as $item) {
                    $data = [
                        'company'     => $company,
                        'method'      => $method,
                        'order_id'    => $order_id,
                        'item_id'     => $item->id,
                        'quantity'    => $item->quantity,
                        'tracking_no' => $tracking_no,
                        'status'      => 0,
                        'created_at'  => date('Y-m-d H:i:s'),
                        'modified_at' => date('Y-m-d H:i:s'),
                        ];

                    Track::insert($data);
                }

                // 更新订单状态
                $data = [
                    'is_synced'  => 0,
                    'status'     => ORDER_SHIPPED,
                    'updated_at' => date('Y-m-d H:i:s'),
                    ];
                Order::update($order_id, $data);

                // 发送同步队列
                $data = [
                    'type'      => 'order',
                    'action'    => 'ship',
                    'entity_id' => $order_id,
                    'status'    => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    ];
                Queue::insert($data);
            }
        }
    }
}

?>
