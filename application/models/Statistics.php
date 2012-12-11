<?php
/**
 * 订单统计模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Statistics
{
    /**
     * 新增订单统计数据
     *
     * @param: $data array  新增数据
     *
     * return integer
     */
    public static function insert($data)
    {
        return DB::table('statistics')->insert($data);
    }

    /**
     * 更新订单统计数据
     *
     * @param: $date integer 统计时间(Ymd)
     * @param: $data array   统计数据
     *
     * return boolean
     */
    public static function update($date, $data)
    {
        return DB::table('statistics')->where_in('id', '=', $date)->update($data);
    }

    /**
     * 记录是否存在
     *
     * @param: $date integer 统计时间(Ymd)
     *
     * return object
     */
    public static function exists($date)
    {
        return DB::table('statistics')->where('id', '=', $date)
                                      ->only('date');
    }

    /**
     * 订单统计列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */    
    public static function filter($fields, $filter = [])
    {
        $query = DB::table('statistics')->select($fields);

        foreach($filter as $key => $value)
        {
            $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 订单统计最大时间
     *
     * return string
     */
    public static function max_date()
    {
        return DB::table('statistics')->max('date');
    }              
}