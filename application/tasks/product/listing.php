<?php
/**
 * 产品上架
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:listing.php  2012年12月12日 星期三 18时12分45秒Z $
 */
class Task_Product_Listing
{
    const LISTING = 1;

    // 产品上架
    public function __construct($args)
    {
        // 上架所有
        $this->_listingAll();
    }

    // 遍历上架
    public function _listingAll()
    {
        // 同步外部订单
        $out_channels = Config::get('application.out_channels');
        foreach($out_channels as $channel_id) {
            $fields = ['id', 'entity_id', 'params'];
            $filter = ['type' => 'product' , 'action' => 'listing', 'channel_id' => $channel_id, 'status' => 0 ];
            $queues = Queue::filter($fields, $filter)->get();
            if(isset($queues[0])) {
                $params = $queues[0]->params;
                $params = unserialize($params);
                $queues['type'] = $params['class'];
                $queues['options'] = $params['options'];
            }

            if(!empty($queues)) {
                Product_Sale_Sku::outListing($queues);
            }
        }

        // 同步内部订单
        $fields = ['id', 'entity_id', 'params'];
        $filter = ['type' => 'product', 'action' => 'listing', 'status' => 0]; 
        $queues = Queue::filter($fields, $filter)->get();

        // 过滤外部渠道
        foreach($queues as $index => $queue) {
            if(in_array($queue->channel_id, $out_channels)) {
                unset($queues[$index]);
            }
        }

        if(!empty($queues)) Product_Sale_Sku::updateAgentChannel($queues, LISTING);

    }

}

?>
