<?php

/**
 * Amazon Feed
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Feed.php  2012年11月21日 星期三 20时37分32秒Z $
 */

class ChannelAPI_Amazon_Feed {

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

    public function __construct() {
    }

    /**
     * 设置请求参数
     *
     * @params: $options array 接口参数
     * @params: $params  array 需要提交的数据
     *
     * return void
     */
    public function setParams($options, $params) {
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
    public function setFeedType($type) {
        $this->_options['FeedType'] = $type;
    }

    /**
     * 提交修改请求
     */
    public function perform() {
        $params = $this->_getParams($this->_options);

        // 暂时这样写
        $filename = '';
        if($this->_options['FeedType'] == '_POST_ORDER_FULFILLMENT_DATA_') {
            $filename = $this->_shipXML();
            $params['content_md5'] = base64_encode(md5_file($filename, true));
            $params['filename'] = $filename;
        } elseif($this->_options['FeedType'] == '_POST_ORDER_ACKNOWLEDGEMENT_DATA_') {
            $filename = $this->_cancelXML();
            $params['content_md5'] = base64_encode(md5_file($filename, true));
            $params['filename'] = $filename;
        }

        if($filename) {
            $curl = new Amazon_Curl();
            $curl->submitFeed($params); // 提交这里不用检查了，同步订单会更新状态
        }
    }

    /**
     * 取消订单XML
     *
     * return string 
     */
    private function _cancelXML() {
        $params = $this->_params;
        $filename = path('feed_xml') . 'cancel' . date('Ymd_H_i_s') . '.xml';

        $xml = new XMLWriter();
        $xml->openUri($filename);
        $xml->setIndentString('    ');
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('AmazonEnvelope');
        $xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->writeAttribute('xsi:noNamespaceSchemaLocation', 'amzn-envelope.xsd');
        $xml->startElement('Header');
        $xml->startElement('DocumentVersion');
        $xml->text('1.01');
        $xml->endElement();
        $xml->startElement('MerchantIdentifier');
        $xml->text($this->_options['MarketplaceId.Id.1']);
        $xml->endElement();
        $xml->endElement();
        $xml->startElement('MessageType');
        $xml->text('OrderAcknowledgement');
        $xml->endElement();
        $message_id = 0;
        foreach($params as $key => $param) {
            $order = unserialize($param->params)['params'];
            $this->_cancelOrderXML($xml, $message_id, $order);
        }
        $xml->endElement();
        $xml->endDocument();

        return $filename;
    }

    /**
     * 确认订单XML
     *
     * return string
     */
    private function _shipXML() {
        $params = $this->_params;
        $filename = path('feed_xml') . 'ship_' . date('Ymd_H_i_s') . '.xml';
        $xml = new XMLWriter();
        $xml->openUri($filename);
        $xml->setIndentString('    ');
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('AmazonEnvelope');
        $xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->writeAttribute('xsi:noNamespaceSchemaLocation', 'amzn-envelope.xsd');
        $xml->startElement('Header');
        $xml->startElement('DocumentVersion');
        $xml->text('1.01');
        $xml->endElement();
        $xml->startElement('MerchantIdentifier');
        $xml->text($this->_options['MarketplaceId.Id.1']);
        $xml->endElement();
        $xml->endElement();
        $xml->startElement('MessageType');
        $xml->text('OrderFulfillment');
        $xml->endElement();
        $message_id = 0;
        foreach($params as $key => $param) {
            $order = unserialize($param->params)['params'];
            $this->_shipOrderXml($xml, $message_id, $order);
        }
        $xml->endElement();
        $xml->endDocument();

        return $filename;
    }

    /**
     * 生成ship order 部分
     *
     * @param: &$xml       object  xml
     * @param: $message_id integer 消息ID
     * @param: $order      array   单个订单数据
     *
     */
    private function _shipOrderXml(& $xml, & $message_id, $order) {
        $company = '';
        $datetime = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
        $order_id = $order['order_id'];
        foreach($order['items'] as $key => $item) {
            if($company == '' ||  $company != $item->company) {
                if($company) {
                    $xml->endElement();
                    $xml->endElement();
                }
                $message_id ++;
                $xml->startElement('Message');
                $xml->startElement('MessageID');
                $xml->text($message_id);
                $xml->endElement();
                $xml->startElement('OrderFulfillment');
                $xml->startElement('AmazonOrderID');
                $xml->text($order_id);
                $xml->endElement();
                $xml->startElement('FulfillmentDate');
                $xml->text($datetime);
                $xml->endElement();
                $xml->startElement('FulfillmentData');
                $xml->startElement('CarrierCode');
                $xml->text($item->company);
                $xml->endElement();
                $xml->startElement('ShippingMethod');
                $xml->text($item->method);
                $xml->endElement();
                $xml->startElement('ShipperTrackingNumber');
                $xml->text($item->tracking_no);
                $xml->endElement();
                $xml->endElement();
            }

            $xml->startElement('Item'); // item begins
            $xml->startElement('AmazonOrderItemCode');
            $xml->text($item->entity_id);
            $xml->endElement();
            $xml->startElement('Quantity');
            $xml->text($item->quantity);
            $xml->endElement();
            $xml->endElement();        // item ends
            $company = $item->company;
        }

        $xml->endElement();
        $xml->endElement();
    }

    /**
     * 生成cancel order 部分
     *
     * @param: &$xml       object  xml
     * @param: $message_id integer 消息ID
     * @param: $order      array   单个订单数据
     *
     */
    private function _cancelOrderXml(& $xml, & $message_id, $order) {
        $datetime = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
        $order_id = $order['order_id'];
        $ids = [];
        foreach($order['items'] as $key => $item) {
            if(!in_array($order_id, $ids)) {
                if(!empty($ids)) {
                    $xml->endElement();
                    $xml->endElement();
                }
                $ids[] = $order_id;
                $message_id ++;
                $xml->startElement('Message');
                $xml->startElement('MessageID');
                $xml->text($message_id);
                $xml->endElement();
                $xml->startElement('OrderAcknowledgement');
                $xml->startElement('AmazonOrderID');
                $xml->text($order_id);
                $xml->endElement();
                $xml->startElement('StatusCode');
                $xml->text('Failure');
                $xml->endElement();
            }

            $xml->startElement('Item'); // item begins
            $xml->startElement('AmazonOrderItemCode');
            $xml->text($item->entity_id);
            $xml->endElement();
            $xml->startElement('CancelReason');
            $xml->text('BuyerCanceled');
            $xml->endElement();
            $xml->endElement();        // item ends
        }

        $xml->endElement();
        $xml->endElement();
    }

    /**
     * 获取amazon请求参数
     *
     * @param: $options array 接口参数
     *
     * return array
     */
    private function _getParams($options) {
        print_r($options);
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
