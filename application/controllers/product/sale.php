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
        return View::make('product.sale.index');
    }

    // 列表
    public function action_filter() {
        $fields = [ 'id', 'title', 'sku', 'price', 'channel_id', 'agent_id', 
                    'sold', 'id as operate'];
        $filter = ['parent_id' => 0];
        $products = Product_Sale::filter($fields);
        $data = Datatables::of($products)->make();
        $channels = [];
        $agents   = [];
        foreach($data['aaData'] as $key=>$value){
            $channels[$value[4]] = $value[4];
            $agents[$value[5]]   = $value[5];
        }
        $agents = Agent::filter(['id', 'company'])->where_in('id', $agents)->get();
        foreach($agents as $key=>$value){
            $agent[$value->id] = $value->company;
        }
        $agents = $agent;
        $channels = Channel::filter(['id', 'name'])->where_in('id', $channels)->get();
        foreach($channels as $key=>$value){
            $channel[$value->id] = $value->name;
        }
        $channels = $channel;

        foreach($data['aaData'] as $key=>$value){
            $value[8] = empty($value[4])?'暂无':$channels[$value[4]];
            $value[9] = empty($value[5])?'暂无':$agents[$value[5]];
            $data['aaData'][$key] = $value;
        }
        return Response::json($data);
    }

    // 审核信息
    public function action_check(){
        $sale_id = Input::get('sale_id');
        $status = Input::get('status');
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

        $sale_info = Product_Sale::info($sale_id);

        if(empty($sale_info)){
            return Response::json(['status' => 'fail', 'message' => '没有找到销售产品的记录']);
        }

        $sale_info->validation = empty($sale_info->validation)?'':json_decode($sale_info->validation);

        $channel = Channel::info($sale_info->channel_id);
        
        $sale_info->channel = $channel->name;
        $sale_info->agent = Agent::info($sale_info->agent_id);

        return Response::json(['status' => 'success', 'message' => $sale_info]);
    }
}