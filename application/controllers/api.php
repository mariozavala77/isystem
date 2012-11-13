<?php

/**
 * 接口控制器
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */

class Api_Controller extends Controller {


    private $response_id = 0;

    private $response = '';

    public function __construct(){
        if($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_SERVER['CONTENT_TYPE']) || $_SERVER['CONTENT_TYPE'] != 'application/json'){
            exit;
        }

        $this->response = json_decode(file_get_contents('php://input'), true);
        $this->response_id = $this->response['id'];
    }


    public function action_index(){
        $api = new AgentAPI($this->response['method'],$this->response['params'],$this->response_id);

        return Response::json($api->handle());
    }
}