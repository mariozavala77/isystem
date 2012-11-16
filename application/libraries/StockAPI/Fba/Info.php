<?php

class StockAPI_Fba_Info {

    const SERVER_VERSION = '2009-01-01';
    const SLEEPTIME = '300';  // 单位秒

    private $_url = null;
    private $_options = [];

    /**
     * 构造FBA同步类
     *
     * @param: $options array API基本参数
     *
     * return void
     */
    public function __construct($options) {
        $options['ReportType'] = '_GET_AFN_INVENTORY_DATA_';
        $options['Version']    = self::SERVER_VERSION;
        $options['Action']     = 'RequestReport'; 

        $this->_url = $options['Server'];
        unset($options['Server']);

        $this->_options = $options;
    }

    /**
     * 获取库存信息
     *
     * return array
     */
    public function info() {
        $infos = [];
        $infos = $this->_getInfo('8261538963');
        return $infos;die;
        $request_id = $this->_getRequestId();

        if($request_id) {
            $report_id = $this->_getReportId($request_id);
            if($report_id) {
                $infos = $this->_getInfo($report_id);
            }
        }
        return $infos;
    }

    /**
     * 获取请求ID
     *
     * 给FBA发送生成库存报告请求，返回识别的请求ID，当他收到请求将开始准备数据，
     * 当数据准备完毕用这个ID去获取生成的报告ID
     *
     * return integer
     */
    private function _getRequestId() {
        $params = $this->_getParams();

        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();

        if($data['httpcode'] == 200) {
            $data = $this->_xml2Array($data['data']);
            if(isset($data['RequestReportResult']['ReportRequestInfo']['ReportRequestId']))
                return $data['RequestReportResult']['ReportRequestInfo']['ReportRequestId']; 
        
        } else {
            Throw new Amazon_Exception("FBA生成报告请求失败。HTTP:[{$data['httpcode']}]");
        }
    }

    /**
     * 获取报告ID
     *
     * FBA生成报告需要一定的时间所以如果没有获取到报告，循环获取
     * 获取到报告ID之后，可以用过此报告ID得到报告详细内容
     * 
     * @param: $request_id integer 请求ID
     *
     * return integer
     */
    private function _getReportId($request_id) {
        $this->_options['Action']                   = 'GetReportRequestList';
        $this->_options['ReportRequestIdList.Id.1'] = $request_id;
        
        $params = $this->_getParams();

        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();
        if($data['httpcode'] == 200) {
            $data = $this->_xml2Array($data['data']);

            if(isset($data['GetReportRequestListResult']['ReportRequestInfo']['GeneratedReportId'])) {
                return $data['GetReportRequestListResult']['ReportRequestInfo']['GeneratedReportId'];
            } else {
                sleep(self::SLEEPTIME);
                return $this->_reportId($request_id);
            }

        } else {
            Throw new Amazon_Exception("FBA获取报告ID失败。HTTP:[{$data['httpcode']}]");
        }
    }

    /**
     * 获取库存详情
     *
     * @param: $report_id integer 报告ID
     *
     * return array
     */
    private function _getInfo($report_id) {
        $this->_options['Action']   = 'GetReport';
        $this->_options['ReportId'] = $report_id;
        unset($this->_options['ReportRequestIdList.Id.1']);

        $params = $this->_getParams();

        $curl = new Amazon_Curl();
        $curl->setParam($params);
        $data = $curl->perform();

        if($data['httpcode'] == 200) {
            $data = explode("\n", $data['data']);
            array_shift($data); // 去掉首行标题
            $infos = [];
            $codes = [];
            foreach($data as $datum) {
                $item     = explode("\t", $datum);
                $code     = $item[2];
                $status   = $item[4];
                $quantity = $item[5];

                // 数据转换
                if(in_array($code, $codes)) {
                    $index = array_search($code, $codes);
                    if($status == 'SELLABLE') {
                        $infos[$index]['sellable'] = $quantity;
                    } else {
                        $infos[$index]['unsellable'] = $quantity;
                    }
                
                } else {
                    $infos[] = [
                        'code'       => $code,
                        'sellable'   => ($status == 'SELLABLE') ? $quantity : 0,
                        'unsellable' => ($status == 'UNSELLABLE') ? $quantity : 0,
                        ];

                    $codes[] = $code;
                }
            }

            return $infos;
         } else {
             Throw new Amazon_Exception("FBA获取报告详情失败。");
         
         }
    }

    /**
     * 转换接口参数
     *
     */
    private function _getParams() {
        $fba = new Amazon();
        $fba->setData($this->_options, $this->_url);
        $data = $fba->combine();
        $params = [
            'url'   => $this->_url,
            'query' => $data,
            ];

        return $params;
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
