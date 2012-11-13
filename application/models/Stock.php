<?php

/**
 * 库存模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Stock.php  2012年11月10日 星期六 01时53分28秒Z $
 */
class Stock {

    /**
     * 列表
     *
     * @param: $fields array 字段
     * @param: $filter array 过滤
     *
     * return object
     */
    public static function filter( $fields, $filter = [] ) {
        return DB::table('stock')->left_join('storage', 'stock.storage_id', '=', 'storage.id')->select($fields);
    }

}

?>
