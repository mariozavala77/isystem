<?php

class ChannelAPI_Amazon {

    private $_options = null;

    public function __construct($options) {
        $this->_options = $options;
    }

    public function order() {
        return new ChannelAPI_Amazon_Order($this->_options);
    }

    public function product() {
    
    }
}


?>
