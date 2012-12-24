<?php
/**
 * 物流跟踪 计划任务
 *
 * 目前只适合跟踪 物流状态为1，2跟踪
 * dhl,ups,usps,fedex,tnt,ems,shunfeng
 *
 */
class Track_Task extends Task {

    public function run( $arguments = [] ){
        $table = Track_Queues::filter();
        $count = $table->count();
        if(!empty($count)){
            $track_api = new TrackAPI;
            $page = intval($count/20);
            $page = ($count%20)?$page+1:$page;
            for ($i=0; $i < $page; $i++) { 
                $lists = $table->skip($i * 20)->take(20)->get();
                if(!empty($lists)){
                    $this->_doTrack($track_api, $lists);
                }
            }
            echo 'ok';
        }else{
            echo 'ok';
        }
        exit;
    }

    /**
     * 任务处理
     * 物流状态 3:疑难件,4:已签收,5:已退货。
     *
     */
    public function _doTrack($track_api, $lists){
        foreach($lists as $key=>$value){
            $track = $track_api->get(str_replace('_', '', $value->company), $value->tracking_no);
            if(!empty($track)){
                // 状态是 3，4，5 删除队列
                $filter = ['company'     => $value->company, 
                           'tracking_no' => $value->tracking_no];
                $data = ['status'        => $track['status'], 
                         'tracking_info' => serialize($track['tracking_info'])];
                Track::update($filter, $data);

                if(in_array($track['status'], [3, 4, 5])){
                    Track_Queues::delete($track_queue_id);
                }
            }
        }
    }
}