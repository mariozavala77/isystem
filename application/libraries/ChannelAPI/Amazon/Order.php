<?php

class ChannelAPI_Amazon_Order {

    private $_options = null;
    private $_url = null;
    private $_report_type = null;
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

    /**
     * 构造订单API
     *
     * @param: $options array API基本参数
     *
     * return void
     */
    public function __construct($options)
    {
        $this->_options = $options;
    }

    /**
     * 同步订单
     *
     * amazon同步订单获取方式：
     * 1,先获取最近1.5天的所有订单报告，取得订单的ID和订单中产品数据
     * 2,在通过获取订单列表得到订单相信信息以及状态
     * 这样做可以省去请求订单下产品的请求数
     *
     *
     * return array
     */
    public function sync() 
    {
        $orders = $this->_getData(); // 36小时内产生的订单
        $orders = array_merge($orders, $this->_getUnshiped());

        $options = $this->_options;
        $options['Version'] = '2011-01-01';
        $this->_url = $options['Server'] . 'Orders/' . $options['Version'];

        if( !$options['LastUpdatedAt'] ) return false;
        $options['LastUpdatedAfter'] = date('c', strtotime($options['LastUpdatedAt']));
        $options['Action'] = 'ListOrders';
        $options['OrderStatus.Status.1'] = 'Pending';
        $options['OrderStatus.Status.2'] = 'Unshipped';
        $options['OrderStatus.Status.3'] = 'PartiallyShipped';
        $options['OrderStatus.Status.4'] = 'Shipped';
        $options['OrderStatus.Status.5'] = 'Canceled';
        $options['OrderStatus.Status.6'] = 'Unfulfillable';
        unset($options['LastUpdatedAt'], $options['Server']);
        
        $data = $this->_getOrders($options); // 更新的订单
        foreach($data as $datum) {

            // 地址处理，多个地址用|分割
            $address = isset($datum['ShippingAddress']['AddressLine3']) ? $datum['ShippingAddress']['AddressLine3'] . '|' : '';
            $address .= isset($datum['ShippingAddress']['AddressLine2']) ? $datum['ShippingAddress']['AddressLine2'] . '|' : '';
            $address .= isset($datum['ShippingAddress']['AddressLine1']) ? $datum['ShippingAddress']['AddressLine1'] : '';

            $order = [
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

            
            if(isset($orders[$order['entity_id']])) {
                $orders[$order['entity_id']] = array_merge($orders[$order['entity_id']], $order);
            } else {
                $orders[$order['entity_id']] = $order;
            }

            // 删除panding订单
            if($orders[$order['entity_id']]['status'] == $this->_status['Pending']) {
                unset($orders[$order['entity_id']]);
            } 
        }

        return [ 'synced_at' => $this->_last_updated, 'data' => $orders ];
    }

    /**
     * 未处理订单
     *
     * return array
     */
    private function _getUnshiped()
    {
        $options = $this->_options;
        $options['Version'] = '2009-01-01';
        $this->_url = $options['Server'];
        unset($options['Server']);
        $data = [];

        // 数据订单
        $this->_report_type = '_GET_FLAT_FILE_ACTIONABLE_ORDER_DATA_';
        $requestId = $this->_request();
        if($requestId) {
            $reportId = $this->_getReport($requestId);
            if($reportId) {
                $data = array_merge($data, $this->_getReportData($reportId));
            }
        }

        return $data;
    
    }

    /**
     * 获取36小时内订单数据
     *
     * return array
     */
    private function _getData()
    {

        $options = $this->_options;
        $options['Version'] = '2009-01-01';
        $this->_url = $options['Server'];
        unset($options['LastUpdatedAt'], $options['Server']);

        $data = [];

        // 数据订单
        $this->_report_type = '_GET_FLAT_FILE_ORDERS_DATA_';
        $requestId = $this->_request();
        if($requestId) {
            $reportId = $this->_getReport($requestId);
            if($reportId) {
                $data = array_merge($data, $this->_getReportData($reportId));
            }
        }

        return $data;
    }

    /**
     * 获取订单报告数据
     *
     * @param: $reportId integer 报告ID
     *
     * return  array
     */
    private function _getReportData($reportId)
    {
        $options = $this->_options;
        $options['Action'] = 'GetReport';
        $options['ReportId'] = $reportId;

        $params = $this->_getParam($options);
        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();

        if($data['httpcode'] == 200) {
            $data = $this->_formatData($data['data']);
        }
        
        return $data;
    }

    /**
     * 处理报告数据
     *
     * @param: $string string 报告文本
     *
     * return array
     */
    public function _formatData($string)
    {
        $rows = explode("\n", $string);
        $array = [];
        foreach($rows as $index => $row) {
            $array[$index] = explode("\t", $row);
        }

        array_shift($array);

        $data = [];
        foreach($array as $key => $value) {
            if($value[0] != '') {
                if(isset($data[$value[0]])) {
                    if($this->_report_type == '_GET_FLAT_FILE_ORDERS_DATA_') {
                        $data[$value[0]] = [
                            'entity_id' => $value[0],
                            'items'     => [
                                    [
                                        'entity_id'      => $value[1],
                                        'sku'            => $value[7],
                                        'quantity'       => $value[9],
                                        'price'          => $value[11],
                                        'shipping_price' => $value[13],
                                    ] 
                                ],
                            ];
                    } elseif ($this->_report_type == '_GET_FLAT_FILE_ACTIONABLE_ORDER_DATA_') {
                        $data[$value[0]] = [
                            'entity_id' => $value[0],
                            'items'     => [
                                    [
                                        'entity_id'      => $value[1],
                                        'sku'            => $value[10],
                                        'quantity'       => $value[12],
                                        'price'          => 0,
                                        'shipping_price' => 0,
                                    ]
                                ],
                            ];
                    }
                } else {
                    if($this->_report_type == '_GET_FLAT_FILE_ORDERS_DATA_') {
                        $data[$value[0]]['items'][] = [
                                        'entity_id'      => $value[1],
                                        'sku'            => $value[7],
                                        'quantity'       => $value[9],
                                        'price'          => $value[11],
                                        'shipping_price' => $value[13],
                                    ];
                    } elseif ($this->_report_type == '_GET_FLAT_FILE_ACTIONABLE_ORDER_DATA_') {
                        $data[$value[0]]['items'][] = [
                                        'entity_id'      => $value[1],
                                        'sku'            => $value[10],
                                        'quantity'       => $value[12],
                                        'price'          => 0,
                                        'shipping_price' => 0,
                                    ];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * 获取订单报告请求
     *
     * return integer
     */
    private function _request()
    {
        $options = $this->_options;
        $options['Action'] = 'RequestReport';
        $options['ReportType'] = $this->_report_type;
        $options['StartDate'] = date('c', strtotime('-36 hours'));

        $params = $this->_getParam($options);
        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();

        $requestId = 0;
        if($data['httpcode'] == 200) {
            $data = $this->_xml2Array($data['data']);
            if(isset($data['RequestReportResult']['ReportRequestInfo']['ReportRequestId']))
                $requestId = $data['RequestReportResult']['ReportRequestInfo']['ReportRequestId']; 
        }

        return $requestId;
    }

    /**
     * 获取报告ID
     *
     * @param: $requestId integer 请求ID
     *
     * return integer
     */
    private function _getReport($requestId)
    {
        sleep(60);
        $options = $this->_options;
        $options['Action'] = 'GetReportRequestList';
        $options['ReportRequestIdList.Id.1'] = $requestId;

        $params = $this->_getParam($options);
        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();
        $reportId = 0;
        if($data['httpcode'] == 200) {
            $data = $this->_xml2Array($data['data']);
            $status = '_SUBMITTED_';
            if(isset($data['GetReportRequestListResult']['ReportRequestInfo']['ReportProcessingStatus'])) {
                $status = $data['GetReportRequestListResult']['ReportRequestInfo']['ReportProcessingStatus'];
                if($status == '_SUBMITTED_' || $status == '_IN_PROGRESS_') {
                    $reportId = $this->_getReport($requestId, $options);
                } elseif($status == '_DONE_') {
                    $reportId = $data['GetReportRequestListResult']['ReportRequestInfo']['GeneratedReportId']; 
                }
            }
        }

        return $reportId;
    }

    /**
     * order ship
     *
     * @param: $params array 发货参数
     *
     * return void
     */
    public function ship($params)
    {
        $url = parse_url($this->_url);
        $this->_options['Server']= $url['scheme'] . '://'.$url['host']. '/';
        $api = new ChannelAPI_Amazon_Feed();
        $api->setParams($this->_options, $params);
        $type = $api::ORDER_FULFILLMENT_FEED; // 这里需修改
        $api->setFeedType($type);
        $api->perform();
    }

    /**
     * order cancel
     *
     * @param: $params array 取消订单参数
     *
     * return void
     */
    public function cancel($params)
    {
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
    private function _getOrdersByNextToken($nexttoken)
    {
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
    private function _getOrders($options)
    {

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
     * 获取API参数
     *
     * @param: $options array 参数
     *
     * return array 转换后参数
     */
    private function _getParam($options)
    {
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
    private function _xml2Array($xml)
    {
        return json_decode(json_encode((array) simplexml_load_string($xml)), 1);
    }
}
?>
