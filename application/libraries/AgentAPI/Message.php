<?php
/**
 * 代理商api-消息
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class AgentAPI_Message extends AgentAPI_Base{
    
    // 代理商消息接收
    public static function send($params){
        $fields = ['receiver_id', 'title', 'message'];

        try{
            $data = self::requeryParams($fields, $params);
        }catch(Exception $e){
            throw new AgentAPIException($e->getMessage(), -32004);
        }

        $data['sender_id'] = $params['agent_id'];
        $data['type']      = 1;
        $data['sent_time'] = time();

        Message::insert($data);
    }
}