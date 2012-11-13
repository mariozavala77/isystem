<?php

/**
 * 产品管理
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:product.php  2012年11月01日 星期四 09时29分33秒Z $
 */
class Product_Controller extends Base_Controller {

    // 产品列表
    public function action_index() {
    
        return View::make('product.list');
    }

    // 产品列表
    public function action_filter() {

        $fields = [ 'p.id as check', 'p.id as id', 'name', 'sku', 'category_id', 'cost', 'min_price', 'max_price', 'status', 'created_at', 'p.id as operate' ];
        $products = Product::filter($fields);
        $data = Datatables::of($products)->make();

        foreach($data['aaData'] as $key => $datum) {
            $product_id = $datum[1];
            $image      = Product_Image::get($product_id, 1);
            $root       = '/uploads/images/products/';
            $data['aaData'][$key][1] = UploadHelper::path($root, $image->image, true);

            $category = Category::info($datum[4]);
            $data['aaData'][$key][4] = $category->name;
        }

        return Response::json( $data );
    }

    // 产品添加
    public function action_add() {
    
        return View::make('product.add');
    }

    // 产品添加处理
    public function action_insert() {
        $data = Input::all();

        // 验证
        
        // 插入
        if(Product::insert( $data )) {
            session::flash('tips', sprintf('产品%s添加成功！', $data['sku']) );

            return Redirect::to('product');
        } else {
            session::flash('tips', '添加失败。');       

            return Redirect::back();
        }
    }

    // 产品编辑
    public function action_edit() {
    
    }

    // 产品编辑处理
    public function action_update() {

    }

    // 导入产品
    public function action_import() {
        return View::make('product.import');
    }

    // 产品导入处理
    public function action_do_import() {
        $file = Input::file('file');

        $filename = UploadHelper::rename($file['name'], 'timestamp');
        $success = Input::upload('file', path('product_import'), $filename);

        $result = ['status' => 'fail', 'message' => '文件上传失败!'];

        if($success) {
            $result = Product::import(path('product_import').$filename);
        }

        return Response::json($result);
    }

    // 产品图片批量上传
    public function action_images() {
        $file = Input::file('file');
        $path = UploadHelper::path(path('product_image'), $file['name']);
        $success = Input::upload('file', $path['dir'], $path['name']);
        if($success) {
            $path = str_replace(path('public'), '', $path['dir'].$path['name']);
            $result = [ 'jsonrpc' => '2.0', 'result' => $path , 'id' => 'id' ];
        } else {
            $result = [ 
                'jsonrpc' => '2.0', 
                'error' => [ 'code' => 103, 'message' => '上传失败'], 
                'id' => 'id'
                ];
        }

        return Response::json( $result );
    }
}
?>
