<?php

/**
 * 产品导入
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:product.php  2012年11月08日 星期四 17时45分55秒Z $
 */
namespace Import;

use Config;
use Validator;
use DB;

class Import_Product extends Import_Base {

    /**
     * @var array 验证配置
     */
    private $_config = [];

    /**
     * @var string 导入文件名
     */
    private $_filename = null;

    /**
     * @var array 通过验证数据
     */
    public $data =[];

    /**
     * 构造函数
     *
     */
    public function __construct($filename) {
        $this->_config = [
            'language' => Config::get('import::product.language'),
            'keys'     => Config::get("import::product.keys"),
            'head'     => Config::get("import::product.head"),
            'rules'    => Config::get("import::product.rules"),
            'messages' => Config::get("import::error"),
            ];

        $this->_filename = $filename;
    }

    /**
     * 验证
     *
     * @param: $data array 需要验证的数据
     *
     * @throws: ImportException
     *
     * return void
     */
    public function valid() {

        $data = $this->read($this->_filename);

        $head = array_shift($data);
        if($head !== $this->_config['head']) Throw new ImportException('这不是一个标准的产品导入文档');

        // 转换验证
        foreach($data as $key => $datum) {
            $current_row = $key + 1;

            if(!$datum[0]) continue;

            $datum = $this->trim($datum);
            $datum = array_combine($this->_config['keys'], $datum);
            if(!$datum) {
                Throw new ImportException("第{$current_row}行数据长度不匹配。");
            }
            
            // 验证语言
            if(!in_array($datum['language'], $this->_config['language']))
                Throw new ImportException("第{$current_row}行数据的语言还没有被支持");

            // 获取验证规则
            $rules = $datum['language'] == 'cn' ? 
                $this->_config['rules'][$datum['language']] : 
                $this->_config['rules']['default'];

            foreach($rules as $key => $rule) {
                unset($rules[$key]);
                $rules[$this->_config['keys'][$key]] = $rule;
            }

            $valid = Validator::make($datum, $rules, $this->_config['messages']);

            if($valid->fails()) {
                $message = str_replace(' ', '_', $valid->errors->first());
                $exception = "亲，第{$current_row}行的" . str_replace($this->_config['keys'], $this->_config['head'], $message);
                Throw new ImportException($exception);
            } else {
                // 额外验证
                $this->_extraValid($current_row, $datum);

                // 转换成入库数据
                $category = $datum['category_id'];
                $datum['category_id'] = DB::table('category')->where('name', '=', $category)->only('id');
                if(!$datum['category_id']) Throw new ImportException("OMG，第{$current_row}行分类“{$category}”不存在。");

                $supplier = $datum['supplier_id'];
                $datum['supplier_id'] = DB::table('suppliers')->where('company', '=', $supplier)->only('id');
                if(!$datum['supplier_id']) Throw new ImportException("OMG，第{$current_row}行供应商“{$supplier}”不存在。");

                $developer = $datum['devel_id'];
                $datum['devel_id'] = DB::table('users')->where('username', '=', $developer)->only('id');
                if(!$datum['devel_id']) Throw new ImportException("OMG，第{$current_row}行开发人“developer}”不存在。");

                $datum['images'] = explode(';', rtrim($datum['images'], ';'));
            }

            $this->data[] = $datum;
        }
    }

    /**
     * 额外验证
     *
     * 这里可以通过注册新的验证实现额外的验证 待改
     *
     * @param: $row  integer 行号
     * @param: $data array   数据
     *
     * @throws ImportException
     */
    private function _extraValid($row, $data) {

        // 价格验证
        if($data['cost'] > $data['min_price']) Throw new ImportException("第{$row}行的最低价格低于成本价。");
        if($data['min_price'] > $data['max_price']) Throw new ImportException("第{$row}行的最高价格低于最低价格。");

        // 产品记录验证
        $exists = DB::table('products as p')->left_join('products_extension as pe', 'p.id', '=', 'pe.product_id')
                                       ->where('language', '=', $data['language'])
                                       ->where('sku', '=', $data['sku'])->only('p.id');

        if($exists) Throw new ImportException("第{$row}行的这个语言版本已经存在。");

        // 图片验证
        $images = rtrim($data['images'], ';');
        $images = explode(';', $images);
        foreach($images as $image) {
            $root = path('public') . 'uploads'. DS . 'images'. DS . 'products' . DS;
            $filename = \UploadHelper::path($root, $image, true);
            if(!file_exists($filename)){
                $exception = "哎呀，第{$row}行图片[{$image}]没有上传，请再传一次。";
                Throw new ImportException($exception);
            }
        }
    }
}
?>
