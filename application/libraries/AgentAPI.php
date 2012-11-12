<?php
/**
 * 代理商接口
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class AgentAPI{

    // 标注jsonrpc的版本
    define("JSONRPC", "2.0");

    // 请求的url 在发送的时候用到
    define("API_URL", 'http://localhost/ceshi/test.php');
    
    // 请求id 可以是代理商的id 也可以是代理商随机生成
	private $id = '';
 
    // 传递的参数
	private $params = '';

    /**
     * 请求的方法
     *
     * 方法的格式 如 get.class.function 方式.类.方法
     * 不对外请求 则是get.class.function
     * 对外请求 post.class.function
     */
	private $method ='';
    
	public function __construct($method, $params, $id = 1){
		$this->method = $method;
		$this->params = $params;
		$this->id     = $id;
	}

    /**
     * 代理商api处理
     *
     * @return bool|mixed
     */
    public function handle(){
		list($method, $interface, $action) = explode('.', $this->method);

        if('get' == $method){
            return $this->_rpc_get($interface, $action);
        }else{
            return $this->_rpc_post($interface, $action);
        }
	}

    /**
     * RPC 错误处理
     *
     * @param string $code    错误码
     * @param string $message 错误信息
     * @return mixed
     */
    private function _rpc_error($code, $message){
        return $this->_rpc_callbak([],['code'=>$code, 'message' => $message]);
    }

    /**
     * 正确的RPC请求处理
     *
     * @param array|string $result 处理结果
     * @return mixed
     */
    private function _rpc_result($result){
        return $this->_rpc_callback($result);
    }

    /**
     * RPC 请求json返回，正确或者错误
     *
     * @param array $result 正确的处理
     * @param array $error  错误的处理
     * @return mixed
     */
    private function _rpc_callbak($result = [], $error = []){
        $response = [
            'jsonrpc' => JSONRPC,
            ];
        if(!empty($result)){
            $response['result'] = $result;
        }
        if(!empty($error)){
            $response['error'] = $error;
        }
        $response['id'] = $this->id;

        return $response;
    }

    /**
     * 处理远程提交的RPC请求
     *
     * @param string $interface 类
     * @param string $action    方法
     *
     * @return bool
     */
    private function _rpc_post($interface, $action){
        $params = $this->params;
        
        if(empty($params)){
            // 写日志
            return FALSE;
        }

        $agent = Agent::info($params['agent_id']);
        
        if(empty($agent)){
            // 写日志
            return FALSE;
        }

        $params['secret'] = $agent->secret;
        
        $request = [
            'jsonrpc' => JSONRPC,
            'method'  => 'get.'.$interface.'.'.$action,
            'params'  => $params,
            'id'      => time()
        ];

        $transport = new Transport();
        $request = $transport->request(API_URL, json_encode($request), 'POST');
        if(empty($request)){
            return FALSE;
        }
        $request = $request['body'];
        $request = json_decode($request,TRUE);
        if(isset($request['error'])){
            // 写日志
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * RPC 的get处理
     *
     * @param string $interface 类
     * @param string $action    方法
     *
     * @return mixed
     */
    private function _rpc_get($interface, $action){
        if(empty($this->id)){
            return $this->_rpc_error('-32600', '无效请求');
        }
        // 传递的参数
        $params = $this->params;

        // 检测代理商权限
        $agent_id = Agent::check($params['agent_id'], $params['secret']);

        if(empty($agent_id)){
            return $this->_rpc_error('-32001', '无效代理商');
        }
        try{
            $interface = 'AgentAPI_' . $interface;
            $param['value']= $params;
            $result=call_user_func_array([$interface, $action], $param);
            if($result){
                if(isset($result['status']) && $result['status'] == 'fail'){
                    return $this->_rpc_error('-32004', $result['message']);
                }else{
                    return $this->_rpc_result($result);
                }
            }else{
                return $this->_rpc_error('-32601', '无效方法');
            }
        }catch(Exception $e){
            // 需要写日志
            return $this->_rpc_error('-32003', '内容获取失败');
        }
    }
}