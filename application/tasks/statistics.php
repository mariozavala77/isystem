<?php
/**
 * 统计任务
 *
 */
class Statistics_Task extends Task{
    
    public function run( $arguments = [] ){
        if(empty($arguments)){
            $this->_handle();
            exit;
        }

        $command = (isset($arguments[0]) && $arguments[0] !=='') ? $arguments[0] : 'help';
        $args = array_slice($arguments, 1);

        switch ($command) {
            case 'purchasing':
                # code...
                break;
            case 'help':
                $this->_help();
            break;
            default:
                $this->_handle();
                break;
        }
    }

    /**
     * 帮助
     */
    private function _help(){
        $html = ['统计任务帮助 ：'];
        array_push($html, 'purchasing'."\t".'新增订单统计');
        array_push($html, 'purchasing'."\t".'新增订单统计');
        array_push($html, 'purchasing'."\t".'新增订单统计');
        echo implode("\r\n\t", $html);
    }

    private function _handle(){
        $purchased_at = Order::get_purchased_at();
        $updated_at = Order::get_updated_at();
        $null = '0000-00-00 00:00:00';
        if ($purchased_at['min'] == $null && $updated_at['min'] != $null)
        {
            $min = $updated_at['min'];
        }

        if($purchased_at['min'] != $null && $updated_at['min'] == $null)
        {
            $min = $purchased_at['min'];
        }
        if($purchased_at['min'] != $null && $updated_at['min'] != $null)
        {
            $min = min($purchased_at['min'], $updated_at['min']);
        }
        if ($purchased_at['max'] == $null && $updated_at['max'] != $null)
        {
            $max = $updated_at['max'];
        }

        if ($purchased_at['max'] != $null && $updated_at['max'] == $null)
        {
            $max = $purchased_at['max'];
        }
        if ($purchased_at['max'] != $null && $updated_at['max'] != $null)
        {
            $max = max($purchased_at['max'], $updated_at['max']);
        }
        $min = substr($min,0,10);
        $statistics_date = Statistics::max_date();
        $min = $statistics_date?max($statistics_date, $min):$min;
        $max = substr($max,0,10);

        if ($min == $max)
        {
            $this->_day($min);
        }
        else
        {
            $min_time = strtotime($min);
            $max_time = strtotime($max);
            $day = ($max_time-$min_time)/86400;
            for ($i=0; $i <= $day; $i++) 
            {
                $times = $min_time+$i*86400;
                $this->_day($times);
            }
        }
    }

    private function _day($times){
        $date = date('Y-m-d', $times);
        $statistics_purchased = Order::statistics_purchased($date);
        $statistics_purchased = $statistics_purchased[0];
        if (!empty($statistics_purchased->price))
        {
            $statistics['purchased'] = $statistics_purchased->total;
            $statistics['purchased_price'] = $statistics_purchased->price;
        }

        $statistics_status = Order::statistics_status($date);

        if (!empty($statistics_status))
        {
            $field = [1 => 'pending', 2 => 'unshipped', 3 => 'shipped', 
                      4 => 'partial_shipment', 5 => 'canceled', 6 => 'unfulfillable'];
            
            foreach ($statistics_status as $value) 
            {
                $key = $field[$value->status];
                $statistics[$key] = $value->total;
                $statistics[$key . '_price'] = $value->price;        
            }
        }

        if (empty($statistics))
        {
            return false;
        }

        $id = date('Ymd', $times);

        if (Statistics::exists($id))
        {
            Statistics::update($id, $statistics);
        }
        else
        {
            $statistics['id'] = $id;
            $statistics['date'] = $date;
            Statistics::insert($statistics);
        }
    }
}