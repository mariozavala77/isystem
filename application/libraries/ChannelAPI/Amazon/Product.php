<?php

class ChannelAPI_Amazon_Product
{

    private $_options = null;
    private $_url = null;
    private $_last_updated = null;

    /**
     * 构造函数
     *
     * @param: $options array 参数
     *
     * return void
     */
    public function __construct($options)
    {
        $this->_options = $options;
    }


    /**
     * 上架
     *
     * @param: $params array 参数
     *
     * return void
     */
    public function listing($params)
    {
        $api = new ChannelAPI_Amazon_Feed();
        $api->setParams($this->_options, $params);
        $type = $api::PRODUCT_FEED;
        $api->setFeedType($type);
        $api->perform();
        $type = $api::PRODUCT_IMAGES_FEED;
        $api->setFeedType($type);
        $api->perform();
        $type = $api::INVENTORY_FEED;
        $api->setFeedType($type);
        $api->perform();
        $type = $api::PRICING_FEED;
        $api->setFeedType($type);
        $api->perform();
    }
}
?>
