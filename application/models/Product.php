<?php

/**
 * 产品模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Product.php  2012年11月01日 星期四 11时21分46秒Z $
 */
class Product {

    /**
     * 产品列表
     *
     * @param: $fields array  显示字段
     * @param: $filter array  过滤
     *
     * return object
     */
    public static function filter( $fields, $filter = [] ) {

        $query = DB::table('products as p')->left_join('products_extensions as pe', 'p.id', '=', 'pe.product_id')
                                           ->select($fields);

        foreach($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query->order_by('product_id', 'DESC');
    }

    /**
     * 获取指定产品信息
     *
     * @param: $product_id integer 产品ID
     * @param: $language   string  语言
     *
     * return object
     */
    public static function info($product_id, $language = 'cn') {

        $info = DB::table('products as p')->left_join('products_extensions as pe', 'p.id', '=', 'pe.product_id')
                                          ->where('p.id', '=', $product_id)
                                          ->where('pe.language', '=', $language)
                                          ->first();

        $info->images = DB::table('products_images')->where('product_id', '=', $product_id)->get();

        return $info;
    }

    /**
     * 写入产品表
     *
     * @param: $data array 数据
     *
     * return bool
     */
    public static function insert($data) {
        return DB::table('products')->insert($data);
    }

    /**
     * 写入产品表并返回ID
     *
     * @param: $data array 数据
     *
     * return integer
     */
    public static function insertGetId($data) {
        return DB::table('products')->insert_get_id($data);
    }

    /**
     * 产品导入
     *
     * 通过上传的Excel文件进行产品批量导入，返回的是导入之后的结果
     *
     * @param: $filepath string 导入文件的绝对地址
     *
     * return array
     */
    public static function import($filepath) {
        try {
            $import = new Import('product', $filepath);
            $result = ['status' => 'success', 'message'=> '导入成功！'];
        } catch(Import\ImportRowException $e) {
            $result = ['status' => 'fail', 'message' => $e->getMessage()];
        } catch(Import\ImportFileException $e) {
            $result = ['status' => 'fail', 'message' => $e->getMessage()];
        } catch(Import\ImportException $e) {
            $result = ['status' => 'fail', 'message' => $e->getMessage()];
            // Write Log
            //$result = ['status' => 'fail', 'message' => '导入插件出错，请联系管理员' ];
        }
    
        return $result;
    }

    /**
     * 更新数据
     *
     * @param: $product_id integer 产品ID
     * @param: $data       array   产品数据
     *
     * return bool
     */
    public static function update($product_id, $data) {
        return DB::table('products')->where('id', '=', $product_id)->update($data);
    }

    /**
     * 获取语言版本
     *
     * @param: $product_id  integer 产品ID
     *
     * return object
     */
    public static function language($product_id) {
        return DB::table('products_extensions')->where('product_id', '=', $product_id)->lists('language');
    }

    /**
     * 删除数据
     * 
     * @param: $product_id integer 产品ID
     *
     * return bool
     */
    public static function delete($product_id) {
        return DB::table('products')->delete($product_id);
    }

}
?>
