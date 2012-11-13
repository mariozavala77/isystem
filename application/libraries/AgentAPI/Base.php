<?php
class AgentAPI_Base{

	/**
	 * 获取必须参数
	 *
	 * @param: $fields array 必须参数的索引
	 * @param: $params array 所有参数
	 *
	 * return array
	 */
	public static function requeryParams($fields, $params){
		$data = [];
		foreach ($fields as $field) {
			if(isset($params[$field]) && !empty($params[$field])){
				$data[$field] = $params[$field];
			}else{
				throw new Exception($field . 'is_null');
			}
		}

		return $data;
	}
}