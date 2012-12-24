<?php

/**
 * 订单模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Order.php  2012年11月03日 星期六 12时47分04秒Z $
 */
class Order {

    /**
     * 检查订单是否存在
     *
     * 如果存在返回订单数据
     *
     * @param: $data array 筛选数据
     *
     * return object
     */
    public static function exists( $data ) {
    
        $table = DB::table('orders');

        foreach($data as $key => $value) {
            $table = $table->where($key, '=', $value);
        }

        return $table->first();
    }

    /**
     * 新增订单
     *
     * @param: $data array  新增数据
     *
     * return integer
     */
    public static function insert( $data ) {
       return DB::table('orders')->insert_get_id( $data );
    }

    /**
     * 更新订单
     *
     * @param: $order_id integer 订单ID
     * @param: $data     array   订单数据
     *
     * return void
     */
    public static function update( $order_id, $data ) {
        return DB::table('orders')->where('id', '=', $order_id)->update($data);
    }

    /**
     * 订单列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = []) {
        $query = DB::table('orders')->left_join('channels', 'orders.channel_id', '=', 'channels.id')
                                    ->select( $fields );

        foreach($filter as $key => $value) {
            $query->where($key, '=', $value);
        }

        $query->order_by('orders.status', 'ASC');

        return $query->order_by('orders.id', 'DESC');
    }

    /**
     * 订单详情
     *
     * @param: $order_id integer 订单ID
     *
     * return object
     */
    public static function info($order_id) {
        return DB::table('orders')->where('id', '=', $order_id)->first();
    }

    /**
     * 同步所有订单
     *
     * @param: $channels object 渠道信息
     *
     * return void
     */
    public static function sync($channels) {
        foreach($channels as $channel) {
            $interface = $channel->type;
            $options = unserialize($channel->accredit);
            $options['LastUpdatedAt'] = $channel->synced_at;
            $api = new ChannelAPI($interface, $options);

            // 订单处理
            $data = $api->order()->sync();
            if(!empty($data['data'])) {
                foreach($data['data'] as $datum) {

                    $datum['channel_id'] = $channel->id;

                    // 更新订单处理
                    if($order_info = static::exists(['entity_id' => $datum['entity_id'], 'channel_id' => $datum['channel_id'] ])) {
                        if( $datum['updated_at'] > $order_info->updated_at && $datum['status'] != $order_info->status ) {
                            foreach($datum as $key => $value) {
                                if(empty($value)) {
                                    unset($datum[$key]);
                                }
                            }
                            $datum['is_synced'] = 1;
                            $items = [];
                            if(isset($datum['items'])) {
                                $items = $datum['items'];  
                                unset($datum['items']);
                            }
                            static::update($order_info->id, $datum);
                            if(!$order_info->is_crawled && !empty($items)) {
                                foreach($items as $item) {
                                    if(Item::exists($order_info->id, $item['entity_id'])) {
                                        $item['order_id'] = $order_info->id;
                                        Item::insert($item);
                                    }
                                }

                                $update_data = ['is_crawled' => 1];
                                static::update($order_info->id, $update_data);
                            }
                        } 

                    // 新订单处理
                    } else {
                        $datum['created_at']  = date('Y-m-d H:i:s');
                        $datum['modified_at'] = date('Y-m-d H:i:s');
                        $datum['is_synced'] = 1;
                        if(isset($datum['items'])) {
                            $items = $datum['items'];  
                            unset($datum['items']);
                        }
                        $order_id = static::insert($datum);
                        if(!empty($items)) {
                            $status = true;
                            foreach($items as $item) {
                                if(!Item::exists($order_id, $item['entity_id'])) {
                                    $item['order_id'] = $order_id;
                                    if(!Item::insert($item))
                                        $status = false;
                                }
                            }

                            if($status) {
                                $update_data = ['is_crawled' => 1];
                                static::update($order_id, $update_data);
                            }
                        }
                    }
                }
            }
            Channel::update($channel->id, ['synced_at' => $data['synced_at']]);
        }

        return ['status' => 'success', 'message' => ''];
    }

