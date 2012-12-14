<?php

class ChannelAPI_Amazon_Order {

    private $_options = null;
    private $_url = null;
    private $_last_updated = null;  // 同步的结束时间
    private $_status = [
                        'Pending'       => 1,  // 待付款
                        'Unshipped'     => 2,  // 未发货
                        'Shipped'       => 3,  // 已发货
                        'unset'         => 4,  // 部分发货预留
                        'Canceled'      => 5,  // 已取消
                        'Unfulfillable' => 6,  // 无法处理
                        ];

    private $_shipping_level = [
            'Expedited' => 0,
            'NextDay'   => 1,
            'SecondDay' => 2,
            'Standard'  => 3,
        ];

    const SERVER_VERSION = '2011-01-01';

    /**
     * 构造订单API
     *
     * @param: $options array API基本参数
     *
     * return void
     */
    public function __construct($options) {
        $options['Version'] = self::SERVER_VERSION;
        $this->_url = $options['Server'] . 'Orders/' . self::SERVER_VERSION;
        unset($options['Server']);

        $this->_options = $options;
    }

    /**
     * 同步订单
     *
     * @param: $options array 附加参数
     *
     * return array
     */
    public function sync($options) {
        $options = array_merge($this->_options, $options);

        if( !$options['LastUpdatedAt'] ) return false;
        $options['LastUpdatedAfter'] = date('c', strtotime($options['LastUpdatedAt']));
        $options['Action'] = 'ListOrders';
        $options['OrderStatus.Status.1'] = 'Unshipped';
        $options['OrderStatus.Status.2'] = 'PartiallyShipped';
        $options['OrderStatus.Status.3'] = 'Shipped';
        $options['OrderStatus.Status.4'] = 'Canceled';
        $options['OrderStatus.Status.5'] = 'Unfulfillable';
        unset($options['LastUpdatedAt']);
        
        $data = $this->_getOrders($options);
        $orders = [];
        foreach($data as $datum) {

            // 地址处理，多个地址用|分割
            $address = isset($datum['ShippingAddress']['AddressLine3']) ? $datum['ShippingAddress']['AddressLine3'] . '|' : '';
            $address .= isset($datum['ShippingAddress']['AddressLine2']) ? $datum['ShippingAddress']['AddressLine2'] . '|' : '';
            $address .= isset($datum['ShippingAddress']['AddressLine1']) ? $datum['ShippingAddress']['AddressLine1'] : '';

            $orders[] = [
                'entity_id'                => isset($datum['AmazonOrderId']) ? $datum['AmazonOrderId'] : '',
                'name'                     => isset($datum['BuyerName']) ? $datum['BuyerName'] : '',
                'email'                    => isset($datum['BuyerEmail']) ? $datum['BuyerEmail'] : '',
                'total_price'              => isset($datum['OrderTotal']['Amount']) ? $datum['OrderTotal']['Amount'] : '',
                'currency'                 => isset($datum['OrderTotal']['CurrencyCode']) ? $datum['OrderTotal']['CurrencyCode'] : '',
                'shipping_name'            => isset($datum['ShippingAddress']['Name']) ? $datum['ShippingAddress']['Name'] : '',
                'shipping_phone'           => isset($datum['ShippingAddress']['Phone']) ? $datum['ShippingAddress']['Phone'] : '',
                'shipping_address'         => trim($address, '|'),
                'shipping_city'            => isset($datum['ShippingAddress']['City']) ? $datum['ShippingAddress']['City'] : '',
                'shipping_state_or_region' => isset($datum['ShippingAddress']['StateOrRegion']) ? $datum['ShippingAddress']['StateOrRegion'] : '',
                'shipping_country'         => isset($datum['ShippingAddress']['CountryCode']) ? $datum['ShippingAddress']['CountryCode'] : '',
                'shipping_postcode'        => isset($datum['ShippingAddress']['PostalCode']) ? $datum['ShippingAddress']['PostalCode'] : '',
                'shipment_level'           => isset($datum['ShipmentServiceLevelCategory']) && in_array($datum['ShipmentServiceLevelCategory'], array_keys($this->_shipping_level)) ? $this->_shipping_level[$datum['ShipmentServiceLevelCategory']] : 4,
                'payment_method'           => isset($datum['PaymentMethod']) ? $datum['PaymentMethod'] : '',
                'status'                   => isset($datum['OrderStatus']) && in_array($datum['OrderStatus'], array_keys($this->_status)) ? $this->_status[$datum['OrderStatus']] : '',
                'is_auto'                  => isset($datum['FulfillmentChannel']) && $datum['FulfillmentChannel'] == 'AFN' ? 1 : 0, 
                'purchased_at'             => isset($datum['PurchaseDate']) ? $datum['PurchaseDate'] : '',
                'updated_at'               => isset($datum['LastUpdateDate']) ? $datum['LastUpdateDate'] : '',
                ];
        }

        return [ 'synced_at' => $this->_last_updated, 'data' => $orders ];
    }

