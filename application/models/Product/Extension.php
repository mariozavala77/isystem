<?php

/**
 * 产品扩展表模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Extension.php  2012年11月13日 星期二 15时02分57秒Z $
 */
class Product_Extension {


    /**
     * 插入
     *
     * @param: $data array 数据
     *
     * return bool
     */
    public static function insert($data) {
        return DB::table('products_extensions')->insert($data);
    }
}

?>
