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
            $data['aaData'][$key][16] = Config::get('application.order_status')[$datum[16]]['name'];
        }

        return Response::json( $data );
    }

    // 订单同步
    public function action_sync() {

        $channels = Channel::filter(['accredit', 'synced_at', 'type', 'id'])->get();
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
            'broken'         => $order->broken ? '正常' : '异常',
            'auto'           => $order->auto ? '是' : '否',
            'is_sync'        => $order->is_sync ? '是' : '否',
            'channel'        => $channel->name,
            'shipment_level' => $order->shipment_level,
            'purchased_at'   => $order->purchased_at,
            'payment_method' => $order->payment_method,
            'updated_at'     => $order->updated_at,
            'modified_at'    => $order->modified_at,
            'created_at'     => $order->created_at,
            'name'           => $order->name,
            'shipping'       => $order->shipping_name ? $order->shipping_name. '<br />' . 
                                $order->email . '<br .>' . 
                                $order->shipping_address . '<br />' .
                                $order->shipping_city . ' ' . $order->shipping_state_or_region . ' ' . $order->shipping_country . '<br />zip:' .
                                $order->shipping_postcode . '<br />tel:' .
                                $order->shipping_phone : '',
            'products'        => '暂无',
            ];

        return Response::json($results);
    }

    // 批量操作
    public function action_batch() {
        $action = Input::get('action');
        $order_ids = Input::get('order_ids');

        $results = ['status' => 'fail', 'message' => '参数传递失败。'];

        switch ($action) {
            case 'ship':
                $results = ['status' => 'success', 'message' => Order::ship($order_ids)];
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
            Order::doBatchShip($input['ship_company'], $input['ship_method'], $tracking);
        } else {
            Order::doShip();
        }
    }
}
?>
