<?php

/**
 * 任务控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:task.php  2012年11月06日 星期二 11时17分55秒Z $
 */
class Task_Controller extends Base_Controller {
    
    // 任务列表
    public function action_index() {
        return View::make('task.index');
    }

    // 列表
    public function action_filter() {
        $fields = ['tasks.type', 'users.username as form', 'tasks.content', 'tasks.level', 'tasks.created_at', 'tasks.id'];
        $filter = ['to_uid' => $this->user_id, 'parent_id' => 0];
        $tasks  = Tasks::filter($fields, $filter);
        $data   = Datatables::of($tasks)->make();

        return Response::json($data);
    }

    // 任务详细
    public function action_info(){
        $task_id = Input::get('task_id');
        if(empty($task_id)){
            session::flash('tips', '任务不存在');
            return Redirect::back();
        }

        // 任务详细
        $task = Tasks::info($task_id);
        $fields = ['tasks.type', 'users.username as form', 'tasks.content', 'tasks.level', 'tasks.created_at', 'tasks.id'];
        $filter = ['parent_id' => $task->id];
        // 任务的备注
        $task_child  = Tasks::filter($fields, $filter)->order_by('created_at', 'asc')
                                                      ->get();
        // 根据type类型 拉取相应的数据
        $interface = $task->type;
        $task_info = $interface::info($task->entity_id);

        return Response::make('task.' . $interface . '_info')->with('task' ,$task)
                                                             ->with('task_child', $task_child)
                                                             ->with('info', $task_info);

    }

    // 任务转发
    public function action_forward(){

        $task_id = Input::get('task_id');

        $data = [
            'to_uid'  => Input::get('user_id'),
            'is_read' => 0
        ];

        $request = Tasks::update($task_id, $data);

        if($request){
            $request = [ 'status' => 'success', 'message' => '任务转发成功'];           
        }else{
            $request = [ 'status' => 'fail', 'message' => '处理失败'];
        }
    }

    // 插入新任务
    public function action_insert(){
        $data = [
            'from_uid'   => $this->user_id,
            'to_uid'     => Input::get('to_uid'),
            'parent_id'  => Input::get('parent_id'),
            'type'       => Input::get('type'),
            'entity_id'  => Input::get('entity_id'),
            'content'    => Input::get('content'),
            'level'      => Input::get('level'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if(Tasks::insert($data)){
            $request = [ 'status' => 'success', 'message' => '处理成功'];
        }else{
            $request = [ 'status' => 'fail', 'message' => '处理失败'];
        }
    }

    // 任务处理状态更新
    public function action_hidden(){
        $tasks_id = Input::get('tasks_id');
        $data = [
            'handle' => 1,
            'modified_at' => date('Y-m-d H:i:s')
        ];

        if(Tasks::update($tasks_id, $data)){
            $request = [ 'status' => 'success', 'message' => '处理成功'];
        }else{
            $request = [ 'status' => 'fail', 'message' => '处理失败'];
        }
    }
}