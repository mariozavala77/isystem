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

        // 同步外部订单
        foreach(Config::get('application.out_channels') as $channel_id) {
            $fields = ['id', 'entity_id', 'params'];
            $filter = ['type' => 'order' , 'action' => 'ship', 'channel_id' => $channel_id, 'status' => 0 ];
            $queues = Queue::filter($fields, $filter)->get();
            if(isset($queues[0])) {
                $params = $queues[0]->params;
                $params = unserialize($params);
                $queues['type'] = $params['class'];
                $queues['options'] = $params['options'];
            }

            if(!empty($queues)) Order::outShip($queues);
        }

        // 同步内部订单
        $fields = ['id', 'entity_id', 'params'];
        $filter = ['type' => 'order', 'action'=> 'ship', 'status' => 0];
        $queues = Queue::filter($fields, $filter)->get();
        if(!empty($queues)) Order::inShip($queues);
    }

    /**
     * 订单发货
     *
     * @param: $order_id integer 订单ID
     *
     * return void
     */
    private function _ship_one($order_id) {

        //$result = Order::push($order_id, 'ship');

        return false;
    }

}
?>
