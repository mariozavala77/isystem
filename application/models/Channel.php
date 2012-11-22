<?php

/**
 * 销售渠道模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Channel.php  2012年11月04日 星期日 10时10分57秒Z $
 */

class Channel extends Base_Model {

    /**
     * 获取所有销售渠道
     *
     * @param: $fields array 字段
     * @param: $filter array 附加条件
     *
     * return $object
     */
    public static function filter($fields, $filter = []) {

        $query = DB::table('channels')->select($fields);

        static::formatFilter($query, $filter);

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
        return DB::table('channels')->insert_get_id( $data );
    }

    /**
     * 获取单条渠道的信息
     *
     * @param: $channel_id integer 渠道ID
     *
     * return object
     */
    public static function info($channel_id){
        return DB::table('channels')->where('id', '=', $channel_id)->first();
    }

    /**
     * 更新渠道信息
     *
     * @param: $channel_id integer 渠道ID
     * @param: $data       array   数据
     *
     * return boolean
     */
    public static function update($channel_id, $data) {
        if(isset($data['accredit'])) $data['accredit'] = serialize($data['accredit']);
        return DB::table('channels')->where('id', '=', $channel_id)->update($data);
    }

    /**
     * 删除渠道
     *
     * @param $channel_id integer 渠道ID
     *
     * retrun boolean
     */
    public static function delete($channel_id){
        return DB::table('channels')->where('id', '=', $channel_id)->delete();
    }

}
