<?php
/**
 * 订单拆分模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Orders_Split {

    /**
     * 获取订单拆分的信息
     *
     * @param: $orders_split_id integer 拆分订单ID
     * 
     * return object
     */
    public static function info($orders_split_id) {
        return DB::table('orders_split')->where('id', '=', $orders_split_id)->first();
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
        $query = DB::table('orders_split')->select($fields);

        foreach($filter as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 添加拆分订单
     *
     * @param: $data array 数据
     *
     * return void
     */
    public static function insert($data) {
        DB::table('orders_split')->insert($data);
    }

    /**
     * 更新拆分订单
     *
     * @param: $orders_split_id integer 拆分订单ID
     * @param: $data     array   数据
     *
     * return void
     */
    public static function update($orders_split_id, $data) {
        DB::table('orders_split')->where('id', '=', $orders_split_id)->update($data);
    }

    /**
     * 删除拆分订单
     *
     * @param: $orders_split_id integer 拆分订单ID
     *
     * return void
     */
    public static function delete($orders_split_id) {
        DB::table('orders_splits')->where('id', '=', $orders_split_id)->delete();
    }


    /**
     * 更具订单和代理商查找拆分的id
     */
    public static function get_id($order_id, $agent_id){
        return DB::table('orders_split')->where('order_id', '=', $order_id)
                                        ->where('agent_id', '=', $agent_id)
                                        ->only('id');
    }
    /**
     * 批量处理订单
     */
    public static function dobatch($data){

        $updata = $insert = [];
        foreach ($data as $key => $value) {
            $sku_id = static::get_id($value['order_id'], $key);
            $value['agent_id'] = $key;
            $value['updated_at'] = date('Y-m-d H:i:s');
            if(empty($sku_id)){
                $insert[] = $value;
            }else{
                $updata[$sku_id] = $value;
            }
        }

        if(!empty($updata)){
            static::batchupdate($updata);
        }
        if(!empty($insert)){
            static::batchinsert($insert);
        }
    }

    /**
     * 拆分订单批量插入
     *
     */
    public static function batchinsert($data){
        $sql = 'INSERT INTO `orders_split` (`order_id`,`channel_id`,`status`,`ship_status`,'
             . '`shipping_country`,`name`,`currency`,`items`,`total_price`,`agent_id`,'
             . '`updated_at`) VALUES ';
        foreach($data as $key=>$value){
            $data[$key] = '(\''.implode('\',\'', $value).'\')';
        }
        $sql = $sql.implode(',', $data);

        return DB::query($sql);
    }

    /**
     * 拆分订单批量更新
     * 
     */
    public static function batchupdate($data){
        foreach ($data as $key => $value) {
            static::update($key, $value);
        }
    }
}