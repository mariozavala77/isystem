<?php

/**
 * 销售产品模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Sale.php  2012年11月06日 星期二 16时38分09秒Z $
 */
class Product_Sale {


    /**
     * 销售列表
     *
     * @param: $fields array  字段
     *
     * return object
     */
    public static function filter($fields) {
        return DB::table('products_sale')->select($fields);
    }
}

?>
