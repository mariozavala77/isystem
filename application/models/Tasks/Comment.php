<?php
/**
 * 任务留言模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Tasks_Comment {

    /**
     * 留言列表
     *
     * @param: $fields array 字段
     * @param: $filter array 过滤条件
     *
     * return object
     */
    public static function filter($fields, $filter = []) {
        $query = DB::table('tasks_comment as tc')->left_join('users', 'tc.uid', '=', 'users.id')
                                                 ->select($fields);

        foreach ($filter as $key => $value) {
            if(!empty($value)){
                $query->where($key, '=', $value);
            }
        }

        return $query;
    }

    /**
     * 新建留言
     *
     * @param: $data array 插入的内容
     *
     * return object
     */
    public static function insert($data){
        return DB::table('tasks_comment')->insert($data);
    }
}