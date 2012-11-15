<?php

/**
 * 订单跟踪表模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Track.php  2012年11月15日 星期四 15时03分26秒Z $
 */
class Track {

    /**
     * 插入跟踪信息
     *
     * @param: $data array 跟踪数据
     *
     * return boolean
     */
    public static function insert($data) {
        return DB::table('track')->insert($data);
    }

}

?>
