<?php
/**
 * 代理商api-订单
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class AgentAPI_Order extends AgentAPI_Base{

    // 订单详细获取
    public static function info($params){
        $fields = ['order_id'];

        try{
            $data = self::requeryParams($fields, $params);
        }catch(Exception $e){
            throw new AgentAPIException($e->getMessage(), -32004);
        }

        $return_fields = ['name', 'shipping_country', 'payment_method', 'status', 
                          'ship_status', 'purchased_at'];
        $order_info = Order::info($data['order_id']);
        $return = [];
        foreach ($return_fields as $value) {
            $return[$value] = $order_info->$value;
        }

        $items = Item::get($data['order_id']);

        // 取出订单下面的所有sku
        foreach ($items as $key=>$value){
            $items[$key] = $value->sku;
        }

        // 订单拆分
        $fields = ['sku', 'price', 'shipping_price'];
        // 订单拆分
        $sales = Product_Sale::filter($fields)->where_in('sku', $items)
                                              ->where('agent_id', '=', $agent_id)
                                              ->get();
        $total_price = 0;
        $item = [];

        foreach ($sales as $key=>$value){
            $total_price += ($value->price + $value->shipping_price);
            $item[$key] = ['sku' => $value->sku, 'shipping_price' => $value->shipping_price];
        }

        $return['total_price'] = $total_price;
        $return['items'] = $item;

        return $return;
    }

    // 订单保存
    public static function save($params){
        $fields = [
            'entity_id', 'name', 'email', 'total_price', 
            'currency', 'shipping_name', 'shipping_phone', 'shipping_address', 
            'shipping_city', 'shipping_state_or_region', 'shipping_country', 
            'shipping_postcode' ,'payment_method', 'status', 
            'purchased_at', 'created_at', 'items', 'shipping_price'
            ];
        $item_fields = ['entity_id', 'sku', 'quantity', 'price'];

        try{
            $data = self::requeryParams($fields, $params);
        }catch(Exception $e){
            throw new AgentAPIException($e->getMessage(), -32004);
        }
        $items = $data['items'];

        foreach ($items as $key => $value) {
            try{
                $items[$key] = self::requeryParams($item_fields, $value);
            }catch(Exception $e){
                throw new AgentAPIException('items '.$e->getMessage(), -32004);
            }
        }

        unset($data['items']);
        $shipping_price = $data['shipping_price'];
        unset($data['shipping_price']);
        $data['ship_status'] = 0;
        $data['modified_at'] = date('Y-m-d H:i:s');
        $agent = Agent::info($params['agent_id']);
        $data['channel_id'] = $agent->channel_id;

        $exists = ['entity_id' => $data['entity_id'], 'channel_id' => $data['channel_id']];
        $order_info = Order::exists($exists);
        if(empty($order_info)){
            $order_id = Order::insert( $data );
            if(empty($order_id)){
                foreach($items as $key=>$value){
                    $value['order_id'] = $order_id;
                    $value['shipping_price'] = $shipping_price;
                    Item::insert($value);
                }
                return ['order_id' => $order_id];
            }else{
                throw new AgentAPILogException('order sql insert wrong', -32006);
            }
        }else{
            if(Order::update( $order_info->id, $data )){
                return ['order_id' => $order_info->id];
            }else{
                throw new AgentAPILogException('order sql update wrong', -32018);
            }
        }
    }
}