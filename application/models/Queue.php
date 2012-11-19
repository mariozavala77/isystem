<?php

/**
 * 队列模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Queue.php  2012年11月18日 星期日 11时23分44秒Z $
 */
class Queue {

    /**
     * 插入队列
     *
     * @param: $data array 数据
     *
     * return integer
     */
    public static function insert($data) {
        return DB::table('queues')->insert_get_id($data);
    }
}

?>
