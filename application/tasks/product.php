<?php

/**
 * 产品任务
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:product.php  2012年12月12日 星期三 18时10分01秒Z $
 */
class Product_Task extends Task
{
    public function run( $arguments = [] ) {

        if(!count($arguments)) {
            $this->_help();
            exit;
        }

        $command = (isset($arguments[0]) && $arguments[0] !=='') ? $arguments[0] : 'help';
        $args = array_slice($arguments, 1);

        switch ($command) {
            case 'listing': // 渠道发货
            case 'l':
                new Task_Product_Listing($args);
                break;
            default:
                $this->_help();
                break;
        }
    } 

    /**
     * help
     */
    private function _help() {
        echo '帮助 ：';
        echo "\t产品 <命令> [参数] [ID...]\n";
        echo "命令:\n";
        echo "\tl/listing\t上架\n";
    }

}

?>
