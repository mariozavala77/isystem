<?php

/**
 * 库存控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:stock.php  2012年11月06日 星期二 17时40分57秒Z $
 */
class Stock_Controller extends Base_Controller {

    // 库存列表
    public function action_index() {
        $fields = ['id', 'area', 'type'];
        $storages = Storage::filter($fields)->get();

        return View::make('stock.index')->with('storages', $storages);
    }

    // 列表
    public function action_filter() {
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

}

?>
