<?php
/**
 * 物流信息跟踪-DHL global mail Track
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class TrackAPI_Dhlglobalm{
    // api 连接地址
    const API_URL = 'http://webtrack.dhlglobalmail.com/';

    // 远程提交处理
    public function transport($com, $nu){
        $url = self::API_URL;
        $params = ['mobile' => '', 'trackingnumber' => $nu];
        $transport = new Transport();
        try{
            $request = $transport->request($url, $params, 'GET');
            if(!empty($request) || !isset($request['body'])){
                $request = str_replace(["\n","\t"],'',$request['body']);
                preg_match_all('/<td.*?>.*?<\/td>/',$request,$out);
                $out = explode(',',str_replace(['<td>','</td>','<td class="bold">'],'',implode(',',$out[0])));
                $count = count($out)/5;
                for ($i=0; $i < $count; $i++) {
                    $key = $i*5
                    $info_time = date('Y-m-d H:i:s',strtotime($matches[1][$key].' '.$matches[2][$key]));
                }
                foreach ($times as $key => $value) {
                    $info_time = date('Y-m-d H:i:s',strtotime($matches[1][$key].' '.$matches[2][$key]));
                    $info_context = trim($matches[4][$key]).':'.trim($matches[3][$key]);
                    $tracking_info[$key]=['time'=>$info_time, 'context'=>$info_context];
                }
                $state = trim($out[4]);
                if($state=='DELIVERED'){
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