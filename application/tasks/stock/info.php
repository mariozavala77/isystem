<?php

class Task_Stock_Info {

    // 操作入口
    public function __construct( $args ) {

        // 抓取库存信息
        if(empty($args)) {
            $this->_info_all();
        } else {
            foreach ($args as $user_id) {
                $user_id = intval($user_id) ? intval($user_id) : 0;
                $this->_info_one( $user_id );
            }
        }
    }

    // 遍历所有用户获取存库
    private function _info_all() {
        //$user_ids = [1];

        $storage_id = 2;
        //foreach( $user_ids as $user_id ) {
            static::_info_one( $storage_id );
        //}
    }

    // 单个存库
    private function _info_one( $storage_id ) {
        if(empty($storage_id)) return ;

        Stock::info( $storage_id );
    }
}
?>
