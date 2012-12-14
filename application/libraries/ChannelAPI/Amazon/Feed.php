<?php

/**
 * Amazon Feed
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Feed.php  2012年11月21日 星期三 20时37分32秒Z $
 */
include __DIR__ . DS . 'FeedXml' . DS . 'Product.php';
include __DIR__ . DS . 'FeedXml' . DS . 'Order.php';

class ChannelAPI_Amazon_Feed {

    use Order, Product;

    const PRODUCT_FEED = '_POST_PRODUCT_DATA_';
    const RELATIONSHIPS_FEED = '_POST_PRODUCT_RELATIONSHIP_DATA_';
    const SINGLE_FORMAT_ITEM_FEED = '_POST_ITEM_DATA_';
    const SHIPPING_OVERRIDE_FEED = '_POST_PRODUCT_OVERRIDES_DATA_';
    const PRODUCT_IMAGES_FEED = '_POST_PRODUCT_IMAGE_DATA_';
    const PRICING_FEED = '_POST_PRODUCT_PRICING_DATA_';
    const INVENTORY_FEED = '_POST_INVENTORY_AVAILABILITY_DATA_';
    const ORDER_ACKNOWLEDGEMENT_FEED = '_POST_ORDER_ACKNOWLEDGEMENT_DATA_';
    const ORDER_FULFILLMENT_FEED = '_POST_ORDER_FULFILLMENT_DATA_';
    const FBA_SHIPMENT_INJECTION_FULFILLMENT_FEED = '_POST_FULFILLMENT_ORDER_REQUEST_DATA_';
    const FBA_SHIPMENT_INJECTION_CANCELLATION_FEED = '_POST_FULFILLMENT_ORDER_CANCELLATION_REQUEST_DATA_';
    const ORDER_ADJUSTMENT_FEED = '_POST_PAYMENT_ADJUSTMENT_DATA_';
    const FLATFILE_LISTINGS_FEED = '_POST_FLAT_FILE_LISTINGS_DATA_';
    const FLATFILE_ORDER_ACKNOWLEDGEMENT_FEED = '_POST_FLAT_FILE_ORDER_ACKNOWLEDGEMENT_DATA_';
    const FLATFILE_ORDER_FULFILLMENT_FEED = '_POST_FLAT_FILE_FULFILLMENT_DATA_';
    const FLATFILE_ORDER_ADJUSTMENT_FEED = '_POST_FLAT_FILE_PAYMENT_ADJUSTMENT_DATA_';
    const FLATFILE_INVENTORY_LOADER_FEED = '_POST_FLAT_FILE_INVLOADER_DATA_';
    const FLATFILE_MUSIC_LOADER_FILE = '_POST_FLAT_FILE_CONVERGENCE_LISTINGS_DATA_';
    const FLATFILE_BOOK_LOADER_FILE = '_POST_FLAT_FILE_BOOKLOADER_DATA_';
    const FLATFILE_PRICE_AND_QUANTITY_UPDATE_FILE = '_POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA_';
    const UIEE_INVENTORY_FILE = '_POST_UIEE_BOOKLOADER_DATA_';
    const SERVER_VERSION = '2009-01-01';

    private $_options;
    private $_params;

    public function __construct() {}

    /**
     * 设置请求参数
     *
     * @params: $options array 接口参数
     * @params: $params  array 需要提交的数据
     *
     * return void
     */
    public function setParams($options, $params)
    {
        $this->_options = $options;
        $this->_params  = $params;
        $this->_options['Action'] = 'SubmitFeed';
        $this->_options['Version'] = self::SERVER_VERSION;
        $this->_options['PurgeAndReplace'] = 'false';
    }

    /**
     * 设置feed类型
     *
     * @param: $type string 类型
     *
     * return void
     */
    public function setFeedType($type)
    {
        $this->_options['FeedType'] = $type;
    }

    /**
     * 提交修改请求
     */
    public function perform()
    {
        $params = $this->_getParams($this->_options);

        $filename = $this->_feedfile();

        if($filename) {
            $params['content_md5'] = base64_encode(md5_file($filename, true));
            $params['filename'] = $filename;
            $curl = new Amazon_Curl();
            $curl->submitFeed($params); // 提交这里不用检查了，同步订单会更新状态
        }
    }

    /**
     * 生成xml文件
     *
     * return string
     */
    private function _feedfile()
    {
        switch ($this->_options['FeedType']) {
            case '_POST_ORDER_FULFILLMENT_DATA_':
                $filename = $this->shipXML();
                break;
            case '_POST_ORDER_ACKNOWLEDGEMENT_DATA_':
                $filename = $this->cancelXML();
                break;
            case '_POST_PRODUCT_DATA_':
                $filename = $this->listingInfoXML();
                break;
            case '_POST_PRODUCT_IMAGE_DATA_':
                $filename = $this->listingImageXML();
                break;
            case '_POST_INVENTORY_AVAILABILITY_DATA_':
                $filename = $this->listingStockXML();
                break;
            case '_POST_PRODUCT_PRICING_DATA_':
                $filename = $this->listingPriceXML();
                break;
            default:
                $filename = '';
                break;
        }

        return $filename;
    }

    /**
     * 获取amazon请求参数
     *
     * @param: $options array 接口参数
     *
     * return array
     */
    private function _getParams($options) {
        $amazon = new Amazon();
        $url = $options['Server'];
        unset($options['Server']);

        $amazon->setData($options, $url);
        $param = $amazon->combine();

        $params  = [
               'url' => $url,
               'query' => $param,
            ];

        return $params;
    }


}
?>
