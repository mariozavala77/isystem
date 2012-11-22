<?php

class Task_Order_Ship {

    // 同步队列发货
    public function __construct( $args ) {

        // 同步所有
        if(empty($args)) {
            $this->_ship_all();
        } else {
            foreach( $args as $order_id) {
                $order_id = intval($order_id) ? intval($order_id) : 0;
                $this->_ship_one($order_id);
            }
        }
    }

    // 遍历所有需发货订单
    private function _ship_all() {
        $fields = ['entity_id'];
        $filter = ['queues.type' => 'order', 'queues.action' => 'ship', 'queues.status' => 0];
        $order_ids = Queue::filter($fields, $filter)->lists('entity_id');
        $order_ids = array_unique($order_ids);
        foreach($order_ids as $order_id) {
            $this->_ship_one($order_id);
        }
    }

    /**
     * 订单发货
     *
     * @param: $order_id integer 订单ID
     *
     * return void
     */
    private function _ship_one($order_id) {

        $result = Order::push($order_id, 'ship');

        return false;
    }

}
?>
