<?php

/**
 * 产品分类模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Category.php  2012年11月06日 星期二 11时47分13秒Z $
 */
class Category {

    /**
     * 获取列表信息
     *
     * @param: $category_id integer 类别ID
     *
     * return object
     */
    public static function info($category_id) {
        return DB::table('category')->where('id', '=', $category_id)->first();
    }

    /**
     * 列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = []) {
        $query = DB::table('category')->select($fields);
        foreach($filter as $key => $value) {
            $query = $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 获取分类名
     *
     * 包括父级别名称
     *
     * @param: $category_id integer 分类ID
     *
     * return string
     */
    public static function name($category_id) {
        $info = DB::table('category')->where('id', '=' ,$category_id)->first();
        $name = $info->name;
        if($info->parent_id != 0) {
            $name = static::name($info->parent_id) . ' > ' . $name;
        }

        return $name;
    }

    /**
     * 获取下级分类
     *
     * 如果$repeat为true迭代获取所有下级分类
     *
     * @param: $category_id integer 当前分类ID
     * @param: $repeat      bool    是否迭代
     *
     * return object
     */
    public static function children($category_id = 0, $repeat = true) {
       $categories = DB::table('category')->where('parent_id', '=', $category_id)->get();

       if($repeat) {
           foreach($categories as $category) {
               $category->children = static::children($category->id);
           }
       }

       return $categories;
    }
}

?>