    /**
     * 获取订单下产品
     *
     * @param: $options array 参数
     *
     * return array
     */
    public function items($options) {
        $options = array_merge($this->_options, $options);
        if(!$options['entity_id']) return false;
        $options['AmazonOrderId'] = $options['entity_id'];
        $options['Action'] = 'ListOrderItems';
        unset($options['entity_id']);

        $data = $this->_getItems($options);

        $items = [];
        foreach($data as $datum) {
            $items[] = [
                'entity_id'      => $datum['OrderItemId'],
                'sku'            => $datum['SellerSKU'],
                'quantity'       => $datum['QuantityOrdered'],
                'price'          => isset($datum['ItemPrice']['Amount']) ? $datum['ItemPrice']['Amount'] : 0,
                'shipping_price' => isset($datum['ShippingPrice']['Amount']) ? $datum['ShippingPrice']['Amount'] : 0,
                ];
        }

        return $items;
    }

    /**
     * order ship
     *
     * @param: $params array 发货参数
     *
     * return void
     */
    public function ship($params) {
        $url = parse_url($this->_url);
        $this->_options['Server']= $url['scheme'] . '://'.$url['host']. '/';
        $api = new ChannelAPI_Amazon_Feed();
        $api->setParams($this->_options, $params);
        $type = $api::ORDER_FULFILLMENT_FEED; // 这里需修改
        $api->setFeedType($type);            //|
        $api->perform();
    }

    /**
     * order cancel
     *
     * @param: $params array 取消订单参数
     *
     * return void
     */
    public function cancel($params) {
        $url = parse_url($this->_url);
        $this->_options['Server']= $url['scheme'] . '://'.$url['host']. '/';
        $api = new ChannelAPI_Amazon_Feed();
        $api->setParams($this->_options, $params);
        $type = $api::ORDER_ACKNOWLEDGEMENT_FEED;
        $api->setFeedType($type);
        $api->perform();
    }

    /**
     * 获取订单通过nexttoken
     *
     * @param: $nexttoken string nexttoken
     *
     * return array
     */
    private function _getOrdersByNextToken($nexttoken) {
        if( !$nexttoken ) return false;

        $options = $this->_options;
        $options['NextToken'] = $nexttoken;
        $options['Action'] = 'ListOrdersByNextToken';
        unset($options['MarketplaceId.Id.1'], $options['Server']);

        return $this->_getOrders($options);
    }


    /**
     * 获取订单
     *
     * @param: $options array API请求参数
     *
     * return array
     */
    private function _getOrders( $options ) {

        $params = $this->_getParam($options);

        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();

        $orders = [];
        if($data['httpcode'] == 200) {
            $data = $this->_xml2Array($data['data']);
            if( isset($data['ListOrdersResult']) || isset($data['ListOrdersByNextTokenResult']) ) {
                $result = isset($data['ListOrdersResult']) ? $data['ListOrdersResult'] : $data['ListOrdersByNextTokenResult'];
                if(isset($result['Orders']['Order'])) {
                    $orders = $result['Orders']['Order'];
                }

                if(isset($result['NextToken'])) {
                    $orders = array_merge($orders, $this->_getOrdersByNextToken($result['NextToken']));
                }

                $this->_last_updated = $result['LastUpdatedBefore'];
            }
        }

        return $orders;
    }

    /**
     * 获取产品
     *
     * @param: $options array API请求参数
     *
     * return array
     */
    private function _getItems($options) {
        $params = $this->_getParam($options);

        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();

        $items = [];
        if($data['httpcode'] == 200) {
            $data = $this->_xml2Array($data['data']);
            if(isset($data['ListOrderItemsResult'])) {
                $result = $data['ListOrderItemsResult'];
                if($result['OrderItems']['OrderItem']) {
                    $item = $result['OrderItems']['OrderItem'];
                    if(isset($item['OrderItemId'])) 
                        $items[0] = $item;
                    else 
                        $items = $item;
                }
            }
        } else {
            print_r($data);
            Throw new Amazon_Exception("抓取订单产品失败。[Entity ID:]{$options['AmazonOrderId']}");
        }

        return $items;
    }

    /**
     * 获取API参数
     *
     * @param: $options array 参数
     *
     * return array 转换后参数
     */
    private function _getParam( $options ) {
        $amazon = new Amazon();
        $amazon->setData($options, $this->_url);
        $data = $amazon->combine();

        $param = [
            'url'   => $this->_url,
            'query' => $data
            ];

        return $param;
    }


    /**
     * XMl内容转换成数组
     *
     * @param: $xml string xml内容
     *
     * return array
     */
    private function _xml2Array( $xml ) {
        return json_decode(json_encode((array) simplexml_load_string( $xml )), 1);
    }
}
?>
