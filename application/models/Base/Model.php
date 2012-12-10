<?php

/**
 * 基本模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Base.php  2012年11月21日 星期三 20时54分00秒Z $
 */
class Base_Model {

    private static $_actions = [ 'like', 'unequal' ];

    /**
     * 附加过滤格式化
     *
     * 非actions关键字，它们默认是组成where $key = $value形式
     *
     * @param: &$query object 数据库查询
     * @param: $filter array  过滤
     * 
     * return void;
     */
    public static function formatFilter(&$query, $filter = []) {
        foreach($filter as $key => $value) {
            if(!in_array($key, self::$_actions)) {
                $query->where($key, '=', $value);
            } else {
                $action = 'filter'.ucfirst($key);
                $query = static::$action($query, $value);
            }
        }
    }

    /**
     * 模糊过滤
     *
     * @param: $query object 数据库查询
     * @param: $filter array 过滤条件
     *
     * return object
     */
    public static function filterLike($query, $filter) {
        foreach($filter as $key => $value) {
            $query = $query->where($key, 'Like', "%{$value}%");
        }

        return $query;
    }

    /**
     * 过滤不等
     *
     * @param: $query object 数据库查询
     * @param: $filter array 过滤条件
     *
     * return object
     */
    public static function filterUnequal($query, $filter) {
        foreach($filter as $key => $value) {
            $query = $query->where($key, '!=', $value);
        }

        return $query;
    }
}

?>
