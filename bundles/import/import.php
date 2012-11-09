<?php
/**
 * Excel表格导入处理
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:import.php  2012年11月08日 星期四 16时44分29秒Z $
 */
namespace Import;

class ImportException extends \Exception {}

class Import {

    /**
     * @var object 导入引用模型
     */
    private $_model = null;

    /**
     * 导入构造函数
     *
     * @param: $model string 导入模型名称
     *
     */
    public function __construct($model, $filename) {

        $import = 'Import\Import_' . ucfirst($model);
        $this->_model  = new $import($filename);
    }

    /**
     * 验证
     *
     * return void
     */
    public function valid() {
        $this->_model->valid(); 
    }

    /**
     * 获取验证后的数据
     *
     * return array
     */
    public function data() {
        return $this->_model->data;
    }

}


?>
