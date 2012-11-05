<?php

/**
 * 产品导入
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:import.php  2012年11月01日 星期四 15时48分16秒Z $
 */

class Product_Import_Controller extends Base_Controller {

    // 导入产品
    public function action_index() {
        return View::make('product.import.index');
    }

    // 处理导入产品
    public function action_do_import() {
        $file = Input::file('file');
        $root = path('storage') . 'products' . DS;
        $path = UploadHelper::path($root, $file['name']);
        $success = Input::upload('file', $path['dir'], $path['name']);

        $result = ['status' => 'fail', 'message' => '导入失败!'];

        if($success) {
            if( Import::product( $path['dir'] . $path['name'] ) ) {
                $result = ['status' => 'success', 'message' => '导入成功!'];
            }
        }

        return Response::json($result);
    }

    // 处理导入图片
    public function action_do_image_import() {
    
        return Response::json('2');
    }

}
?>
