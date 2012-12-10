<?php

/**
 * 库存控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:stock.php  2012年11月06日 星期二 17时40分57秒Z $
 */
class Stock_Controller extends Base_Controller 
{

    // 库存列表
    public function action_index() 
    {
        $fields = ['id', 'area', 'type'];
        $storages = Storage::filter($fields)->get();

        return View::make('stock.list')->with('storages', $storages);
    }

    // 列表
    public function action_filter() 
    {
        $fields = [ 'pe.name', 'storage_id', 'code', 'sellable', 'unsellable', 'stock.created_at', 'stock.id' ];
        $stock = Stock::filter($fields);
        $data = Datatables::of($stock)->make();

        foreach($data['aaData'] as $key => $datum) {
            // 仓库信息
            $storage = Storage::info($datum[1]);
            $data['aaData'][$key][1] = $storage->area . '[' . $storage->type. ']';

            if(!$data['aaData'][$key][0])
                $data['aaData'][$key][0] = '<span class="red">未关联产品池</span>';
        }

        return Response::json($data);
    }

    // 修改库存
    public function action_modify() 
    {
        $input = Input::all();

        $stock_id = $input['stock_id'];
        $data = [
            'sellable'    => $input['sellable'],
            'unsellable'  => $input['unsellable'],
            'modified_at' => date('Y-m-d H:i:s'),
            ];

        if(Stock::update($stock_id, $data)) {
            $result = ['status' => 'success', 'message'=>'修改成功!'];
        } else {
            $result = ['status' => 'fail', 'message' => '修改失败!'];
        }

        return Response::json($result);
    }

    // 其他仓库数据
    public function action_info()
    {
        $input = Input::all();

        $stock = Stock::info($input['stock_id']);

        $field = ['stock.id', 'code', 'sellable', 'unsellable'];
        $filter = ['storage_id' => $input['storage_id'], 'stock.product_id' => $stock->product_id];
        $result = Stock::filter($field, $filter)->get();

        return Response::json($result);
    }

}
