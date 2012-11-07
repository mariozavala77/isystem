<?php

/**
 * 仓储控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:stock.php  2012年11月06日 星期二 17时40分57秒Z $
 */
class Stock_Controller extends Base_Controller {

    public function action_index() {
    
    
        return View::make('stock.index');
    }


}

?>
