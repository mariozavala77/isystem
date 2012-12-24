<?php
/**
 * 物流信息跟踪-Lasership Track
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class TrackAPI_Lasership{
    // api 连接地址
    const API_URL = 'http://www.lasership.com/track/{TRACKINGNUMBER}/json';

    // 远程提交处理
    public function transport($com, $nu){
        $url = str_replace('{TRACKINGNUMBER}',$nu,self::API_URL);
        $transport = new Transport();
        try{
            $request = $transport->request($url);
            if(!empty($request) || !isset($request['body'])){
                $request = json_decode($request['body'],true);
                $request = $request['Events'];
                foreach ($request as $key => $value) {
                    $info_time = date('Y-m-d H:i:s', strtotime($value['UTCDateTime']));
                    $info_context = trim($value['City']).','.trim($value['Country']).':'.trim($value['EventShortText']).(empty($value['Location'])?:' '.$value['Location']);
                    $tracking_info[$key]=['time'=>$info_time, 'context'=>$info_context];
                }
                $state = trim($request[0]['EventShortText']);
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