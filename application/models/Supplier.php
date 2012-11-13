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
}

?>
