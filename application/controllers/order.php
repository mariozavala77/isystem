<?php

/**
 * 订单控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:order.php  2012年11月03日 星期六 12时49分05秒Z $
 */
class Order_Controller extends Base_Controller {

    // 订单列表
    public function action_index() {
        
        $total['order'] = 12;
        return View::make('order.list')->with('total', $total);
    }

    // 订单列表
    public function action_filter() {
        $fields = [ 
            'id', 'entity_id', 'name', 'email', 'total_price', 'currency', 'shipping_name',
            'shipping_address', 'shipping_city', 'shipping_state_or_region', 'shipping_country',    
            'shipping_postcode', 'shipping_phone', 'channel_id', 
            'purchased_at', 'payment_method', 'status', 'id as option'
            ];
        
        $orders = Order::filter($fields);
        $data = Datatables::of($orders)->make();

        return Response::json( $data );
    }

    // 订单同步
    public function action_sync() {

        $channels = Channel::all();
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

    // 订单处理
    public function action_handle() {
    
    }
}
?>
