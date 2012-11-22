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

    /**
     * 录入发货信息
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = []) {
        $query = DB::table('queues')->select($fields);

        foreach($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query;
    }
}

?>
