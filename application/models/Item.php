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
     * return string|html
     */
    public static function info($order_id) {
        $items = static::get($order_id);

        foreach($items as $item) {
                $product_id = static::productMap($item->sku); 
                if($product_id) {
                    $product = Product::info($product_id);
                    $storages = Storage::stock($product_id);

                    $item->name = $product->name;
                    $item->stock = static::formatStock($storages);
                } else {
                    $item->name = '<span class="red">未关联产品池，无法获取名称。</span>';
                    $item->stock = '无';
                }
        }

        return View::make('block.item_info')->with('items', $items)->render();
    }

    /**
     * 产品发货表格
     *
     * @param: $order_id integer 订单ID
     *
     * return string|html
     */
    public static function ship($order_id) {
        $items = static::get($order_id);
        $items_ship = [];
        foreach($items as $item) {
            $shiped = Track::itemCount($item->id);
            if($item->quantity > $shiped) {
                $product_id = static::productMap($item->sku);
                if($product_id) {
                    $product = Product::info($product_id);
                    $storages = Storage::stock($product_id);

                    $item->name = $product->name;
                    $item->stock = static::formatStock($storages);
                } else {
                    $item->name = '<span class="red">未关联产品池，无法获取名称。</span>';
                    $item->stock = '无';
                }
                $item->unship = $item->quantity - $shiped;
                $items_ship[] = $item;
            }
        }

        return View::make('block.item_ship')->with('items', $items_ship)->render();
    }

    /**
     * 格式化库存
     *
     * @param: $storages array 库存信息
     *
     * return html
     */
    public static function formatStock($storages_info) {
        $stock = '';
        $split = '';
        foreach($storages_info as $storage) {
            $count = $storage['sellable'] ? $storage['sellable'] : 0;
            $stock .= sprintf($split.'%s[%s]%s个', $storage['area'], $storage['type'], $count);
            $split = '<br/>';
        }

        return $stock;
    }



    /**
     * 获取产品池映射
     *
     * @param: $sku string 上架产品的SKU
     *
     * return integer
     */
    public static function productMap($sku) {
        $fields = [ 'products.id' ];
        $filter = [ 'sku' => $sku ];
        $product_id = Product_Sale::filter($fields, $filter)->only('product_id');

        return $product_id;
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
