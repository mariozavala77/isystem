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
        $countries = Order::country();
        $status = Config::get('application.order_status');
        $fields = ['id', 'name'];
        $channels = Channel::filter($fields)->get();
        $logistic_company = Config::get('application.logistic_company');

        return View::make('order.list')->with('countries', $countries)
                                       ->with('status', $status)
                                       ->with('channels', $channels)
                                       ->with('logistic_company', $logistic_company);
    }

    // 订单列表
    public function action_filter() {
        $fields = [ 
            'orders.id', 'entity_id', 'orders.name', 'email', 'total_price', 'currency', 'shipping_name',
            'shipping_address', 'shipping_city', 'shipping_state_or_region', 'shipping_country',    
            'shipping_postcode', 'shipping_phone', 'channels.name as source', 
            'purchased_at', 'payment_method', 'orders.status', 'orders.id as option'
            ];

        $filter = ['is_auto' => 0];
        
        $orders = Order::filter($fields, $filter);
        $data = Datatables::of($orders)->make();

        foreach($data['aaData'] as $key => $datum) {
            $data['aaData'][$key][16] = Config::get('application.order_status')[$datum[16]]['name'];
        }

        return Response::json( $data );
    }

    // 订单同步
    public function action_sync() {

        $filter = ['unequal' => ['type' => 'Agent']];
        $channels = Channel::filter(['accredit', 'synced_at', 'type', 'id'], $filter)->get();
        $results = Order::sync($channels);

        return Response::json($results);
    }

    // 订单详情
    public function action_info() {
        $order_id = Input::get('order_id');

        $order = Order::info($order_id);


        $channel = Channel::info($order->channel_id);
        $results = [
            'entity_id'      => $order->entity_id,
            'status'         => Config::get('application.order_status')[$order->status]['name'],
            'total_price'    => $order->currency . ' ' . $order->total_price,
            'is_broken'      => $order->is_broken == '0' ? '正常' : '异常',
            'is_auto'        => $order->is_auto ? '是' : '否',
            'is_synced'      => $order->is_synced ? '已同步' : '未同步',
            'channel'        => $channel->name,
            'shipment_level' => Config::get('application.order_shipment_level')[$order->shipment_level],
            'purchased_at'   => $order->purchased_at,
            'payment_method' => $order->payment_method,
            'updated_at'     => $order->updated_at,
            'modified_at'    => $order->modified_at,
            'created_at'     => $order->created_at,
            'name'           => $order->name,
            'country'        => $order->shipping_country,
            'shipping'       => $order->shipping_name ? $order->shipping_name. '<br />' . 
                                $order->email . '<br .>' . 
                                $order->shipping_address . '<br />' .
                                $order->shipping_city . ' ' . $order->shipping_state_or_region . ' ' . $order->shipping_country . '<br />zip:' .
                                $order->shipping_postcode . '<br />tel:' .
                                $order->shipping_phone : '',
            'items_info'      => Item::info($order_id),
            ];

        if(in_array($order->status, [ORDER_UNSHIPPED, ORDER_PARTIALLYSHIPPED])) {
            $results['items_ship'] = Item::ship($order_id);
        }

        return Response::json($results);
    }

    // 批量操作
    public function action_batch() {
        $action = Input::get('action');
        $order_ids = Input::get('order_ids');

        $results = ['status' => 'fail', 'message' => '参数传递失败。'];

        switch ($action) {
            case 'ship':
                $results = ['status' => 'success', 'message' => Order::shipable($order_ids)];
                break;
            
            default:
                break;
        }

        return Response::json($results);
    }

    // 订单发货处理
    public function action_ship() {
        $input = Input::all();

        // 如果多个ID为批量
        if(isset($input['ship_order_ids']) && !empty($input['ship_order_ids'])) {
            $tracking = array_combine($input['ship_order_ids'], $input['ship_tracking_nos']);
            $result = Order::doBatchShip($input['ship_company'], $input['ship_method'], $tracking);
        } else if(isset($input['order_ship'])) {
            $result = Order::doShip($input['order_ship']);
        }

        return Response::json($result);
    }

    // 取消订单处理
    public function action_cancel() {
        $order_id = Input::get('order_id');
        Order::doCancel($order_id);
        $result = ['status' => 'success'];

        return Response::json($result);

    }
}
