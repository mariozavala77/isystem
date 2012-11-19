<?php

/**
 * 消息模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */

class Message {

    /**
     * 获取所有消息
     *
     * return $object
     */
    public static function filter($fields, $filter = []) {

        $query = DB::table('message')->select($fields);
        foreach ($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 插入渠道信息
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data){
        return DB::table('message')->insert_get_id( $data );
    }

    /**
     * 获取单条渠道的信息
     *
     * @param: $message_id integer 消息ID
     *
     * return object
     */
    public static function info($message_id){
        return DB::table('message')->where('id', '=', $message_id)->first();
    }

    /**
     * 更新渠道信息
     *
     * @param: $message_id integer 消息ID
     * @param: $data       array   数据
     *
     * return boolean
     */
    public static function update($message_id, $data) {
        return DB::table('message')->where('id', '=', $message_id)->update($data);
    }

    /**
     * 删除消息
     *
     * @param $message_id integer 消息ID
     *
     * retrun boolean
     */
    public static function delete($message_id){
        return DB::table('message')->where('id', '=', $message_id)->delete();
    }

}