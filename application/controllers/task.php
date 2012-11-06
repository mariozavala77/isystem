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
}

?>
