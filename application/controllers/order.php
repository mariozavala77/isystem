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
            'orders.id', 'entity_id', 'orders.name', 'email', 'total_price', 'currency', 'shipping_name',
            'shipping_address', 'shipping_city', 'shipping_state_or_region', 'shipping_country',    
            'shipping_postcode', 'shipping_phone', 'channels.name as scource', 
            'purchased_at', 'payment_method', 'orders.status', 'orders.id as option'
            ];
        
        $orders = Order::filter($fields);
        $data = Datatables::of($orders)->make();

        foreach($data['aaData'] as $key => $datum) {
            $data['aaData'][$key][16] = Config::get('application.order_status')[$datum[16]];
        }

        return Response::json( $data );
    }

    // 订单同步
    public function action_sync() {

        $channels = Channel::filter(['accredit', 'synced_at', 'type', 'id'])->get();
        $results = Order::sync($channels);

        return Response::json($results);
    }

    // 订单处理
    public function action_handle() {
    
    }
}
?>
