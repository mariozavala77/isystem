<?php

/**
 * 销售渠道模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Channel.php  2012年11月04日 星期日 10时10分57秒Z $
 */

class Channel {

    /**
     * 获取所有销售渠道
     *
     * return $object
     */
    public static function all() {
        return DB::table('channels')->get();
    }

    /**
     * 更新渠道信息
     *
     * @param: $channel_id integer 渠道ID
     * @param: $data       array   数据
     *
     * return void
     */
    public static function update($channel_id, $data) {
        DB::table('channels')->where('id', '=', $channel_id)->update($data);
    }

}
?>
