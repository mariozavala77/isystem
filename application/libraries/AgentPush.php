<?php
/**
 * 代理商消息推送
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class AgentPush{

    /**
     * 推送订单状态
     *
     * @param $agent_id integer 代理商id
     * @param $order_id integer 订单id
     * @param $status   integer 状态
     *
     * @return bool|mixed
     */
    public static function order_status($agent_id, $order_id, $status){
        $params = ['id' => $order_id, 'agent_id' => $agent_id, 'status' => $status];
        $api = new AgentAPI('order.save', $params);
        return $api->handle();
    }

    /**
     * 推送订单物流信息状态
     *
     * @param $agent_id    integer 代理商id
     * @param $order_id    integer 订单id
     * @param $ship_status integer 物流状态
     *
     * @return bool|mixed
     */
    public static function order_ship_status($agent_id, $order_id, $ship_status){
        $params = ['id' => $order_id, 'agent_id' => $agent_id, 'ship_status' => $ship_status];
        $api = new AgentAPI('order.save', $params);
        return $api->handle();
    }

    /**
     * 推送订单详情
     *
     * @param $agent_id integer 代理商id
     * @param $order_id integer 订单id
     *
     * @return bool|mixed
     */
    public static function order_info($agent_id, $order_id){
        $return_fields = ['name', 'shipping_country', 'status', 
                          'ship_status', 'purchased_at'];
        $order_info = Order::info($order_id);
        $params = ['id' => $order_id, 'agent_id' => $agent_id];
        foreach ($return_fields as $value) {
            $params[$value] = $order_info->$value;
        }

        $items = Item::get($order_id);

        // 取出订单下面的所有sku
        foreach ($items as $key=>$value){
            $items[$key] = $value->sku;
        }
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

        $params['total_price'] = $total_price;
        $params['items'] = $item;

        $api = new AgentAPI('order.save', $params);
        return $api->handle();
    }

    /**
     * 推送产品信息审核状态
     *
     * @param $agent_id   integer 代理商id
     * @param $product_id integer 产品池id
     * @param $status     integer 审核状态
     * @param $message    string  审核不成功的原因
     *
     * @return bool|mixed
     */
    public static function product_sale($agent_id, $product_id, $status, $message = ''){
        $params = ['id' => $product_id, 'agent_id' => $agent_id, 'status' => $status];

        if(!empty($message)){
            $params['message'] = $message;
        }

        $api = new AgentAPI('product.save', $params);
        return $api->handle();
    }

    /**
     * 推送产品上架状态
     *
     * @param $agent_id   integer 代理商id
     * @param $product_id integer 产品池id
     * @param $sold       integer 上架状态
     *
     * @return bool|mixed
     */
    public static function product_sold($agent_id, $product_id, $sold){
        $params = ['id' => $product_id, 'agent_id' => $agent_id, 'sold' => $sold];

        $api = new AgentAPI('product.save', $params);
        return $api->handle();
    }

    /**
     * 发送站内信息
     *
     * @param $agent_id integer 代理商id
     * @param $titile   string  消息标题
     * @param $content  string  消息内容
     * @param $user_id  integer 管理员id 默认为0     
     *
     * @return bool|mixed
     */
    public static function message_send($agent_id, $titile, $content, $user_id = 0){
        $params = ['titile' => $titile, 'agent_id' => $agent_id, 'content' => $content, 'user_id' => $user_id];

        $api = new AgentAPI('message.create', $params);
        return $api->handle();
    }
}