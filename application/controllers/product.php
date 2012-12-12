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
        $fields = [ 'id', 'name' ];
        $filter = [ 'parent_id' => 0 ];
        $categories = Category::filter($fields, $filter)->get();
        return View::make('product.list')->with('categories', $categories);
    }

    // 产品列表
    public function action_filter() {

        $fields = [ 
            'p.id as check', 'p.id as id', 'name', 'sku', 'category_id', 'cost', 'min_price', 
            'max_price', 'status', 'created_at', 'p.id as operate' 
            ];

        $filter = ['language' => 'cn'];

        $products = Product::filter($fields, $filter);
        $data = Datatables::of($products)->make();

        foreach($data['aaData'] as $key => $datum) {
            $product_id = $datum[1];
            $image      = Product_Image::get($product_id, 1);
            $root       = '/uploads/images/products/';
            $data['aaData'][$key][1] = empty($image)?'':UploadHelper::path($root, $image->image, true);

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
            'name'        => $input['name'],
            'description' => $input['description'],
            ];
        Product_Extension::update($product_id, $input['language'], $data);
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

    // 产品详情
    public function action_info(){
        $product_id = intval(Input::get('product_id'));

        if(empty($product_id)){
            return Response::json([ 'status' => 'fail', 'message' => '参数丢失']);
        }
        
        $product_info = Product::info($product_id);

        if(empty($product_info)){
            return Response::json([ 'status' => 'fail', 'message' => '没有找到产品信息']);   
        }

        $stock_fields = ['stock.id', 'stock.code', 'stock.sellable', 'stock.unsellable', 'storage.area', 'storage.type'];
        $stock_filter = ['stock.product_id' => $product_id];
        // 仓储信息
        $product_info->stock = Stock::filter($stock_fields, $stock_filter)->get();
        // 产品分类
        $category = Category::info($product_info->category_id);

        $product_info->category = $category->name;
        // 供应商信息
        $suppliers = Supplier::info($product_info->supplier_id);

        $product_info->supplier = $suppliers->company;

        $devel = User::info($product_info->devel_id);

        $product_info->devel = $devel->username;
        if(Request::ajax()){
            return Response::json(['status'=>'success', 'message'=>$product_info]);
        }else{
            return View::make('product.info')->with('product_info', $product_info)
                                             ->with('product_id', $product_id);
        }
    }

    public function action_search(){
        $keyword = Input::get('keyword');
        $fields = ['product_id','name'];
        $result = Product_Extension::filter($fields);
        $result = $result->where('name', 'LIKE', $keyword . '%')->take(10)->get();
        
        if(!empty($result)){
            $result = ['status' => 'success', 'message' => $result];
        }else{
            $result = ['status' => 'fail', 'message' => '没有匹配的产品'];
        }
        return Response::json($result);
    }
}
