<?php
/**
 * 订单统计控制器
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */ 
class Statistics_Controller extends Base_Controller {
    // 统计默认页
    public function action_index()
    {
        return View::make('statistics.main');
    }

    // 订单统计
    public function action_day()
    {
        $start = Input::get('start');
        $start = empty($start)?date('Ym').'01':date('Ymd',strtotime($start));
        $end = Input::get('end');
        $end = empty($end)?date('Ymd'):date('Ymd',strtotime($end));
        $fields = ['purchased_price', 'unshipped_price', 
                   'shipped_price', 'unfulfillable_price', 'date'];
        $data = Statistics::filter($fields)->where('id', '>=', $start)
                                           ->where('id', '<=', $end)
                                           ->get();
        foreach ($data as $key => $value) {
            $value->date = substr($value->date,5);
            $data[$key] = $value;
        }
        return Response::json($data);
    }

    // 订单区域统计
    public function action_region()
    {
        $data = Order::orders_region();
        $color = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F');
        foreach ($data as $key => $value) 
        {
            for($i=0;$i<6;$i++)
            {
                $rand[$i] = $color[rand(0, 15)];
            }
            $value->color = '#'.implode('', $rand);
            $data[$key] = $value;
        }

        return Response::json($data);
    }

    // 首页概况统计
    public function action_main()
    {
        $fields = ['purchased_price', 'unshipped_price', 
                   'shipped_price', 'unfulfillable_price', 'date'];
        $order = Statistics::filter($fields)->where('id', '>=', date('Ymd',time()-1209600))
                                            ->where('id', '<=', date('Ymd'))
                                            ->get();
        foreach ($order as $key => $value) {
            $value->date = substr($value->date,5);
            $order[$key] = $value;
        }
        $data['chart'] = $order;
        $status=Order::statistics_status();
        var_dump($status);
        exit;
        $purchased=Order::statistics_purchased();

        return Response::json($data);
    }
}