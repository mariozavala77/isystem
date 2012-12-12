<?php

/**
 * 供应商模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Supplier.php  2012年11月13日 星期二 13时54分28秒Z $
 */
class Supplier {


    /**
     * 列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = []) {

        $query = DB::table('suppliers')->select($fields);
        foreach ($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 获取单条供应商的信息
     *
     * @param: $supplier_id integer 供应商ID
     *
     * return object
     */
    public static function info($supplier_id){
        return DB::table('suppliers')->where('id', '=', $supplier_id)->first();
    }

    /**
     * 插入供应商信息
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data){
        return DB::table('suppliers')->insert($data);
    }

    /**
     * 更新供应商信息
     *
     * @param: $supplier_id integer 供应商ID
     * @param: $data        array   数据
     *
     * return boolean
     */
    public static function update($supplier_id, $data){
        return DB::table('suppliers')->where('id', '=', $supplier_id)->update($data);
    }

    /**
     * 删除供应商
     *
     * @param $supplier_id integer 供应商ID
     *
     * retrun boolean
     */
    public static function delete($supplier_id){
        return DB::table('suppliers')->where('id', '=', $supplier_id)->delete();
    }
    
    /**
     * 获取供应商的个数
     *
     * return integer
     */
    public static function total() {
        return DB::table('suppliers')->count();
    }    
}