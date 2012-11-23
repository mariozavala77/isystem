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
        $fields = ['name', 'shipping_country', 'status', 
                   'ship_status', 'purchased_at', 'currency', 'channel_id', 'id'];
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
        $orders_info = $table->get();
        foreach ($orders_info as $key => $order_info) {
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
        $split = [];
        foreach($items as $key=>$value){
            $data['items'] = serialize($value['items']);
            $data['total_price'] = array_sum($value['total_price']);
            $split[$key] = $data;
        }
        
    }

    private function _items($order_id){
        $items_data = Item::get($order_id);
        foreach ($items_data as $key=>$value){
            $sku[$value->sku] = $value->sku;
            $item[$value->sku] = $value;
        }
        $items_data = $item;
        $fields = ['agent_id', 'title', 'sku'];
        $sale_data = Product_Sale_Sku::filter($fields)->where_in('sku', $sku)->get();
        foreach($sale_data as $key=>$value){
            $item = $items_data[$value->sku];
            $data = ['sku' => $value->sku, 'shipping_price' => $item->shipping_price, 'title' => $value->title, 'quantity' => $item->quantity, 'price' => $item->price];
            $items[$value->agent_id]['items'][] = $data;
            $items[$value->agent_id]['total_price'][] = $item->price*$item->quantity + $item->shipping_price;
        }
        return $items;
    }
}