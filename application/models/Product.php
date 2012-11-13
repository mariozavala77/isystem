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

        return $query;
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
     * 添加产品
     *
     * @param: $data array 提交数据
     *
     * return boolean
     */
    /*
    public static function insert( $data ) {

        $product_data = [
            'sku'         => $data['sku'],
            'cost'        => $data['cost'],
            'category_id' => $data['category_id'], 
            'supplier_id' => $data['supplier_id'], 
            'devel_id'    => $data['devel_id'], 
            'min_price'   => $data['min_price'],
            'max_price'   => $data['max_price'],
            'weight'      => $data['weight'] * 1000,
            'size'        => $data['size'],
            'status'      => $data['status'],
            ];

        $product_id = static::_insertProduct( $product_data );

        if($product_id) {
            $product_extension_data = [
                'product_id'        => $product_id,
                'name'              => $data['name'],
                'language'          => $data['language'],
                'keywords'          => $data['keywords'],
                'short_description' => $data['short_description'],
                'description'       => $data['description'],
                'created_at'        => date('Y-m-d H:i:s'),
                ];

            static::_insertProductExtension( $product_extension_data );

            if($data['images']) {
                Product_Image::insert($product_id, $data['images']);
            }
        }

        return $product_id ? true : false;
    }
     */

    /**
     * 写入products表
     *
     * @param: $data array 插入数据
     *
     * return integer
     */
    private static function _insertProduct( $data ) {
        return DB::table('products')->insert_get_id($data);
    }

    /**
     * 写入proudcts_extension表
     *
     * @param: $data array 插入数据
     *
     * return void
     */
    private static function _insertProductExtension( $data ) {
        DB::table('products_extension')->insert( $data );
    }
}