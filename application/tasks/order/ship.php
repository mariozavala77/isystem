<?php

class Task_Order_Ship {

    // 同步队列发货
    public function __construct( $args ) {

        // 同步所有
        if(empty($args)) {
            $this->_ship_all();
        } else {
            return;
        }
    }

    // 遍历所有需发货订单
    private function _ship_all() {

        // 同步外部订单
        $out_channels = Config::get('application.out_channels');
        foreach($out_channels as $channel_id) {
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
        // 过滤外部渠道
        foreach($queues as $index => $queue) {
            if(in_array($queue->channel_id, $out_channels)) {
                unset($queues[$index]);
            }
        }
        if(!empty($queues)) Order::updateAgentChannel($queues, ORDER_SHIPPED);
    }
}
?>
