<?php

/**
 * 日志模型
 *
 * @author: shaoqi <shaoqisq123@gamil.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Channel.php  2012年11月04日 星期日 10时10分57秒Z $
 */

class Logs {

    /**
     * 获取所有的日志
     *
     * return $object
     */
    public static function filter($fields) {
        return DB::table('log')->select($fields);
    }

    /**
     * 新增日志
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data){
        return DB::table('log')->insert( $data );
    }

    /**
     * 获取单条日志的信息
     *
     * @param: $log_id integer 日志ID
     *
     * return object
     */
    public static function info($log_id){
        return DB::table('log')->where('id', '=', $log_id)->first();
    }
}