<?php

class Task_Order_Sync {
    
    // 同步订单
    public function __construct($args) {

        // 同步所有
        $this->_sync_all();
    
    }

    public function _sync_all() {
        $filter = ['unequal' => ['type' => 'Agent']];
        $channels = Channel::filter(['accredit', 'synced_at', 'type', 'id'], $filter)->get();
        $results = Order::sync($channels);
        echo 'done';
    }
}