    /**
     * 获取订单所属国家
     *
     * return object
     */
    public static function country() {
        $countries = Cache::get('isystem.order.countries');

        if(!$countries) {
            $countries =  DB::table('orders')->where('shipping_country', '!=', '')
                                             ->group_by('shipping_country')
                                             ->lists('shipping_country');

            Cache::put('app.order.countries', $countries, 3600);
        }

        return $countries;
    }

    /**
     * 获取可以发货的订单
     *
     * @param: $order_ids array 订单ID
     *
     * return array
     */
    public static function shipable($order_ids) {
        return DB::table('orders')->where('status', '=', ORDER_UNSHIPPED)
                                  ->where('is_auto', '=', '0')  // 不是FBA订单
                                  ->where_in('id', $order_ids)
                                  ->lists('id');
    }

    /**
     * 批量发货处理
     *
     * @param: $company  string 快递公司 
     * @param: $method   string 投递方式
     * @param: $tracking array  订单跟踪信息
     *
     * return array
     */
    public static function doBatchShip($company, $method, $tracking) {
        $datetime = date('Y-m-d H:i:s');

        // 验证
        if(empty($company)) return ['status' => 'fail', 'message' => '物流公司为必填项！'];
        foreach($tracking as $tracking_no) {
            if(empty($method) && empty($tracking_no)) return ['status' => 'fail', 'message' => '发货方式和跟踪号填写不完整！'];
        }

        // 入库
        foreach($tracking as $order_id => $tracking_no) {
            $items = Item::get($order_id);
            foreach($items as $item) {
                $data = [
                    'company'     => $company,
                    'method'      => $method,
                    'order_id'    => $order_id,
                    'item_id'     => $item->id,
                    'quantity'    => $item->quantity,
                    'tracking_no' => $tracking_no,
                    'status'      => 0,
                    'created_at'  => $datetime,
                    'modified_at' => $datetime,
                    ];

                Track::insert($data);
            }

            // 更新订单状态
            $data = [
                'is_synced'  => 0,
                'status'     => ORDER_SHIPPED,
                ];
            static::update($order_id, $data);

            // 发送同步队列
            $data = [
                'type'       => 'order',
                'action'     => 'ship',
                'entity_id'  => $order_id,
                'status'     => 0,
                'created_at' => $datetime,
                ];
            static::queueParams($order_id, $data);
            Queue::insert($data);
        }

        return ['status' => 'success'];
    }

    /**
     * 单个订单发货
     *
     * @param: $ship_info array 提交的发货信息
     *
     * return array
     */
    public static function doShip($ship_info) {
        $datetime = date('Y-m-d H:i:s');

        $data = [];
        $total = 0;
        foreach($ship_info as $item_id => $value) {

            $total += $value['sold'];
            if(empty($value['quantity']) || empty($value['order_id'])) continue; // 发货数量为空直接跳过
            $order_id = $value['order_id'];

            if(!empty($value['company']) && (!empty($value['tracking_no']) || !empty($value['method']))) {
                $shiped = Track::itemCount($item_id);
                if($value['quantity'] + $shiped > $value['sold']) return ['status' => 'fail', 'message' => '发货数量不正确！']; // 发货数量大于卖出数量 跳过
                // 入跟踪表
                $data[] = [
                    'item_id'     => $item_id,
                    'company'     => $value['company'],
                    'method'      => $value['method'],
                    'order_id'    => $order_id,
                    'quantity'    => $value['quantity'],
                    'tracking_no' => $value['tracking_no'],
                    'status'      => 0,
                    'created_at'  => $datetime,
                    'modified_at' => $datetime,
                    ];

            } else {
                return ['status' => 'fail', 'message' => '从FBA发货物流公司填Amazon发货方式填FBA跟踪号可不填，其他方式请填完整表单！'];
            }
        } 

        foreach($data as $datum) {
            Track::insert($datum);
        }

        // 更新订单状态
        $shiped = Track::orderCount($order_id);
        if($shiped == $total) {
            $status = ORDER_SHIPPED;
        } else {
            $status = ORDER_PARTIALLYSHIPPED;
        }

        $data = [
            'is_synced'  => 0,
            'status'     => $status,
            ];

        static::update($order_id, $data);

        // 发送同步队列
        $data = [
            'type'       => 'order',
            'action'     => 'ship',
            'entity_id'  => $order_id,
            'status'     => 0,
            'created_at' => $datetime,
            ];

        static::queueParams($order_id, $data);
        Queue::insert($data);

        return ['status'=>'success'];
    }

