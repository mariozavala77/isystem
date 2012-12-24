<?php

/**
 * 订单任务
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:order.php  2012年11月21日 星期三 18时00分18秒Z $
 */

class Order_Task extends Task {

    public function run( $arguments = [] ) {

        if(!count($arguments)) {
            $this->_help();
            exit;
        }

        $command = (isset($arguments[0]) && $arguments[0] !=='') ? $arguments[0] : 'help';
        $args = array_slice($arguments, 1);

        switch ($command) {
            case 'ship': // 渠道发货
            case 's':
                new Task_Order_Ship($args);
                break;
            case 'split': // 拆分订单 发送订单到渠道
                new Task_Order_Split($args);
                break;
            case 'cancel': // 渠道取消订单
            case 'c':
                new Task_Order_Cancel($args);
                break;
            case 'sync': // 同步订单
                new Task_Order_Sync($args);
                break;
            case 'track': // 同步FBA物流
                new Task_Order_Track($args);
                break;
            default:
                $this->_help();
                break;
        }
    } 

    /**
     * help
     */
    private function _help() {
        echo '帮助 ：';
        echo "\torder <命令> [参数] [订单ID...]\n";
        echo "命令:\n";
        echo "\tsync\t同步订单\n";
        echo "\ts/ship\t发货\n";
        echo "\tsplit\t订单拆分\n";
        echo "\tc/cancel\t订单取消\n";
    }
    
}
