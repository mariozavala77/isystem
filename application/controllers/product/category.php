<?php

/**
 * 产品分类控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:category.php  2012年11月06日 星期二 11时20分31秒Z $
 */
class Product_Category_Controller extends Base_Controller {

    // 分类列表
    public function action_index() {
        return View::make('product.category.list');
    }

    // 类别
    public function action_filter() {
        $fields = ['name', 'sort', 'id'];
        $categories = Category::filter($fields);
        $data = Datatables::of($categories)->make();
        foreach($data['aaData'] as $key => $datum) {
            $data['aaData'][$key][0] = Category::name($datum[2]);
        }

        return Response::json($data);
    }

    // 获取下级
    public function action_children() {
        $category_id = intval(Input::get('category_id'));
        $categories  =  Category::children($category_id, false);

        return Response::json($categories);
    }

    // 类别添加
    public function action_add() {
        $fields = ['id', 'name'];
        $filter = ['parent_id' => 0];
        $categories = Category::filter($fields, $filter)->get();

        return View::make('product.category.add')->with('categories', $categories);
    }

    // 插入 
    public function action_insert() {
        $data = [
           'parent_id' => Input::get('parent_id'), 
           'name'      => Input::get('name'),
           'sort'      => Input::get('sort'),
            ];
        $data['sort'] = intval($data['sort']);
        if(Category::exist($data)){
            $msg = ['status' => 'failure', 'message' => '分类名称有重复'];
        }else{
            Category::insert($data);
            $msg = ['status' => 'success', 'message' => '删除成功！'];
        }
        if(Request::ajax()){
            return Response::json($msg);
        }
        return Redirect::to('product/category');
    }

    // 编辑
    public function action_edit() {
        $category_id = Input::get('category_id');
        $category = Category::info($category_id);
        $children = json_encode(Category::children());
        $fields = ['id', 'name'];
        $filter = ['parent_id' => 0];
        $categories = Category::filter($fields, $filter)->get();

        return View::make('product.category.edit')->with('category', $category)
                                                  ->with('children', $children)
                                                  ->with('categories', $categories);
    }

    // 更新
    public function action_update() {
        $category_id = Input::get('category_id');
        $data = [
            'parent_id' => Input::get('parent_id'),
            'name'      => Input::get('name'),
            'sort'      => Input::get('sort'),
            ];

        Category::update($category_id, $data);

        return Redirect::to('product/category');
    }

    // 删除
    public function action_delete() {
        $category_id = Input::get('category_id');
        $result = ['status' => 'success', 'message' => '删除成功！'];

        Category::delete($category_id);

        return Response::json($result);
    }

}
