<?php

/**
 * 销售渠道API
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:ChannelAPI.php  2012年11月04日 星期日 10时16分38秒Z $
 */
class ChannelAPI {

    private $_api;

    /**
     * 实例化接口
     *
     * @parans: $interface string 接口名
     * @params: $options   array  接口参数
     *
     * return void
     */
    public function __construct ($interface, $options ) {
        $interface = 'ChannelAPI_' . $interface;
        $this->_api = new $interface($options);
    }

    /**
     * 订单处理
     */
    public function order() {
        return $this->_api->order();
    }

    /**
     * 销售产品处理
     */
    public function product() {
        return $this->_api->product();
    }
}
?>
