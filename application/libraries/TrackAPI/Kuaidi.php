<?php
/**
 * 物流信息跟踪-快递一百接口
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */

class TrackAPI_Kuaidi{
    
    // api 连接地址
    const API_URL = 'http://api.kuaidi100.com/api?id={key}&com={com}&nu={nu}&show=0&muti=1&order=asc';
    
    // api key
    const API_KEY = 'ac4bffc51dce962e';
    
    // 远程提交处理
    public function transport($com, $nu){
        $search  = ['{key}', '{com}', '{nu}'];
        $replace = [self::API_KEY, $com, $nu];
        $subject = self::API_URL;
        $api = str_replace($search, $replace, $subject);
        $transport = new Transport();
        try{
            $request = $transport->request($api);
            if(!empty($request) || !isset($request['body'])){
                $request = $request['body'];
                $request = json_decode($request,TRUE);
                if($request['status']!=1){
                    throw new AgentAPILogException($request['message'], $request['status']);
                }
                return ['status' => $request['state']+1, 'tracking_info' => $request['data']];
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