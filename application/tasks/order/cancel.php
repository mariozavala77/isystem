<?php

class Task_Order_Cancel {

    // 同步取消
    public function __construct( $args ) {

        // 同步所有
        if(empty($args)) {
            $this->_cancel_all();
        } else {
            return;
        }
    }

    // 遍历所有需取消订单
    private function _cancel_all() {

        // 同步取消外部订单
        $out_channels = Config::get('application.out_channels');
        foreach($out_channels as $channel_id) {
            $fields = ['id', 'entity_id', 'params'];
            $filter = ['type' => 'order' , 'action' => 'cancel', 'channel_id' => $channel_id, 'status' => 0 ];
            $queues = Queue::filter($fields, $filter)->get();
            if(isset($queues[0])) {
                $params = $queues[0]->params;
                $params = unserialize($params);
                $queues['type'] = $params['class'];
                $queues['options'] = $params['options'];
            }

            if(!empty($queues)) Order::outCancel($queues);
        }

        // 同步取消内部订单
        $fields = ['id', 'entity_id', 'params'];
        $filter = ['type' => 'order', 'action'=> 'cancel', 'status' => 0];
        $queues = Queue::filter($fields, $filter)->get();
        if(!empty($queues)) Order::updateAgentChannel($queues, ORDER_CANCELED);
    }

}
?>
