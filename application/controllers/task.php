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
        if(empty($task)){
            session::flash('tips', '无此任务');
            return Redirect::back();
        }
        $fields = ['id', 'username'];
        $users = User::filter($fields)->get();

        $view = 'task.' . $task->type . '_info';

        return View::make($view)->with('task' ,$task)
                                ->with('users', $users)
                                ->with('user_id', $this->user_id)
                                ->with('nowtime', time());

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

        return Response::json($request);
    }

    // 插入新任务
    public function action_insert(){
        $data = [
            'from_uid'   => $this->user_id,
            'to_uid'     => intval(Input::get('to_uid')),
            'parent_id'  => intval(Input::get('parent_id')),
            'type'       => trim(Input::get('type')),
            'entity_id'  => intval(Input::get('entity_id')),
            'content'    => trim(Input::get('content')),
            'level'      => intval(Input::get('level')),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if(empty($data['to_uid']) || empty($data['type']) || empty($data['entity_id']) || empty($data['content'])){
            return Response::json([ 'status' => 'fail', 'message' => '信息不完整']);
        }

        if(!in_array($data['type'], ['product', 'order', 'product_sale'])){
            return Response::json([ 'status' => 'fail', 'message' => '任务类型正确']);
        }

        if(Tasks::insert($data)){
            $request = [ 'status' => 'success', 'message' => '处理成功'];
        }else{
            $request = [ 'status' => 'fail', 'message' => '处理失败'];
        }

        return Response::json($request);
    }

    // 任务处理状态更新
    public function action_handle(){
        $task_id = intval(Input::get('task_id'));
        $handle = intval(Input::get('handle'));
        $comment = Input::get('comment');

        if(empty($tasks_id) || empty($comment)){
            return Response::json([ 'status' => 'fail', 'message' => '信息不完整']);
        }
        $data = [
            'handle' => $handle,
            'modified_at' => date('Y-m-d H:i:s')
        ];

        if(Tasks::update($tasks_id, $data)){
            $data = [
                'taskid'     => $task_id,
                'uid'        => $this->user_id,
                'comment'    => $comment,
                'created_at' => time(),
            ];
            Tasks_Comment::insert($data);
            $request = [ 'status' => 'success', 'message' => '处理成功'];
        }else{
            $request = [ 'status' => 'fail', 'message' => '处理失败'];
        }

        return Response::json($request);
    }
}