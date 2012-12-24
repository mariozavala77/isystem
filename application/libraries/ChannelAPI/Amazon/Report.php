<?php
/**
 * Amazon Reports
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class ChannelAPI_Amazon_Report {
    // 商品报告
    const FLAT_FILE_OPEN_LISTINGS = '_GET_FLAT_FILE_OPEN_LISTINGS_DATA_';
    const MERCHANT_LISTINGS_BACK_COMPAT = '_GET_MERCHANT_LISTINGS_DATA_BACK_COMPAT_';
    const MERCHANT_LISTINGS = '_GET_MERCHANT_LISTINGS_DATA_';
    const MERCHANT_LISTINGS_LITE = '_GET_MERCHANT_LISTINGS_DATA_LITE_';
    const MERCHANT_LISTINGS_LITER = '_GET_MERCHANT_LISTINGS_DATA_LITER_';
    const CONVERGED_FLAT_FILE_SOLD_LISTINGS = '_GET_CONVERGED_FLAT_FILE_SOLD_LISTINGS_DATA_';
    const MERCHANT_CANCELLED_LISTINGS = '_GET_MERCHANT_CANCELLED_LISTINGS_DATA_';
    const MERCHANT_LISTINGS_DEFECT = '_GET_MERCHANT_LISTINGS_DEFECT_DATA_';
    // 订单报告
    const FLAT_FILE_ACTIONABLE_ORDER = '_GET_FLAT_FILE_ACTIONABLE_ORDER_DATA_';
    const ORDERS = '_GET_ORDERS_DATA_';
    const FLAT_FILE_ORDER_REPORT = '_GET_FLAT_FILE_ORDER_REPORT_DATA_';
    const FLAT_FILE_ORDERS = '_GET_FLAT_FILE_ORDERS_DATA_';
    const CONVERGED_FLAT_FILE_ORDER_REPORT = '_GET_CONVERGED_FLAT_FILE_ORDER_REPORT_DATA_';
    // 等待中订单报告
    const FLAT_FILE_PENDING_ORDERS = '_GET_FLAT_FILE_PENDING_ORDERS_DATA_';
    const PENDING_ORDERS = '_GET_PENDING_ORDERS_DATA_';
    // 业绩报告
    const SELLER_FEEDBACK = '_GET_SELLER_FEEDBACK_DATA_';
    // 结算报告
    const FLAT_FILE_PAYMENT_SETTLEMENT = '_GET_FLAT_FILE_PAYMENT_SETTLEMENT_DATA_';
    const PAYMENT_SETTLEMENT = '_GET_PAYMENT_SETTLEMENT_DATA_';
    const ALT_FLAT_FILE_PAYMENT_SETTLEMENT = '_GET_ALT_FLAT_FILE_PAYMENT_SETTLEMENT_DATA_';
    // 亚马逊物流报告
    const FILE_ALL_ORDERS_BY_LAST_UPDATE = '_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_LAST_UPDATE_';
    const FILE_ALL_ORDERS_DATA_BY_ORDER = '_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_ORDER_DATE_';
    const XML_ALL_ORDERS_BY_LAST_UPDATE = '_GET_XML_ALL_ORDERS_DATA_BY_LAST_UPDATE_';
    const XML_ALL_ORDERS_BY_ORDER_DATE = '_GET_XML_ALL_ORDERS_DATA_BY_ORDER_DATE_';
    const AFN_INVENTORY = '_GET_AFN_INVENTORY_DATA_';
    const AMAZON_FULFILLED_SHIPMENTS = '_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_';
    const CUSTOMER_RETURNS = '_GET_FBA_FULFILLMENT_CUSTOMER_RETURNS_';
    const CUSTOMER_SHIPMENT_SALES = '_GET_FBA_FULFILLMENT_CUSTOMER_SHIPMENT_SALES_DATA_';
    const CUSTOMER_TAXES = '_GET_FBA_FULFILLMENT_CUSTOMER_TAXES_DATA_';
    const SHIPMENT_PROMOTION = '_GET_FBA_FULFILLMENT_CUSTOMER_SHIPMENT_PROMOTION_DATA_';
    const INBOUND_NONCOMPLIANCE = '_GET_FBA_FULFILLMENT_INBOUND_NONCOMPLIANCE_DATA_';
    const CURRENT_INVENTORY = '_GET_FBA_FULFILLMENT_CURRENT_INVENTORY_DATA_';
    const MONTHLY_INVENTORY = '_GET_FBA_FULFILLMENT_MONTHLY_INVENTORY_DATA_';
    const INVENTORY_RECEIPTS = '_GET_FBA_FULFILLMENT_INVENTORY_RECEIPTS_DATA_';
    const INVENTORY_SUMMARY = '_GET_FBA_FULFILLMENT_INVENTORY_SUMMARY_DATA_';
    const INVENTORY_ADJUSTMENTS = '_GET_FBA_FULFILLMENT_INVENTORY_ADJUSTMENTS_DATA_';
    const INVENTORY_HEALTH = '_GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA_';
    const UNSUPPRESSED_INVENTORY = '_GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_';
    const ALL_INVENTORY = '_GET_FBA_MYI_ALL_INVENTORY_DATA_';
    const CUSTOMER_SHIPMENT_REPLACEMENT = '_GET_FBA_FULFILLMENT_CUSTOMER_SHIPMENT_REPLACEMENT_DATA_';
    const CROSS_BORDER_INVENTORY_MOVEMENT = '_GET_FBA_FULFILLMENT_CROSS_BORDER_INVENTORY_MOVEMENT_DATA_';
    const RECOMMENDED_REMOVAL = '_GET_FBA_RECOMMENDED_REMOVAL_DATA_';
    // 商品广告
    const NEMO_MERCHANT_LISTINGS = '_GET_NEMO_MERCHANT_LISTINGS_DATA_';
    const PERFORMANCE_DAILY_TSV = '_GET_PADS_PRODUCT_PERFORMANCE_OVER_TIME_DAILY_DATA_TSV_';
    const PERFORMANCE_DAILY_XML = '_GET_PADS_PRODUCT_PERFORMANCE_OVER_TIME_DAILY_DATA_XML_';
    const PERFORMANCE_WEEKLY_TSV = '_GET_PADS_PRODUCT_PERFORMANCE_OVER_TIME_WEEKLY_DATA_TSV_';
    const PERFORMANCE_WEEKLY_XML = '_GET_PADS_PRODUCT_PERFORMANCE_OVER_TIME_WEEKLY_DATA_XML_';
    const PERFORMANCE_MONTHLY_TSV = '_GET_PADS_PRODUCT_PERFORMANCE_OVER_TIME_MONTHLY_DATA_TSV_';
    const PERFORMANCE_MONTHLY_XML = '_GET_PADS_PRODUCT_PERFORMANCE_OVER_TIME_MONTHLY_DATA_XML_';

    const SERVER_VERSION = '2009-01-01';

    private $_options = null;
    private $_url = null;
    // 临时存储 请求报告的参数
    private $_report = null;

    public function __construct($options)
    {
        $options['Version'] = self::SERVER_VERSION;
        $this->_url = $options['Server'];
        unset($options['Server']);
        $this->_options = $options;
    }

    /**
     * 创建报告请求
     *
     * @param: $params array 参数
     *
     * return array 请求后的内容
     */
    public function RequestReport($params)
    {
        $params['Action'] = 'RequestReport';
        $this->_report = $params;
        $params = array_merge($params, $this->_options);
        $data = $this->_handle($params);
        if (!empty($data)) 
        {
            $data = $this->_xml2Array($data);
            $data = $data['RequestReportResult']['ReportRequestInfo']['ReportRequestId'];
        }

        return $data;
    }

    /**
     * 获取报告的ReportRequestId的报告请求列表
     *
     * @param: $params array 参数
     *
     * return array 请求后的内容
     */
    public function GetReportRequestList($params)
    {
        $params['Action'] = 'GetReportRequestList';
        $params = array_merge($params, $this->_options);
        $data =  $this->_handle($params);
        if(empty($data)){
            return $data;
        }
        $data = $this->_xml2Array($data);
        $data = $data['GetReportRequestListResult'];
        $data = $data['ReportRequestInfo'];
        $Status = $data['ReportProcessingStatus'];
        if ($Status == '_SUBMITTED_' || $Status == '_IN_PROGRESS_')
        {
            sleep(50);
            return $this->GetReportRequestList($params);
        }
        elseif ($Status=='_CANCELLED_')
        {
            //return $this->_restart($params);
            return false;
        }
        elseif ($Status=='_DONE_NO_DATA_')
        {
            return false;
        }
        else
        {
            if(empty($data['GeneratedReportId']))
            {
                return $this->_restart($params);
            }
            else
            {
                return $data['GeneratedReportId']; 
            }
        }
    }

    /**
     * 获取报告内容及所返回报告正文的 Content-MD5 标头
     *
     * @param: $params array 参数
     *
     * return array 请求后的内容
     */
    public function GetReport($params)
    {
        $params['Action'] = 'GetReport';
        $params = array_merge($params, $this->_options);
        return $this->_handle($params);
    }

    /**
     * 获取API参数
     *
     * @param: $options array 参数
     *
     * return array 转换后参数
     */
    private function _getParam( $params ) 
    {
        $amazon = new Amazon();
        $amazon->setData($params, $this->_url);
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
    private function _xml2Array( $xml ) 
    {
        return json_decode(json_encode((array) simplexml_load_string( $xml )), 1);
    }

    /**
     * 执行请求函数
     *
     * @param: $params array xml内容
     *
     * return array
     */
    private function _handle($params)
    {
        $curl = new Amazon_Curl();
        $curl -> setParam($this->_getParam( $params ));
        $data = $curl->perform();


        if($data['httpcode']==200){
            return $data['data'];
        }else{
            return false;
        }
    }

    /**
     * 重新启动获取 报告
     */
    private function _restart($params)
    {
        $ReportRequestId = $this->RequestReport($this->_report);

        if (!empty($ReportRequestId))
        {
            return $this->GetReportRequestList($params);
        }else{
            /*sleep(65);
            return $this->_restart($params);*/
            return false;
        }
    }
}