    /**
     * 订单取消
     *
     * @param: $order_id integer 订单ID
     *
     * return 
     */
    public static function doCancel($order_id) {

        $datetime = date('Y-m-d H:i:s');

        $data = [
            'is_synced' => 0,
            'status'    => ORDER_CANCELED,
            ];
        static::update($order_id, $data);
    
        // 写到队列
        $data = [
            'type'       => 'order',
            'action'     => 'cancel',
            'entity_id'  => $order_id,
            'status'     => 0,
            'created_at' => $datetime,
            ];
        static::queueParams($order_id, $data, 'cancel');
        Queue::insert($data);
    }

    /**
     * 外部渠道发货
     *
     * @param: $orders object 队列订单
     *
     * return void
     */
    public static function outShip($orders) {
        $api = new ChannelAPI($orders['type'], $orders['options']);
        unset($orders['type'], $orders['options']);
        $data = $api->order()->ship($orders);

        $ids = []; // 队列ID
        foreach($orders as $order) {
            $ids[] = $order->id;
        }

        if(!empty($ids)) {
            $data = ['status' => 1];
            DB::table('queues')->where_in('id', $ids)->update($data);
        }
    }

    /**
     * 外部渠道取消订单
     *
     * @param: $orders object 队列数据
     *
     * retrun 
     */
    public static function outCancel($orders) {
        $api = new ChannelAPI($orders['type'], $orders['options']);
        unset($orders['type'], $orders['options']);
        $data = $api->order()->cancel($orders);

        $ids = [];
        foreach($orders as $order) {
            $ids[] = $order->id;
        }

        if(!empty($ids)) {
            $data = ['status' => 1];
            DB::table('queues')->where_in('id', $ids)->update($data);
        }
    }

    /**
     * 内部订单状态修改
     *
     * 包括发货 和 取消订单状态修改
     *
     * @param: $orders object  队列订单
     * @param: $status integer 订单状态
     *
     * return void
     */
    public static function updateAgentChannel($orders, $status) {
        $ids = [];
        foreach($orders as $order) {
            $info = unserialize($order->params);
            if($info['class'] == 'Agent') {
               $agent_id = DB::table('agents')->where('channel_id', '=', $info['channel_id'])->only('id');
               AgentPush::order_status($agent_id, $order->entity_id, $status);
               $ids[] = $order->id;
            }
        }

        if(!empty($ids)) {
            $data = ['status' => 1];
            DB::table('queues')->where_in('id', $ids)->update($data);
        }
    }

    /**
     * 构造渠道队列参数
     *
     * 添加了渠道ID，用于批量操作。
     * 添加了渠道通信API密钥
     *
     * @param: $order_id integer 订单ID
     * @param: $data     array   队列数据
     * @param: $type     string  类型 (发货或者取消订单)
     *
     * return void
     */
    public static function queueParams($order_id, & $data, $type = 'ship') {
        $order = static::info($order_id);
        $channel = Channel::info($order->channel_id);

        $params['order_id'] = $order->entity_id;

        if($type == 'ship') {
            $params['items'] = Track::items($order_id);
        } elseif($type == 'cancel') {
            $params['items'] = Item::get($order_id);
        } 

        $params = [
            'class'      => $channel->type,
            'channel_id' => $channel->id,
            'options'    => unserialize($channel->accredit),
            'params'     => $params,
            ];

        $data['params'] = serialize($params);
        $data['channel_id'] = $channel->id;
    }

