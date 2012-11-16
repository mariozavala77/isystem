<?php

class Stock_Task extends Task {


    public function run ( $arguments = [] ) {
    
        if (! count($arguments)) {
            $this->_help();
            exit;
        }

        $command = (isset($arguments[0]) && $arguments[0] !=='') ? $arguments[0] : 'help';
        $args = array_slice($arguments, 1);

        switch ($command) {
            case 'info':
            case 'i':
                new Task_Stock_Info($args);
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
        echo "\tstock <命令> [参数] [库存ID...]\n";
        echo "命令:\n";
        echo "\ti/info\t获取库存信息\n";

    }
}
?>
