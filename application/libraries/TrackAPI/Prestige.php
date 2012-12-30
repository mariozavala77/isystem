<?php
/**
 * 物流信息跟踪-Prestige Track
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class TrackAPI_Prestige{
    // api 连接地址
    const API_URL = 'http://www.prestigedelivery.com/TrackingHandler.ashx';

    // 远程提交处理
    public function transport($com, $nu){
        $url = self::API_URL;
        $params = ['trackingNumbers' => $nu];
        $transport = new Transport();
        try{
            $request = $transport->request($url, $params, 'GET');
            if(!empty($request) || !isset($request['body'])){
                $request = $request['body'];
                $request = json_decode($request,true);
                $TrackingEventHistory = $request[0]['TrackingEventHistory'];
                if($TrackingEventHistory[0]['EventCode'] == 'ERROR_101'){
                    new AgentAPILogException($TrackingEventHistory[0]['EventCodeDesc'],101);
                }
                $state = 2;
                foreach ($TrackingEventHistory as $key => $value) {
                    $info_time = date('Y-m-d H:i:s',strtotime($value['serverDate']. ' ' . $value['serverTime']));
                    if ($value['EventCode'] == "EVENT_101") {
                        $info_context = $value['CountryCode'];
                    }
                    else if ($value['EventCode'] == "EVENT_301") { //Delivered
                        $state = 4;
                        $info_context = $value['PDCity'] . '-' . $value['PDState'] . " " . $value['CountryCode'];
                    }
                    else {
                        $info_context = $value['ELCity'] . '-' . $value['ELState'] . " " . $value['CountryCode'];
                    }
                    $info_context = $info_context.':'.trim($value['EventCodeDesc']);
                    $tracking_info[$key]=['time'=>$info_time, 'context'=>$info_context];
                }

                return ['status' => $state, 'tracking_info' => $tracking_info];
            }
        }catch(AgentAPILogException $e){
            $content = [
                'errormsg' => $e->getMessage(),
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
                'trace'    => $e->getTraceAsString(),
                'params'   => ['company' => $com, 'tracking_no' => $nu]
            ];
            $data = [
                'user_id'    => $this->_user_id,
                'content'    => serialize($content),
                'created_at' => date('Y-m-d H:i:s')
            ];
            Logs::insert($data); // 写入日志
            return FALSE;
        }
    }

    // 数据格式处理
    public function get($com, $nu){
        return $this->transport($com, $nu);
    }
}