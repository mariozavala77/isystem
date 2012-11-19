<?php

class ChannelAPI_Amazon {

    private $_options = null;

    public function __construct($options) {
        $this->_options = $options;
    }

    /**
     * 订单处理
     */
    public function order() {
        return new ChannelAPI_Amazon_Order($this->_options);
    }

    /**
     * 产品处理
     */
    public function product() {
        return new ChannelAPI_Amazon_Product($this->_options);
    }
}


?>
