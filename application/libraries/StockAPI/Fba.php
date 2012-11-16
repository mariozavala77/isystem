<?php

/**
 * FBA仓储API
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:fba.php  2012年11月16日 星期五 11时59分00秒Z $
 */


class StockAPI_Fba {

    private $_options = null;

    public function __construct($options) {
        $this->_options = $options;
    }

    public function info() {
        $spider = new StockAPI_Fba_Info($this->_options);

        return $spider->info();
    }
}


?>
