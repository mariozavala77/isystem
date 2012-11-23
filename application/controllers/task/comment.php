<?php
/**
 * 任务留言控制器
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Task_Comment_Controller extends Base_Controller {

    // 留言列表
    public function action_filter(){
        $fields = ['tc.uid', 'users.username', 'tc.comment',
                   'tc.created_at', 'tc.id'];
        $filter = ['tc.taskid'=>Input::get('taskid')];
        $comment = Tasks_Comment::filter($fields, $filter)->get();
        
        return Response::json($comment);
    }

    // 新增留言
    public function action_insert(){
    	$data = [
    		'taskid' => Input::get('task_id'),
    		'uid' => $this->user_id,
            'comment' => Input::get('comment'),
    		'created_at' => time(),
    	];

        if(empty($data['comment']) or empty($data['taskid'])){
            return Response::json([ 'status' => 'fail', 'message' => '请填写备注的内容']);
        }

        if(Tasks_Comment::insert($data)){
            $fields = ['tc.uid', 'users.username', 'tc.comment',
                   'tc.created_at', 'tc.id'];
            $comment = Tasks_Comment::filter($fields)->get();
            $return = [ 'status' => 'success', 'message' => $comment];
        }else{
            $return = [ 'status' => 'fail', 'message' => '备注填写有误！'];
        }
        
        return Response::json($return);
    }
}