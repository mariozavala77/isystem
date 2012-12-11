<?php
/**
 * 订单拆分计划任务
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Task_Order_Split{

    public function __construct( $args ){
        if(empty($args)){
            $this->_get_all();
        }else{
            $order_ids = [];
            foreach ($args as $order_id) {
                $order_id = intval($order_id);
                if(!empty($order_id)){
                    $order_ids[] = $order_id;    
                }
            }
            if(!empty($order_ids)){
                $this->_get_ids($order_ids);            
            }
        }
    }

    /**
     * 执行所有的订单拆分
     */
    private function _get_all(){
        $fields = ['id', 'synced_at', 'splited_at'];
        $filter = ['unequal' => ['type' => 'Agent']];
        $channels = Channel::filter($fields, $filter)->get();
        foreach($channels as $value){
            $this->_orders($value->id, $value->synced_at, $value->splited_at);
        }
    }

    /**
     * 根据指定的订单id进行拆分
     *
     * @param $order_ids array 订单的id集合
     */
    private function _get_ids($order_ids){
        $fields = ['orders.name', 'shipping_country', 'orders.status', 
                   'ship_status', 'orders.purchased_at', 'currency', 'channel_id', 'orders.id'];
        $table = Order::filter($fields);

        if(count($order_ids)==1){
            $table->where('orders.id', '=', $order_ids[0]);
        }else{
            $table->where_in('orders.id', $order_ids);
        }
        $channel_ids = Config::get('application.split_order_channel');
        if(count($channel_ids)==1){
            $table->where('channel_id', '=', $channel_ids[0]);
        }else{
            $table->where_in('channel_id', $channel_ids);
        }        
        $orders_info = $table->get();
        foreach ($orders_info as $order_info) {
            $this->_split($order_info);
        }
    }

    /**
     * 订单拆分
     *
     * @param $order_info array 订单信息
     *
     * @return bool
     */
    private function _split($order_info){
        $data = ['order_id'         => $order_info->id, 
                 'channel_id'       => $order_info->channel_id, 
                 'status'           => $order_info->status, 
                 'ship_status'      => $order_info->ship_status, 
                 'shipping_country' => $order_info->shipping_country, 
                 'name'             => $order_info->name, 
                 'currency'         => $order_info->currency,
                 'purchased_at'     => $order_info->purchased_at
                ];
        $items = $this->_items($data);
        if(empty($items)){
            return FALSE;
        }
        $split = [];
        foreach($items as $key=>$value){
            $data['items'] = serialize($value['items']);
            $data['total_price'] = array_sum($value['total_price']);
            $split[$key] = $params = $data;
            $params['id'] = $data['order_id'];
            $params['items'] = $value['items'];
            $params['agent_id'] = $key;
            unset($params['order_id']);
            $api = new AgentAPI('order.save', $params);
            $api->handle();
        }
        Orders_Split::dobatch($split);
    }

    /**
     * 订单产品信息
     *
     * @param $data array 订单的信息
     *
     * @return bool|array
     */
    private function _items($data){
        $items_data = Item::get($data['order_id']);
        if(empty($items_data)){
            return FALSE;
        }
        foreach ($items_data as $key=>$value){
            $sku[$value->sku] = $value->sku;
            $item[$value->sku] = $value;
        }
        $items_data = $item;
        $fields = ['psku.id','ps.product_id','agent_id', 'title', 'sku'];
        $sale_data = Product_Sale_Sku::filter($fields)->where_in('sku', $sku)->get();
        if(empty($sale_data)){
            $this->_supplement_sku($data['channel_id'], $sku, $items_data);
            return FALSE;
        }
        foreach($sale_data as $key=>$value){
            $sale_sku[$value->sku] = $value->sku;
            $item = $items_data[$value->sku];
            $data = ['sku' => $value->sku, 'shipping_price' => $item->shipping_price, 'title' => $value->title, 'quantity' => $item->quantity, 'price' => $item->price];
            $items[$value->agent_id]['items'][] = $data;
            $items[$value->agent_id]['total_price'][] = $item->price*$item->quantity + $item->shipping_price;
            // 没有进行映射的 写入任务中
            if(empty($value->product_id)){
                $this->_create_sku_mapping_task($value->id);
            }
        }
        // 求两组中sku的差集
        $skus = array_diff($sku, $sale_sku);
        if(!empty($skus)){
            $this->_supplement_sku($data['channel_id'], $skus, $items_data);

        }
        return $items;
    }

    /**
     * 订单列表
     *
     * @param $channel_id integer 渠道id
     * @param $synced_at  string  订单的同步时间
     * @param $splited_at string  订单上一次的拆分时间
     */
    private function _orders($channel_id, $synced_at, $splited_at){
        $fields = ['orders.name', 'shipping_country', 'orders.status', 
                   'ship_status', 'orders.purchased_at', 'currency', 'channel_id', 'orders.id'];
        $table = Order::filter($fields)->where('orders.updated_at', '>=', $splited_at)
                                       ->where('orders.updated_at', '<=', $synced_at)
                                       ->where('channel_id', '=', $channel_id);
        $count = $table;
        $count = $count->count();
        $page  = $count/10;
        $page  = $count%10?intval($page)+1:intval($page);
        for ($i = 0; $i < $page; $i++) {
            $orders_info = $table->skip($i * 10)->take(10)->get();
            foreach ($orders_info as $order_info) {
                $this->_split($order_info);
            }
        }
        $data = ['splited_at' => $synced_at];
        Channel::update($channel_id, $data);
    }

    /**
     * 销售产品补充
     *
     * @param $channel_id integer 销售渠道
     * @param $skus       array   sku
     * @param $items_data array   订单的产品信息
     */
    private function _supplement_sku($channel_id, $skus, $items_data){
        foreach ($skus as $sku) {
            $item = $items_data[$sku];
            if(empty($item)){
                break;
            }
            $date_time = date('Y-m-d H:i:s');
            $sale_data = ['agent_id'   => 1,
                          'price'      => $date_time,
                          'created_at' => date('Y-m-d H:i:s'),
                          'product_id' => 0
                         ];
            $sale_id = Product_Sale::insert($sale_data);

            if(!empty($sale_id)){
                $sale_sku_data = ['channel_id' => $channel_id,
                                  'product_id' => 0,
                                  'psid'       => $sale_id,
                                  'sold'       => 1,
                                  'sold_at'    => $date_time,
                                  'sku'        => $sku
                                 ];
                $sale_sku_id = Product_Sale_Sku::insert($sale_sku_data);
                if(!empty($sale_sku_id)){
                    $this->_create_sku_mapping_task($sale_sku_id);
                }
            }
        }
    }

    /**
     * 创建 在售产品的sku映射任务
     *
     * @param $sale_sku_id integer 代理商产品上架id
     */
    private function _create_sku_mapping_task($sale_sku_id){
        $data = ['to_uid'     => 1, 
                 'type'       => 'sku_mapping', 
                 'entity_id'  => $sale_sku_id,
                 'content'    => '产品映射',
                 'level'      => 9,
                 'created_at' => data('Y-m-d H:i:s')
                ];
        Tasks::insert($data);
    }
}