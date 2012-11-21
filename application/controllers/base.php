<?php

class Base_Controller extends Controller {

    public $user_id = null;

    /**
     * 控制器构造函数
     *
     * 初始化常用的变量
     *
     */
    public function __construct() {
        if(Sentry::check()) $this->user_id = Sentry::user()->get('id');
    }

    public function is_ajax(){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Catch-all method for requests that can't be matched.
     *
     * @param  string    $method
     * @param  array     $parameters
     * @return Response
     */
    public function __call($method, $parameters)
    {
        return Response::error('404');
    }

}
