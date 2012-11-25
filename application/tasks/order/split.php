<?php
/**
 * 订单拆分计划任务
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

    private function _get_all(){
        $fields = ['id', 'synced_at', 'splited_at'];
        $filter = ['unequal' => ['type' => 'Agent']];
        $channels = Channel::filter($fields, $filter);
        foreach($channels as $value){
            $this->_orders($value->id, $value->synced_at, $value->splited_at);
        }
    }

    
    private function _get_ids($order_ids){
        $fields = ['orders.name', 'shipping_country', 'orders.status', 
                   'ship_status', 'purchased_at', 'currency', 'channel_id', 'orders.id'];
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

    // 订单拆分
    private function _split($order_info){
        $data = ['order_id'         => $order_info->id, 
                 'channel_id'       => $order_info->channel_id, 
                 'status'           => $order_info->status, 
                 'ship_status'      => $order_info->ship_status, 
                 'shipping_country' => $order_info->shipping_country, 
                 'name'             => $order_info->name, 
                 'currency'         => $order_info->currency
                ];
        $items = $this->_items($data['order_id']);
        if(empty($items)){
            return false;
        }
        $split = [];
        foreach($items as $key=>$value){
            $data['items'] = serialize($value['items']);
            $data['total_price'] = array_sum($value['total_price']);
            $split[$key] = $data;
        }
        Orders_Split::dobatch($split);  
    }

    private function _items($order_id){
        $items_data = Item::get($order_id);
        if(empty($items_data)){
            return false;
        }
        foreach ($items_data as $key=>$value){
            $sku[$value->sku] = $value->sku;
            $item[$value->sku] = $value;
        }
        $items_data = $item;
        $fields = ['agent_id', 'title', 'sku'];
        $sale_data = Product_Sale_Sku::filter($fields)->where_in('sku', $sku)->get();
        if(empty($sale_data)){
            return false;
        }
        foreach($sale_data as $key=>$value){
            $item = $items_data[$value->sku];
            $data = ['sku' => $value->sku, 'shipping_price' => $item->shipping_price, 'title' => $value->title, 'quantity' => $item->quantity, 'price' => $item->price];
            $items[$value->agent_id]['items'][] = $data;
            $items[$value->agent_id]['total_price'][] = $item->price*$item->quantity + $item->shipping_price;
        }
        return $items;
    }

    private function _orders($channel_id, $synced_at, $splited_at){
        $fields = ['orders.name', 'shipping_country', 'orders.status', 
                   'ship_status', 'purchased_at', 'currency', 'channel_id', 'orders.id'];
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
}