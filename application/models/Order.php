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

        return DB::table('orders')->left_join('channels', 'orders.channel_id', '=', 'channels.id')->select( $fields )->order_by('orders.id', 'DESC');
    }

    /**
     * 同步所有订单
     *
     * @param: $channel  object  渠道信息
     *
     * return void
     */
    public static function sync($channels) {
        foreach($channels as $channel) {
            $interface = $channel->type;
            $options = unserialize($channel->accredit);
            $api = new ChannelAPI($interface, $options);
            $data = $api->order()->sync(['LastUpdatedAt' => $channel->synced_at]);
            if(!empty($data['data'])) {
                foreach($data['data'] as $datum) {

                    $datum['channel_id'] = $channel->id;

                    // 如果订单存在
                    if($order_info = Order::exists( ['entity_id' => $datum['entity_id'], 'channel_id' => $datum['channel_id'] ])) {
                        // 如果渠道端状态有更新，覆盖数据
                        if( $datum['updated_at'] > $order_info->updated_at && $datum['status'] > $order_info->status ) {
                            foreach($datum as $key => $value) {
                                if(empty($value)) {
                                    unset($datum[$key]);
                                }
                            }
                            Order::update($order_info->id, $datum);
                        // 如果内控端状态有更新，更新渠道
                        } else if( $datum['updated_at'] < $order_info->updated_at && $datum['status'] < $order_info->status ) { 
                            Order::sync($order_info->id);
                        }
                    // 订单不存在插入订单
                    } else {
                        $datum['created_at']  = date('Y-m-d H:i:s');
                        $datum['modified_at'] = date('Y-m-d H:i:s');
                        Order::insert($datum);
                    }
                }

                Channel::update($channel->id, ['synced_at' => $data['synced_at']]);
            }
        }
    }
}

?>
