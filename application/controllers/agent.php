<?php

/**
 * 代理商管理
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:agent.php  2012年11月06日 星期二 01时36分41秒Z $
 */
class Agent_Controller extends Base_Controller {

    // 代理商
    public function action_index() {
    
        return View::make('agent.list');
    }

    // 代理商列表
    public function action_filter() {
        
        $fields = [ 'company', 'phone', 'email', 'address', 'created_at', 'id' ];
        $agents = Agent::filter($fields);
        $data = Datatables::of($agents)->make();

        return Response::json($data);
    }

    // 添加代理商
    public function action_add() {
        return View::make('agent.add');
    }

    // 添加操作
    public function action_insert() {

        $secret = Input::get('secret');
        $secret = empty($secret)?Agent::secret():$secret;
        // 先创建渠道后插入
        $data = [
            'company'    => Input::get('company'),
            'phone'      => Input::get('phone'),
            'email'      => Input::get('email'),
            'address'    => Input::get('address'),
            'secret'     => $secret,
            'created_at' => date('Y-m-d H:i:s'),
            ];
        $channel_data = [
            'type'        => 'Agent',
            'name'        => $data['company'],
            'alias'       => $data['company'],
            'description' => $data['company'],
            'accredit'    => serialize(['secret' => $secret]),
            'synced_at'   => date('Y-m-d H:i:s'),

        ];
        $channel_id = Channel::insert($channel_data);
        $data['channel_id'] = $channel_id;
        Agent::insert($data);
    
        return Redirect::to('agent');
    }

    // 编辑用户
    public function action_edit() {
        $agent_id = Input::get('agent_id');
        $agent = Agent::info($agent_id);

        return View::make('agent.edit')->with('agent', $agent);
    }

    // 更新操作
    public function action_update() {

        $agent_id = Input::get('agent_id');

        $secret = Input::get('secret');
        $secret = empty($secret)?Agent::secret():$secret;
        $agent = Agent::info($agent_id);

        $data = [
            'company'    => Input::get('company'),
            'phone'      => Input::get('phone'),
            'email'      => Input::get('email'),
            'address'    => Input::get('address'),
            'secret'     => $secret,
            ];
        $channel_data = [
            'name'        => $data['company'],
            'alias'       => $data['company'],
            'description' => $data['company'],
            'accredit'    => serialize(['secret' => $secret]),

        ];

        Agent::update($agent_id, $data);
        Channel::update($agent->channel_id, $channel_data);
        return Redirect::to('agent');
    }

    // 删除操作
    public function action_delete() {
        $agent_id = Input::get('agent_id');
        $result = [ 'status' => 'success', 'message' => '删除成功！' ];
        $agent = Agent::info($agent_id);
        Agent::delete($agent_id);
        Channel::delete($agent->channel_id);
        return Response::json($result);
    }

    // 生成密钥
    public function action_secret(){
        return Response::json(['status' => 'success', 'message' => Agent::secret()]);
    }
}
?>
