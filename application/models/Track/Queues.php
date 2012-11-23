<?php
/**
 * 订单跟踪队列模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */

class Track_Queues {
    
    public static function filter(){
        return DB::table('track_queues')->select();
    }

    public static function delete($track_queue_id){
        return DB::table('track_queues')->where('id', '=', $track_queue_id)
                                        ->delete();
    }

    public static function insert($data){
        return DB::insert($data);
    }
}