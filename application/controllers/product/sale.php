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

    // 审核信息
    public function action_check(){
        $sale_id = Input::get('sale_id');
        $channel_id = Input::get('channel_id');
        $product_id = Input::get('product_id');
        $agent_id = Input::get('agent_id');
        $status = Input::get('status');
        $task_id = Input::get('task_id');
        
        if(empty($sale_id) || empty($channel_id) || empty($product_id) || empty($agent_id) || empty($status)){
            return Response::json(['status' => 'fail', 'message' => '信息不完整']);
        }

        // 远程通信的参数
        $param = ['agent_id' => $agent_id, 'channel_id' => $channel_id, 'product_id' => $product_id, 'status' => $status];
        // 数据库更新值
        $data = ['status' => $status];

        if(1==$status){
            
        }else{
            // 不通过的原因
            $msg = Input::get('msg');
            if(empty($msg)){
                return Response::json(['status' => 'fail', 'message' => '请填写审核不通过的原因']);       
            }
        }
    }

    // 编辑销售信息
    public function action_edit(){

    }

    // 更新销售
    public function action_update(){

    }

    // 代理商认购商品信息
    public function action_info(){
        $sale_id = intval(Input::get('sale_id'));

        if(empty($sale_id)){
            return Response::json(['status' => 'fail', 'message' => '信息不完整']);
        }

        $sale_info = Product_Sale::info($sale_id);
        if(empty($sale_info)){
            return false;
        }

        $channel = Channel::info($sale_info->channel_id);
        $sale_info->channel = $channel->name;
        $sale_info->agent = Agent::info($sale_info->agent_id);
        return Response::json(['status' => 'success', 'message' => $sale_info]);
    }
}