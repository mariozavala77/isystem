<?php
/**
 * 同步FBA物流信息
 */
class Task_Order_Track{

    public function __construct($args) {
        $channel = Channel::filter(['id', 'type','accredit'],['type'=>'Amazon', 'id' => '1'])->get();
        if(empty($args['start']) || empty($args['end'])){
            $this->get_all($channel);
        }else{
            foreach ($channel as $key => $value) 
            {
                $min = strtotime($args['start']);
                $max = strtotime($args['end']);
                $this->batch_processing($value->type, $value->accredit, $min, $max);
            }            
        }
    }

    public function get_all($channel){
        foreach ($channel as $key => $value) {
            $day = Order::get_track($value->id);
            if(!empty($day['min']) && !empty($day['max'])){
                $min = strtotime($day['min']);
                $max = strtotime($day['max']);

                $this->batch_processing($value->type, $value->accredit, $min, $max);
            }
        }
    }

    public function get_day($type, $accredit, $start, $end){
        $start = date('c', $start);
        $end = date('c', $end);
        $api = new ChannelAPI($type, unserialize($accredit));
        $report = $api->report();
        $ReportType = $report::AMAZON_FULFILLED_SHIPMENTS;
        $params = ['StartDate'=> $start, 
                   'EndDate' => $end, 
                   'ReportType' => $ReportType];
        $ReportRequestId = $report->RequestReport($params);
        echo 'ReportRequestId=>'.$ReportRequestId;
        if (!empty($ReportRequestId))
        {
            $params =['ReportRequestIdList.Id.1' => $ReportRequestId, 
                      'ReportTypeList.Type.1' => $ReportType,
                      ];
            $GeneratedReportId = $report->GetReportRequestList($params);
            echo ',GeneratedReportId=>'.$GeneratedReportId;
            if (!empty($GeneratedReportId))
            {
                $data = $report->GetReport(['ReportId' => $GeneratedReportId]);
                $this->handle($data);
            }
        }
        echo "\n";
    }

    /**
     * 根据物流信息，插入到物流的跟踪的表中
     *
     * @param: $data string 抓取的物流信息
     */
    public function handle($data)
    {
        $data = explode("\n", $data);
        unset($data[0]);
        foreach ($data as $key => $value)
        {
            $value = explode("\t",$value);
            $value[0] = trim($value[0]);
            $value[42] = trim($value[42]);
            $value[43] = trim($value[43]);
            if(!empty($value[0]) && !empty($value[42]) && !empty($value[43]))
            {
                if($value[42]=='SMARTPOST')
                {
                    $value[42] = 'fedexus';
                }
                if($value[42]=='SMARTMAIL')
                {
                    $value[42] = 'dhlglobalmail';
                }
                $value[42] = strtolower($value[42]);
                $this->push_track($value[0], $value[42], $value[43]);
            }
        }
    }

    /**
     * FBA发货物流处理
     *
     * @param: $entity_id   string 亚马逊订单id
     * @param: $company     string 物流公司
     * @param: $tracking_no string 物流追踪号
     * 
     */
    public function push_track($entity_id, $company='', $tracking_no='')
    {
        $order = Order::exists(['entity_id' => $entity_id]);
        
        if(empty($order))
        {
            return false;
        }

        $items = Item::get($order->id);

        if(!empty($items))
        {
            foreach ($items as $key => $value)
            {
                $filter = ['order_id' => $order->id, 'item_id' => $value->id];
                $track = Track::exists($filter);
                $track_data = ['company'     => $company, 
                               'order_id'    => $order->id,
                               'item_id'     => $value->id,
                               'quantity'    => $value->quantity,
                               'tracking_no' => $tracking_no,
                               'modified_at' => date('Y-m-d H:i:s')
                               ];
                if(empty($track))
                {
                    $track_data['created_at'] = $track_data['modified_at'];
                    $track_data['status'] = 0;
                    Track::insert($track_data);
                }
                else
                {
                    Track::update(['id' => $track->id], $track_data);
                }
            }
            Track_Queues::insert(['company' => $company, 'tracking_no' => $tracking_no]);
        }

        Order::update( $order->id, ['is_track' => 1] );
    }

    public function batch_processing($type, $accredit, $min, $max)
    {
        $rang = $max - $min;
        $spacing = 86400*15;
        $k = intval($rang/$spacing);
        $k = $rang%$spacing?$k+1:$k;
        for($i=0;$i<$k;$i++)
        {
            $start = ($i*$spacing)+$min;
            $end = $start+$spacing;
            $end = ($end>$max)?$max:$end;
            $this->get_day($type, $accredit, $start, $end);
        }
    }
}