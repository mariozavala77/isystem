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

        $fields = [ 
            'p.id as check', 'p.id as id', 'name', 'sku', 'category_id', 'cost', 'min_price', 
            'max_price', 'status', 'created_at', 'p.id as operate' 
            ];

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

        $fields = [ 'id', 'name' ];
        $filter = [ 'parent_id' => 0 ];
        $categories = Category::filter($fields, $filter)->get();

        $fields = ['id', 'company'];
        $suppliers = Supplier::filter($fields)->get();

        $fields = ['id', 'username'];
        $users = User::filter($fields)->get();
    
        return View::make('product.add')->with('categories', $categories)
                                        ->with('suppliers', $suppliers)
                                        ->with('users', $users);
    }

    // 产品添加处理
    public function action_insert() {
        $input = Input::all();

        // 验证

        
        // 插入product表
        $data = [
            'sku'         => $input['sku'],
            'cost'        => $input['cost'],
            'price'       => $input['price'],
            'category_id' => $input['category_id'],
            'supplier_id' => $input['supplier_id'],
            'devel_id'    => $input['devel_id'],
            'min_price'   => $input['min_price'],
            'max_price'   => $input['max_price'],
            'weight'      => $input['weight'],
            'size'        => $input['size'],
            'status'      => 1,
            ]; 
        $product_id = Product::insertGetid($data);

        // 插入products_extensions
        if($product_id) {
            $data = [
                'product_id'  => $product_id,
                'language'     => $input['language'],
                'name'        => $input['name'],
                'description' => $input['description'],
                'created_at'  => date('Y-m-d H:i:s'),
                ];

            Product_Extension::insert($data);

            if(isset($input['images'])) {
                Product_Image::insert($product_id, $input['images']);
            }

            //session::setFlash('tips', '产品['.$input['sku'].']插入成功');
        }

        return Redirect::to('product');
    }

    // 产品编辑
    public function action_edit() {

        $product_id = Input::get('product_id');
        $product = Product::info($product_id);

        $fields = [ 'id', 'name' ];
        $filter = [ 'parent_id' => 0 ];
        $categories = Category::filter($fields, $filter)->get();

        $fields = ['id', 'company'];
        $suppliers = Supplier::filter($fields)->get();

        $fields = ['id', 'username'];
        $users = User::filter($fields)->get();
    
        return View::make('product.edit')->with('categories', $categories)
                                         ->with('suppliers', $suppliers)
                                         ->with('product', $product)
                                         ->with('users', $users);
    }

    // 产品编辑处理
    public function action_update() {
        $input = Input::all();

        // 验证

        $product_id = Input::get('product_id');
        // 插入product表
        $data = [
            'sku'         => $input['sku'],
            'cost'        => $input['cost'],
            'price'       => $input['price'],
            'category_id' => $input['category_id'],
            'supplier_id' => $input['supplier_id'],
            'devel_id'    => $input['devel_id'],
            'min_price'   => $input['min_price'],
            'max_price'   => $input['max_price'],
            'weight'      => $input['weight'],
            'size'        => $input['size'],
            ]; 
        Product::update($product_id, $data);

        // 插入products_extensions
        $data = [
            'language'    => $input['language'],
            'name'        => $input['name'],
            'description' => $input['description'],
            ];
        Product_Extension::update($product_id, $data);
        Product_Image::update($product_id, $input['images']);

        return Redirect::to('product');
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

    // 产品删除
    public function action_delete() {
        $product_id = Input::get('product_id');

        Product::delete($product_id);
        Product_Extension::delete($product_id);
        Product_Image::delete($product_id);

        return Response::json(['status'=>'success', 'message'=>'删除成功！']);
    }
}
?>
