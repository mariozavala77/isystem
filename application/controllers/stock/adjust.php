<?php
/**
 * 调仓
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:adjust.php  2012年12月04日 星期二 16时22分25秒Z $
 */
class Stock_Adjust_Controller extends Base_Controller {

    // 调仓处理
    public function action_do_adjust()
    {
        $input = Input::all();
        
        $stock_id   = $input['stock_id'];
        $storage_id = $input['storage_id'];
        $quantity   = $input['quantity'];

        $to_stock_id = (isset($input['to_stock_id'])) ? $input['to_stock_id'] : 0;

        $result = Stock::adjust($stock_id, $storage_id, $quantity, $to_stock_id);

        return Response::json($result);
    }

    // 获取调仓列表
    public function action_entry()
    {
        $stock_id = Input::get('stock_id');
        $fields = ['s1.area', 's1.type', 'sa.quantity', 'sa.created_at', 'sa.id', 'sa.to_stock_id'];
        $filter = ['to_stock_id' => $stock_id, 'status' => '0'];
        $adjust = Stock_Adjust::filter($fields, $filter);
        $data = Datatables::of($adjust)->make();

        return Response::json($data);
    }

    // 确认入仓
    public function action_do_entry()
    {
        $datetime  = date('Y-m-d H:i:s');
        $adjust_id = Input::get('adjust_id');
        $adjust    = Stock_Adjust::info($adjust_id);
        $stock     = Stock::info($adjust->to_stock_id);
        $result    = ['status' => 'fail', 'message' => '调仓失败！'];
        $data      = [
            'sellable'   => $adjust->quantity + $stock->sellable,
            'updated_at' => $datetime,
            ];

        if(Stock::update($stock->id, $data)) {
            $data = [
                'status'     => 1,
                'updated_at' => $datetime,
                ];
            if(Stock_Adjust::update($adjust_id, $data))
                $result = ['status' => 'success', 'message' => '调仓入库成功！'];
        }

        return Response::json($result);
    }

    // 取消入仓
    public function action_do_cancel()
    {
        $datetime  = date('Y-m-d H:i:s');
        $adjust_id = Input::get('adjust_id');
        $adjust    = Stock_Adjust::info($adjust_id);
        $stock     = Stock::info($adjust->from_stock_id);
        $result    = ['status' => 'fail', 'message' => '取消失败！'];
        $data      = [
            'sellable'   => $adjust->quantity + $stock->sellable,
            'updated_at' => $datetime,
            ];

        if(Stock::update($stock->id, $data)) {
            $data = [
                'status'     => 2,
                'updated_at' => $datetime,
                ];
            if(Stock_Adjust::update($adjust_id, $data))
                $result = ['status' => 'success', 'message' => '取消成功！'];
            
        }

        return Response::json($result);
    }
}

