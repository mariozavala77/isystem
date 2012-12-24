<?php
/**
 * 订单xml模板
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Order.php  2012年12月14日 星期五 10时50分39秒Z $
 */
trait Order
{
    /**
     * 取消订单XML
     *
     * return string 
     */
    public function cancelXML($params)
    {
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
    public function shipXML($params)
    {
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
    private function _shipOrderXml(&$xml, &$message_id, $order)
    {
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
    private function _cancelOrderXml(&$xml, &$message_id, $order)
    {
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
}
