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
