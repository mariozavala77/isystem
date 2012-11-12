<?php
/**
 * 代理商api-渠道
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */

class AgentAPI_channel
{
    /**
     * 渠道列表
     *
     * @param array $params 列表相关参数
     *
     * @return mixed 仅返回 id 别名 描述
     */
    public static function lists($params){
        $fields = ['id', 'alias', 'description', 'lang'];
        $request = Channel::filter($fields)->get();

        return $request;
    }
}
