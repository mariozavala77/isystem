<?php
/**
 * 仓库调节
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Adjust.php  2012年12月03日 星期一 17时39分48秒Z $
 */
class Stock_Adjust extends Base_Model
{
    /**
     * 新增调节数据
     *
     * @param: $data array 数据
     *
     * return integer
     */
    public static function insert($data)
    {
        return DB::table('stock_adjust')->insert_get_id($data); 
    }

    /**
     * 更新调仓记录
     *
     * @param: $adjust_id integer 记录ID
     * @param: $data      array   数据
     *
     * return integer
     */
    public static function update($adjust_id, $data)
    {
        return DB::table('stock_adjust')->where('id', '=', $adjust_id)
                                        ->update($data);
    }

    /**
     * 列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = [])
    {
        $query = DB::table('stock_adjust as sa')->left_join('storage as s1', 'sa.from_storage_id', '=', 's1.id')
                                                ->left_join('storage as s2', 'sa.to_storage_id', '=', 's2.id')
                                                ->select($fields);

        static::formatFilter($query, $filter);

        return $query;
    }

    /**
     * 获取调仓信息
     *
     * @param: $stock_id integer 记录ID
     *
     * return object
     */
    public static function info($adjust_id)
    {
        return DB::table('stock_adjust')->where('id', '=', $adjust_id)->first();
    }
}


