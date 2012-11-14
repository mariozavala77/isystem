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
        $fields = ['type', 'content', 'level', 'created_at', 'id'];
        $tasks = Tasks::filter($fields);
        $data = Datatables::of($tasks)->make();

        return Response::json($data);
    }

    // 任务详细
    public function action_info(){
        $task_id = Input::get('task_id');
        if(empty($task_id)){
            session::flash('tips', '任务不存在');
            return Redirect::back();
        }
        
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
}

?>
