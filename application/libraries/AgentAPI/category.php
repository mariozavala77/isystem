<?php
/**
 * 代理商API产品分类相关
 * 产品分类lists直接返回树形结构 
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */

class AgentAPI_Category
{
    /**
     * 产品分类列表
     *
     * @param $params array 相关参数列表
     *
     * @return object 直接返回分类的属性数组
     */
    public static function lists($params){
        $filed = ['id', 'parent_id', 'name'];
        $items = Category::filter($filed)->get();

        return static::genTree($items);
    }

    /**
     * 将数据格式化成树形结构
     *
     * @param $items array 需要处理的数组
     *
     * @return object
     */
    private static function genTree($items) {
        $tree = []; //格式化好的树
        foreach($items as $key => $item){
            $tree[$item->id] = (array)$item;
        }
        $items = $tree;
        $tree  = [];
        foreach ($items as $item){
            if (isset($items[$item['parent_id']])){
                $items[$item['parent_id']]['child'][] = &$items[$item['id']];
            }else{
                $tree[] = &$items[$item['id']];
            }
        }

        return (object)$tree;
    }
}