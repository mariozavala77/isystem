<?php
/**
 * 物流信息跟踪-Parcel Pool Track
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class TrackAPI_Parcelpool{
    // api 连接地址
    const API_URL = 'https://www.myib.com/tracking/Default.aspx';

    // 远程提交处理
    public function transport($com, $nu){
        $url = self::API_URL;
        $params = ['tnum' => $nu];
        $options = ['User-Agent' => 'Mozilla/5.0 (Windows NT 5.1; rv:17.0) Gecko/20100101 Firefox/17.0', 'Host' => 'www.myib.com'];
        $transport = new Transport();
        try{
            $request = $transport->request($url, $params, 'GET', $options);
            return $request;
            if(!empty($request) || !isset($request['body'])){
                $request = $request['body'];
                return $request;
                preg_match_all('/<ul class="activity"><li class="date">(.*?)<\/li><li class="time">(.*?)<\/li><li class="description">(.*?)<\/li><li class="location">(.*?)<\/li><\/ul>/', $request, $matches);
                unset($matches[0]);
                $times = $matches[1];
                foreach ($times as $key => $value) {
                    $info_time = date('Y-m-d H:i:s',strtotime($matches[1][$key].' '.$matches[2][$key]));
                    $info_context = trim($matches[4][$key]).':'.trim($matches[3][$key]);
                    $tracking_info[$key]=['time'=>$info_time, 'context'=>$info_context];
                }
                $state = trim(array_pop($matches[3]));
                if($state=='Delivered'){
                    $state = 4;
                }else{
                    $state = 2;
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
                'user_id'    => 0,
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