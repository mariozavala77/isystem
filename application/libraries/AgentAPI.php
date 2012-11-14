<?php
/**
 * 代理商接口
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class AgentAPIException extends Exception{}
class AgentAPILogException extends Exception{}

class AgentAPI{

    // 标注jsonrpc的版本
    const JSONRPC = '2.0';

    // 请求的url 在发送的时候用到
    const APP_URL = 'http://localhost/ceshi/testeeee.php';
    
    // 请求id 可以是代理商的id 也可以是代理商随机生成
    private $_id = '';
 
    // 传递的参数
    private $_params = '';

    // 当前登录系统的用户
    private $_user_id = 0;

    /**
     * 请求的方法
     *
     * 方法的格式 如 class.function 类.方法
     */
    private $_method ='';
    
    public function __construct($method, $params, $id = 1, $type = 'post'){
        $this->_method = $type . '.' . $method;
        $this->_params = $params;
        $this->_id     = $id;
        if(Sentry::check()) $this->_user_id = Sentry::user()->get('id');
    }

    /**
     * 代理商api处理
     *
     * @return bool|mixed
     */
    public function handle(){
        list($method, $interface, $action) = explode('.', $this->_method);

        if('get' == $method){
            $result = $this->_rpc_get($interface, $action);
        }else{
            $result = $this->_rpc_post($interface, $action);
        }

        return $result;
    }

    /**
     * RPC 错误处理
     *
     * @param $code    string 错误码
     * @param $message string 错误信息
     *
     * @return mixed
     */
    private function _rpc_error($code, $message){
        return $this->_rpc_callback([],['code'=>$code, 'message' => $message]);
    }

    /**
     * 正确的RPC请求处理
     *
     * @param $result array|string 处理结果
     * @return mixed
     */
    private function _rpc_result($result){
        return $this->_rpc_callback($result);
    }

    /**
     * RPC 请求json返回，正确或者错误
     *
     * @param $result array 正确的处理
     * @param $error  array 错误的处理
     * @return mixed
     */
    private function _rpc_callback($result = [], $error = []){
        $response = [
            'jsonrpc' => self::JSONRPC,
            ];
        if(!empty($result)){
            $response['result'] = $result;
        }
        if(!empty($error)){
            $response['error'] = $error;
        }
        $response['id'] = $this->_id;

        return $response;
    }

    /**
     * 处理远程提交的RPC请求
     *
     * @param $interface string 类
     * @param $action    string 方法
     *
     * @return bool
     */
    private function _rpc_post($interface, $action){
        $params = $this->_params;
        try{
            if(empty($params)){

                throw new AgentAPILogException('参数不争取', 1);
            }

            $agent = Agent::info($params['agent_id']);
            
            if(empty($agent)){
                throw new AgentAPILogException('没有获取到代理商信息', 1);
            }

            $params['secret'] = $agent->secret;
            
            $request = [
                'jsonrpc' => self::JSONRPC,
                'method'  => 'get.'.$interface.'.'.$action,
                'params'  => $params,
                'id'      => time()
            ];

            $transport = new Transport();
            $request = $transport->request(self::APP_URL, json_encode($request), 'POST');
            if(!empty($request) || !isset($request['body'])){
                $request = $request['body'];
                $request = json_decode($request,TRUE);
                if(isset($request['error'])){
                    throw new AgentAPILogException($request['error'], 1);
                }
                return TRUE;
            }
        }catch(AgentAPILogException $e){
            $content = [
                'errormsg' =>$e->getMessage(),
                'file'     =>$e->getFile(),
                'line'     =>$e->getLine(),
                'trace'    =>$e->getTraceAsString(),
            ];
            $data = [
                'user_id'    => $this->_user_id,
                'content'    => json_encode($content),
                'created_at' => date('Y-m-d H:i:s')
            ];
            //App\Log\Log::insert($data); // 写入日志
            return FALSE;
        }
    }

    /**
     * RPC 的get处理
     *
     * @param $interface string 类
     * @param $action    string 方法
     *
     * @return mixed
     */
    private function _rpc_get($interface, $action){
        try{
            if(empty($this->_id)){
                throw new AgentAPIException('无效请求', -32600);
            }
            // 传递的参数
            $params = $this->_params;

            // 检测代理商权限
            $agent_id = Agent::check($params['agent_id'], $params['secret']);

            if(empty($agent_id)){
                throw new AgentAPIException('无效代理商', -32001);
            }
            
            $interface = 'AgentAPI_' . $interface;
            $param['value']= $params;
            $result=call_user_func_array([$interface, $action], $param);
            if($result == FALSE){
                throw new AgentAPIException('请求了无效方法', -32601);
                
            }
            return $this->_rpc_result($result);
        }catch(AgentAPIException $e){
            return $this->_rpc_error($e->getCode(), $e->getMessage());
        }catch(AgentAPILogException $e){
            $content = [
                'errormsg' =>$e->getMessage(),
                'file'     =>$e->getFile(),
                'line'     =>$e->getLine(),
                'trace'    =>$e->getTraceAsString(),
            ];
            $data = [
                'user_id'    => $this->_user_id,
                'content'    => json_encode($content),
                'created_at' => date('Y-m-d H:i:s')
            ];
            //Log::insert($data); // 写入日志
            return $this->_rpc_error(-32003, '内容获取失败');
        }catch(Exception $e){
            return $this->_rpc_error(-32003, $e->getMessage());
        }
    }
}