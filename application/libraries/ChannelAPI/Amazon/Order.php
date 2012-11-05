<?php

class ChannelAPI_Amazon_Order {

    private $_options = null;
    private $_last_updated = null;  // 同步的结束时间
    private $_status = [
                        'Pending'       => 1,  // 待付款
                        'Unshipped'     => 2,  // 未发货
                        'Shipped'       => 3,  // 已发货
                        'Canceled'      => 4,  // 已取消
                        'Unfulfillable' => 5,  // 无法处理
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
        $options['Server']  = $options['Server'] . 'Orders/' . self::SERVER_VERSION;
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
        unset($options['LastUpdatedAt'], $options['Server']);
        
        $data = $this->_getOrders($options);
        $new_data = [];
        foreach($data as $datum) {

            // 地址处理，多个地址用|分割
            $address = isset($datum['ShippingAddress']['AddressLine3']) ? $datum['ShippingAddress']['AddressLine3'] . '|' : '';
            $address .= isset($datum['ShippingAddress']['AddressLine2']) ? $datum['ShippingAddress']['AddressLine2'] . '|' : '';
            $address .= isset($datum['ShippingAddress']['AddressLine1']) ? $datum['ShippingAddress']['AddressLine1'] : '';

            $new_data[] = [
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
                'auto'                     => isset($datum['FulfillmentChannel']) && $datum['FulfillmentChannel'] == 'AFN' ? 1 : 0, 
                'purchased_at'             => isset($datum['PurchaseDate']) ? $datum['PurchaseDate'] : '',
                'updated_at'               => isset($datum['LastUpdateDate']) ? $datum['LastUpdateDate'] : '',
                ];
        }

        return [ 'synced_at' => $this->_last_updated, 'data' => $new_data ];
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
     * $options array API请求参数
     *
     * return array
     */
    private function _getOrders( $options ) {
        $param = $this->_getParam($options);

        $curl = new ChannelAPI_Amazon_Libs_Curl();
        $curl->setParam($param);
        $data = $curl->perform();

        $orders = [];
        if($data['httpcode'] == 200) {
            $data = $this->_xml2Array($data['data']);

            // 订单
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
     * 获取API参数
     *
     * @param: $options array 参数
     *
     * return array 转换后参数
     */
    private function _getParam( $options ) {
        $amazon = new ChannelAPI_Amazon_Libs_Amazon();
        $amazon->setData($options, $this->_options['Server']);
        $data = $amazon->combine();

        $param = [
            'url'   => $this->_options['Server'],
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
