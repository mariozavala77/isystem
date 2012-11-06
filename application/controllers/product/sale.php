<?php

/**
 * 产品销售控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:sale.php  2012年11月06日 星期二 15时38分29秒Z $
 */
class Product_Sale_Controller extends Base_Controller {

    // 销售列表
    public function action_index() {
        return View::make('product.sale.index');
    }

    // 列表
    public function action_filter() {
        $fields = [ 'id', 'title', 'sku', 'price', 'channel_id', 'agent_id', 'status', 'id as operate'];
        $products = Product_Sale::filter($fields);
        $data = Datatables::of($products)->make();

        return Response::json($data);
    
    }
}

?>
