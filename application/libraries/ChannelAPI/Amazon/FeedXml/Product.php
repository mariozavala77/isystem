<?php
/**
 * 产品xml模板
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Order.php  2012年12月14日 星期五 10时50分39秒Z $
 */
trait Product
{
    /**
     * 产品信息上架XML
     *
     * return string 
     */
    public function listingInfoXML()
    {
        $params = $this->_params;
        $filename = path('feed_xml') . 'listing_info' . date('Ymd_H_i_s') . '.xml';

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
        $xml->text('Product');
        $xml->endElement();
        $message_id = 0;
        foreach($params as $key => $param) {
            $product = unserialize($param->params)['params'];
            $this->_listingInfosXML($xml, $message_id, $product);
        }
        $xml->endDocument();

        return $filename;
    }

    /**
     * 生成listing product 信息部分
     *
     * @param: &$xml       object  xml
     * @param: $message_id integer 消息ID
     * @param: $product    array   单个产品数据
     *
     */
    private function _listingInfosXml(&$xml, &$message_id, $product)
    {
        $message_id ++;
        $xml->startElement('Message');
        $xml->startElement('MessageID');
        $xml->text($message_id);
        $xml->endElement();
        $xml->startElement('OperationType');
        $xml->text('Update');
        $xml->endElement();
        $xml->startElement('Product');
        $xml->startElement('SKU');
        $xml->text($product['sku']);
        $xml->endElement();
        $xml->startElement('ProductTaxCode');
        $xml->text('A_GEN_TAX');
        $xml->endElement();
        $xml->startElement('Condition');
        $xml->startElement('ConditionType');
        $xml->text('New');
        $xml->endElement();
        $xml->endElement();
        $xml->startElement('DescriptionData'); // description_data begins
        $xml->startElement('Title');
        $xml->text('<![CDATA[' . $product['title'] . ']]>');
        $xml->endElement();
        $xml->startElement('Description');
        $xml->text('<![CDATA[' . $product['description'] . ']]>');
        $xml->endElement();
        $xml->endElement();                    // description_data ends
        $xml->startElement('ProductData');
        $xml->startElement('Miscellaneous');
        $xml->startElement('ProductType');
        $xml->text('Misc_Other');
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
    }

    /**
     * 产品图片上架XML
     *
     * return string 
     */
    public function listingImageXML()
    {
        $params = $this->_params;
        $filename = path('feed_xml') . 'listing_image' . date('Ymd_H_i_s') . '.xml';

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
        $xml->text('ProductImage');
        $xml->endElement();
        $message_id = 0;
        foreach($params as $key => $param) {
            $product = unserialize($param->params)['params'];
            $this->_listingImagesXML($xml, $message_id, $product);
        }
        $xml->endDocument();

        return $filename;
    }

    /**
     * 图片xml数据
     *
     * @param: &$xml        object  xml数据
     * @param: &$message_id integer feed消息序号
     * @param: $product     array   产品信息
     *
     * return void
     */
    private function _listingImagesXML(&$xml, &$message_id, $product)
    {
        foreach($product['images'] as $image) {
            $message_id ++;
            $xml->startElement('Message');
            $xml->startElement('MessageID');
            $xml->text($message_id);
            $xml->endElement();
            $xml->startElement('OperationType');
            $xml->text('Update');
            $xml->endElement();
            $xml->startElement('ProductImage');
            $xml->startElement('SKU');
            $xml->text($product['sku']);
            $xml->endElement();
            $xml->startElement('ImageType');
            $xml->text('Main');
            $xml->endElement();
            $xml->startElement('ImageLocation');
            $xml->text(UploadHelper::path(Config::get('application.url').'uploads/images/products/', $image->image, true));
            $xml->endElement();
            $xml->endElement();
            $xml->endElement();
        }
    }

    /**
     * 产品库存
     *
     */
    public function listingStockXML()
    {
        $params = $this->_params;
        $filename = path('feed_xml') . 'listing_stock' . date('Ymd_H_i_s') . '.xml';

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
        $xml->text('Inventory');
        $xml->endElement();
        $message_id = 0;
        foreach($params as $key => $param) {
            $product = unserialize($param->params)['params'];
            $this->_listingStocksXML($xml, $message_id, $product);
        }
        $xml->endDocument();

        return $filename;
    }

    /**
     * 库存xml数据
     *
     * @param: &$xml        object  xml数据
     * @param: &$message_id integer feed消息序号
     * @param: $product     array   产品信息
     *
     * return void
     */
    private function _listingStocksXML(&$xml, &$message_id, $product)
    {
        $message_id ++;
        $xml->startElement('Message');
        $xml->startElement('MessageID');
        $xml->text($message_id);
        $xml->endElement();
        $xml->startElement('OperationType');
        $xml->text('Update');
        $xml->endElement();
        $xml->startElement('Inventory');
        $xml->startElement('SKU');
        $xml->text($product['sku']);
        $xml->endElement();
        $xml->startElement('Quantity');
        $xml->text('888');  // 默认上架库存888件
        $xml->endElement();
        $xml->startElement('FulfillmentLatency');
        $xml->text('1');
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
    }

    /**
     * 产品价格
     *
     */
    public function listingPriceXML()
    {
        $params = $this->_params;
        $filename = path('feed_xml') . 'listing_price' . date('Ymd_H_i_s') . '.xml';

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
        $xml->text('Price');
        $xml->endElement();
        $message_id = 0;
        foreach($params as $key => $param) {
            $product = unserialize($param->params)['params'];
            $this->_listingPricesXML($xml, $message_id, $product);
        }
        $xml->endDocument();

        return $filename;
    }

    /**
     * 价格xml数据
     *
     * @param: &$xml        object  xml数据
     * @param: &$message_id integer feed消息序号
     * @param: $product     array   产品信息
     *
     * return void
     */
    private function _listingPricesXML(&$xml, &$message_id, $product)
    {
        $message_id ++;
        $xml->startElement('Message');
        $xml->startElement('MessageID');
        $xml->text($message_id);
        $xml->endElement();
        $xml->startElement('OperationType');
        $xml->text('Update');
        $xml->endElement();
        $xml->startElement('Price');
        $xml->startElement('SKU');
        $xml->text($product['sku']);
        $xml->endElement();
        $xml->startElement('StandardPrice');
        $xml->writeAttribute('currency', 'USD');
        $xml->text($product['price']);
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
    }
}