    /**
     * 订单状态分类统计
     *
     * @param: $start string 开始时间
     * @param: $end   string 结束时间
     *
     * return array
     */
    public static function statistics_status($start = '', $end = '')
    {
        $sql = 'SELECT COUNT(`id`) as total, SUM(`total_price`) AS price, `status` '
             . 'FROM `orders`';

        if(!empty($start) && !empty($end)){
            $sql = $sql . 'WHERE `updated_at` '
                 . 'BETWEEN \'' . $start . ' 00:00:00\' AND \'' 
                 . $end . ' 23:59:59\'';
        }
        else
        {
            if (!empty($start))
            {
                $sql = $sql . 'WHERE `updated_at` '
                     . 'BETWEEN \'' . $start . ' 00:00:00\' AND \'' 
                     . $start . ' 23:59:59\'';
            }
            if (!empty($end))
            {
                $sql = $sql . 'WHERE `updated_at` '
                     . 'BETWEEN \'' . $start . ' 00:00:00\' AND \'' 
                     . $end . ' 23:59:59\'';
            }            
        }

        $sql = $sql . 'GROUP BY status';

        return DB::query($sql);
    }

    /**
     * 订单购买统计
     *
     * @param: $start string 开始时间
     * @param: $end   string 结束时间
     *
     * return array
     */
    public static function statistics_purchased($start = '', $end = ''){
        $sql = 'SELECT COUNT(`id`) as total, SUM(`total_price`) AS price '
             . 'FROM `orders` ';

        if (!empty($start) && !empty($end))
        {
            $sql = $sql . 'WHERE `purchased_at` '
                 . 'BETWEEN \'' . $start . ' 00:00:00\' AND \'' 
                 . $end . ' 23:59:59\'';
        }
        else
        {
            if (!empty($start))
            {
                $sql = $sql . 'WHERE `purchased_at` '
                     . 'BETWEEN \'' . $start . ' 00:00:00\' AND \'' 
                     . $start . ' 23:59:59\'';
            }
            if (!empty($end))
            {
                $sql = $sql . 'WHERE `purchased_at` '
                     . 'BETWEEN \'' . $start . ' 00:00:00\' AND \'' 
                     . $end . ' 23:59:59\'';
            }            
        }

        return DB::query($sql);
    }

    /**
     * 获取订单的购买时间,最大和最小
     *
     * return array
     */
    public static function get_purchased_at()
    {
        $sql = DB::table('orders');

        return ['min' => $sql->min('purchased_at'), 'max' => $sql->max('purchased_at')];
    }

    /**
     * 获取订单的变更时间,最大和最小
     *
     * return array     
     */
    public static function get_updated_at()
    {
        $sql = DB::table('orders');

        return ['min' => $sql->min('updated_at'), 'max' => $sql->max('updated_at')];
    }

    /**
     * 获取每个国家的订单额
     *
     * return array     
     */
    public static function orders_region()
    {
        $sql = DB::table('orders'); 
        $sql = $sql -> select(['shipping_country', DB::raw('SUM(total_price) as price')])
                    -> group_by('shipping_country')
                    -> get();
        return $sql;
    }

    /**
     * 获取尚未跟踪订单的最早时间和最晚时间
     */
    public static function get_track($channel_id)
    {
        $sql = DB::table('orders')->where('is_auto', '=', '1')
                                  ->where('is_track', '=', '0')
                                  ->where('channel_id', '=', $channel_id);

        return ['min' => $sql->min('purchased_at'), 'max' => $sql->max('purchased_at')];
    }
}
