<?php

/**
 * 销售渠道模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Channel.php  2012年11月04日 星期日 10时10分57秒Z $
 */

class Channel extends Base_Model
{

    /**
     * 获取所有销售渠道
     *
     * @param: $fields array 字段
     * @param: $filter array 附加条件
     *
     * return $object
     */
    public static function filter($fields, $filter = [])
    {

        $query = DB::table('channels')->select($fields);
        static::formatFilter($query, $filter);

        return $query;
    }

    /**
     * 插入渠道信息
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data)
    {
        return DB::table('channels')->insert_get_id( $data );
    }

    /**
     * 外部渠道（非代理商渠道）
     *
     * return array
     */
    public static function out()
    {
        $out = Cache::get('app.out_channels');
        if(empty($out)) {
            $out = DB::table('channels')->where('type', '!=', 'Agent')
                                        ->lists('id');

            Cache::put('app.out_channels',$out, 3600);
        }

        return $out;
    }

    /**
     * 获取单条渠道的信息
     *
     * @param: $channel_id integer 渠道ID
     *
     * return object
     */
    public static function info($channel_id)
    {
        return DB::table('channels')->where('id', '=', $channel_id)->first();
    }

    /**
     * 更新渠道信息
     *
     * @param: $channel_id integer 渠道ID
     * @param: $data       array   数据
     *
     * return boolean
     */
    public static function update($channel_id, $data)
    {
        if(isset($data['accredit'])) $data['accredit'] = serialize($data['accredit']);
        return DB::table('channels')->where('id', '=', $channel_id)->update($data);
    }

    /**
     * 删除渠道
     *
     * @param $channel_id integer 渠道ID
     *
     * retrun boolean
     */
    public static function delete($channel_id)
    {
        return DB::table('channels')->where('id', '=', $channel_id)->delete();
    }

    /**
     * 可上架销售渠道列表
     * 
     * @param: $sale_id integer 销售产品ID
     *
     * return object
     */
    public static function sell($sale_id)
    {
        $out_channel_ids = static::out();
        $sale  = Product_Sale::info($sale_id);
        $agent = Agent::info($sale->agent_id);
        $product_id = $sale->product_id;

        $channel_ids = array_merge($out_channel_ids, [$agent->channel_id]);

        $channels = [];
        foreach($channel_ids as $channel_id) {
            $channel = static::info($channel_id);
            $channel->on_sale = Product_Sale_Sku::onSale($product_id, $channel_id);
            $channels[] = $channel;
        }

        return $channels;
    }

}
