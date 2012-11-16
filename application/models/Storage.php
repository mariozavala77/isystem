<?php

/**
 * 仓库表
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Storage.php  2012年11月16日 星期五 10时59分25秒Z $
 */

class Storage {

    /**
     * 获取外部仓库信息
     *
     * 用于同步产品的存库
     *
     * return array
     */
    public static function outStorage() {
        return DB::table('storage')->where('type', '!=', 'Local')->lists('id');
    }

    /**
     * 获取仓库信息
     *
     * @param: $storage_id integer 仓库ID
     *
     * return object
     */
    public static function info($storage_id) {
        return DB::table('storage')->where('id', '=', $storage_id)->first();
    }
}


?>