<?php

/**
 * 产品销售控制器
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:sale.php  2012年11月06日 星期二 15时38分29秒Z $
 */
class Product_Sale_Controller extends Base_Controller {

    // 销售列表
    public function action_index() {
        return View::make('product.sale.list');
    }

    // 列表
    public function action_filter() {
        $fields = [ 'title', 'price', 'agent_id', 
                    'created_at', 'id as operate'];
        $products = Product_Sale::filter($fields);
        $data = Datatables::of($products)->make();
        $agents   = [];
        foreach($data['aaData'] as $key=>$value){
            $agents[$value[2]]   = $value[2];
        }
        $agents = Agent::filter(['id', 'company'])->where_in('id', $agents)->get();
        foreach($agents as $key=>$value){
            $agent[$value->id] = $value->company;
        }
        $agents = $agent;

        foreach($data['aaData'] as $key=>$value){
            $value[5] = empty($value[2])?'暂无':$agents[$value[2]];
            $data['aaData'][$key] = $value;
        }
        return Response::json($data);
    }

    // 审核信息
    public function action_check(){
        $sale_id = Input::get('sale_id');
        $status  = Input::get('status');
        $task_id = Input::get('task_id');
        $msg = Input::get('msg');
        $msg = ($status==1?'产品信息通过审核。':'产品信息没有通过审核。').$msg;

        $info = Product_Sale::info($sale_id);

        if(empty($info)){
            return Response::json(['status' => 'fail', 'message' => '没有找到销售产品的记录']);
        }

        if(!empty($info->status)){
            return Response::json(['status' => 'fail', 'message' => '销售记录已经审核过了']);
        }

        if(1==$status){

            $validation = json_decode($info->validation);
            $sale_data  = [
                'status'            => $status,
                'price'             => $validation->price,
                'title'             => $validation->title,
                'keywords'          => $validation->keywords,
                'short_description' => $validation->short_description,
                'description'       => $validation->description,
            ];
        }else{
            $sale_data = [
                'status' => $status,
            ];
        }

        if(Sale::update($sale_id, $sale_data)){
            if(!empty($task_id)){
                $task_data = [
                    'handle'      => 1,
                    'modified_at' => date('Y-m-d H:i:s'),
                ];

                $tasks_comment = [
                    'uid'        => $this->user_id,
                    'taskid'     => $task_id,
                    'comment'    => $msg,
                    'created_at' => time()
                ];
                Tasks::update($task_id, $task_data);
                Tasks_Comment::insert($tasks_comment);                
            }
            $params = [
                       'product_id' =>$info->product_id,
                       'channel_id' =>$info->channel_id,
                       'agent_id'   =>$info->agent_id,
                       'status'     =>$status
                      ];
            if($status==2){
                $params['message'] = $msg;
            }
            // 代理商在售产品信息传递
            $method = '';
            $api = AgentAPI($method, $params);
            $apie->handle();
            // 后期加入 平台同步
            $request = [ 'status' => 'success', 'message' => '信息审核成功'];
        }else{
            $request = [ 'status' => 'fail', 'message' => '信息审核失败'];
        }

        return Response::json($request);
    }

    // 编辑销售信息
    public function action_edit(){
        $sale_id = Input::get('sale_id');

        $info = Product_Sale::info($sale_id);
        var_dump($info);
    }

    // 更新销售
    public function action_update(){

    }

