<?php

/**
 * 产品图片模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Image.php  2012年11月07日 星期三 18时00分51秒Z $
 */
class Product_Image {

    /**
     * 获取产品图片
     *
     * @param: $product_id integer 产品ID
     * @param: $take       integer 获取数目
     *
     * return object
     */
    public static function get($product_id, $take = 0) {

        $model = DB::table('products_images')->where('product_id', '=', $product_id)->order_by('sort', 'DESC');

        switch ($take) {
            case '0':
                $result = $model->get();
                break;
            case '1':
                $result = $model->first();
                break;
            default:
                $result = $model->take($take)->get();
                break;
        }

        return $result;
    }
}
?>
