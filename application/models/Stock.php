<?php

/**
 * 库存模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Stock.php  2012年11月10日 星期六 01时53分28秒Z $
 */
class Stock {

    /**
     * 列表
     *
     * @param: $fields array 字段
     * @param: $filter array 过滤
     *
     * return object
     */
    public static function filter( $fields, $filter = [] ) {
        return DB::table('stock')->left_join('storage', 'stock.storage_id', '=', 'storage.id')->select($fields);
    }

    /**
     * 从外面仓储获取库存
     *
     * @param: $storage_id integer 仓储ID
     *
     * return void
     */
    public static function info($storage_id) {
        $storage = Storage::info($storage_id);
        if($storage->channel_id) {
            $channel = Channel::info($storage->channel_id);
            $storage->accredit = $channel->accredit;
        }

        $options = unserialize($storage->accredit);

        $stock = new StockAPI($storage->type, $options);
        $infos = $stock->info();

        foreach($infos as $info) {
            $stock_id = static::exists($storage_id, $info['code']);
            if($stock_id) {
                $info['updated_at'] = date('Y-m-d H:i:s');
                static::update($stock_id, $info);
            } else {
                $info['created_at'] = date('Y-m-d H:i:s');
                static::insert($info);
            }
        }

        echo 'ok';
    }

    /**
     * 检测库存信息是否存在
     *
     * @param: $storage_id integer 仓库ID
     * @param: $code       string  库存编码
     *
     * return integer 库存表ID
     */
    public static function exists($storage_id, $code) {
        return DB::table('stock')->where('storage_id', '=', $storage_id)
                                 ->where('code', '=', $code)
                                 ->only('id');
    }

    /**
     * 插入库存数据
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data) {
        return DB::table('stock')->insert($data);
    }

    /**
     * 更新库存
     *
     * @param: $stock_id integer 记录ID
     * @param: $data     array   数据
     *
     * return boolean
     */
    public static function update($stock_id, $data) {
        return DB::table('stock')->where('stock_id', '=', $stock)
                                 ->update($data);
    }




}

?>
