<?php
/**
 * 仓库调节
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Adjust.php  2012年12月03日 星期一 17时39分48秒Z $
 */
class Stock_Adjust
{

    /**
     * 新增调节数据
     *
     * @param: $data array 数据
     *
     * return integer
     */
    public static function insert($data)
    {
        return DB::table('stock_adjust')->insert_get_id($data); 
    }
}


