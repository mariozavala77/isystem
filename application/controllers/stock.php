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
        return View::make('stock.index');
    }

    // 列表
    public function action_filter() {
        $fields = [ 'product_id', 'storage_id', 'code', 'sellable', 'unsellable', 'created_at', 'stock.id' ];
        $stock = Stock::filter($fields);
        $data = Datatables::of($stock)->make();

        foreach($data['aaData'] as $key => $datum) {
            // 仓库信息
            $storage = Storage::info($datum[1]);
            $data['aaData'][$key][1] = $storage->area . '[' . $storage->type. ']';

            // 产品信息
            if($datum[0]) {
                $product = Product::info($datum[0]);
                $product_name = $product->name;
            } else {
                $product_name = '<span class="red">未关联产品池</span>';
            }
            
            $data['aaData'][$key][0] = $product_name;
        }

        return Response::json($data);
    }


}

?>
