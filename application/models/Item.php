<?php

/**
 * 订单产品表模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Item.php  2012年11月15日 星期四 15时15分33秒Z $
 */
class Item {

    /**
     * 获取订单产品
     *
     * @param: $order_id integer 订单ID
     *
     * return object
     */
    public static function get($order_id) {
        return DB::table('items')->where('order_id', '=', $order_id)->get();
    }

    /**
     * 获取订单信息
     *
     * 表格版
     *
     * @param: $order_id integer 订单ID
     *
     * return string
     */
    public static function info($order_id) {
        $items = static::get($order_id);

        $fields = ['product_id'];
        foreach($items as $item) {
            $filter = ['sku' => $item->sku];
            $product_id = Product_Sale::filter($fields, $filter)->only('product_id');
            if($product_id) {
                $product = Product::info($product_id);
                $item->name = $product->name;
            } else {
                $item->name = '无法获取名称，未关联产品<a href="">[关联]</a>';
            }
        }

        return View::make('block.item')->with('items', $items)->render();
    }

    /**
     * 插入订单产品信息
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data) {
        return DB::table('items')->insert($data);
    }

    /**
     * 是否存在此订单产品
     *
     * @param: $order_id  integer 订单ID
     * @param: $entity_id string 产品实际ID
     *
     * return integer
     */
    public static function exists($order_id, $entity_id) {
        return DB::table('items')->where('order_id', '=', $order_id)
                                 ->where('entity_id', '=', $entity_id)
                                 ->only('id');
    }

    /**
     * 同步订单产品
     *
     * @param: $channels object 渠道信息
     *
     * return void
     */
    public static function sync($channels) {
        foreach($channels as $channel) {
            $fields = ['orders.id', 'orders.entity_id'];
            $filter = ['channel_id' => $channel->id, 'is_crawled' => 0];
            $orders = Order::filter($fields, $filter)->get();

            if(!$orders) continue;

            $interface = $channel->type;
            $options = unserialize($channel->accredit);
            $api = new ChannelAPI($interface, $options);
            foreach($orders as $order) {
                $options['entity_id'] = $order->entity_id;
                $data = $api->order()->items($options);
                foreach($data as $datum) {
                    $item_id = static::exists($order->id, $datum['entity_id']);
                    if(!$item_id) {
                        $datum['order_id'] = $order->id;
                        static::insert($datum);
                    }
                }

                $data = ['is_crawled' => 1];
                Order::update($order->id, $data);
            }
        }
    }
}

?>