    // 产品上架
    public function action_sale(){
        $sale_id = Input::get('sale_id');
        $task_id = Input::get('task_id');
        $info = Product_Sale::info($sale_id);

        if(empty($info)){
            return Response::json(['status' => 'fail', 'message' => '没有找到销售产品的记录']);
        }

        if($info->status!=1){
            return Response::json(['status' => 'fail', 'message' => '产品信息没有审核或者没有通过']);
        }

        if($info->sold==1){
            return Response::json(['status' => 'fail', 'message' => '产品信息已经上架']);
        }

        $sale_data = [
            'status' => 1,
        ];

        if(Sale::update($sale_id, $sale_data)){
            if(!empty($task_id)){
                $task_data = [
                    'handle'      => 1,
                    'modified_at' => date('Y-m-d H:i:s'),
                ];

                $tasks_comment = [
                    'uid'        => $this->user_id,
                    'taskid'     => $task_id,
                    'comment'    => '代理商产品下架',
                    'created_at' => time()
                ];
                Tasks::update($task_id, $task_data);
                Tasks_Comment::insert($tasks_comment);                
            }
            $params = [
                       'product_id' => $info->product_id,
                       'channel_id' => $info->channel_id,
                       'agent_id'   => $info->agent_id,
                       'status'     => 1
                      ];
            if($status==2){
                $params['message'] = $msg;
            }
            // 代理商在售产品信息传递
            $method = '';
            $api = AgentAPI($method, $params);
            $apie->handle();
            $request = [ 'status' => 'success', 'message' => '信息审核成功'];
        }else{
            $request = [ 'status' => 'fail', 'message' => '信息审核失败'];
        }

        return Response::json($request);

    }

    // 产品下架
    public function action_off(){
        $sale_id = Input::get('sale_id');
        $task_id = Input::get('task_id');
        $info = Product_Sale::info($sale_id);
        if(empty($info)){
            return Response::json(['status' => 'fail', 'message' => '没有找到销售产品的记录']);
        }

        if($info->status!=1){
            return Response::json(['status' => 'fail', 'message' => '产品信息没有审核或者没有通过']);
        }

        $sale_data = [
            'status' => 1,
        ];
        
        if(Sale::update($sale_id, $sale_data)){
            if(!empty($task_id)){
                $task_data = [
                    'handle'      => 1,
                    'modified_at' => date('Y-m-d H:i:s'),
                ];

                $tasks_comment = [
                    'uid'        => $this->user_id,
                    'taskid'     => $task_id,
                    'comment'    => '代理商产品下架',
                    'created_at' => time()
                ];
                Tasks::update($task_id, $task_data);
                Tasks_Comment::insert($tasks_comment);                
            }
            $params = [
                       'product_id' => $info->product_id,
                       'channel_id' => $info->channel_id,
                       'agent_id'   => $info->agent_id,
                       'status'     => 1
                      ];
            if($status==2){
                $params['message'] = $msg;
            }
            // 代理商在售产品信息传递
            $method = '';
            $api = AgentAPI($method, $params);
            $apie->handle();
            $request = [ 'status' => 'success', 'message' => '信息审核成功'];
        }else{
            $request = [ 'status' => 'fail', 'message' => '信息审核失败'];
        }

        return Response::json($request);
    }

    // 代理商认购商品信息
    public function action_info(){
        $sale_id = intval(Input::get('sale_id'));

        if(empty($sale_id)){
            return Response::json(['status' => 'fail', 'message' => '信息不完整']);
        }

        $sale_info = Product_Sale_Sku::info($sale_id);

        if(empty($sale_info)){
            return Response::json(['status' => 'fail', 'message' => '没有找到销售产品的记录']);
        }

        $sale_info->validation = empty($sale_info->validation)?'':json_decode($sale_info->validation);

        $channel = Channel::info($sale_info->channel_id);
        
        $sale_info->channel = $channel->name;
        $sale_info->agent = Agent::info($sale_info->agent_id);

        return Response::json(['status' => 'success', 'message' => $sale_info]);
    }

    // 产品映射
    public function action_mapping(){
        $product_id = Input::get('product_id');
        $sku = Input::get('sku');
        $task_id = Input::get('task_id');
        if(Product_Sale::mapping($sku, $product_id)){
            if( ! empty($task_id)){
                $data = ['handle'      => 1, 
                         'modified_at' => date('Y-m-d H:i:s')
                        ];
                Tasks::update($task_id, $data);
                $data = ['uid'        => $this->user_id,
                         'comment'    => '产品已经映射',
                         'taskid'     => $task_id,
                         'created_at' => time()
                        ];
                Tasks_Comment::insert($data);
            }
            $result = ['status' => 'success', 'message' => 'ok'];
        }else{
            $result = ['status' => 'fail', 'message' => 'wrong'];
        }
        return Response::json($result);
    }
}
