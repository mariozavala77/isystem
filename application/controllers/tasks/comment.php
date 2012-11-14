<?
class Task_Comment_Controller extends Base_Controller {

    // 留言列表
    public function action_filter(){
        $fields = ['type', 'name', 'alias', 'description', 'status', 'id'];
        $channel = Channel::filter($fields)->get();
        
        return Response::json($data);
    }

    // 新增留言
    public function action_insert(){
    	$data = [
    		'taskid' => Input::get('task_id'),
    		'uid' => $this->user_id,
    		'time' => time(),
    	];
    }
}