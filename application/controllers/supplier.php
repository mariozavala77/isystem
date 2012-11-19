<?php
/**
 * 供应商控制器
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:supplier.php  2012年11月13日 星期二 13时59分11秒Z $
 */

class Supplier_Controller extends Base_Controller {


    // 供应商默认
    public function action_index() {
    	return View::make('supplier.list');
    }

    // 供应商列表
    public function action_filter(){
        $fields = ['company', 'phone', 'email', 'address', 'created_at', 'id'];
        $channel = Supplier::filter($fields);
        $data = Datatables::of($channel)->make();

        return Response::json($data);
    }

    // 供应商添加
    public function action_add(){

        return View::make('supplier.add');
    }

    // 供应商信息
    public function action_insert(){
        $data = [
            'company'    => Input::get('company'),
            'phone'      => Input::get('phone'),
            'email'      => Input::get('email'),
            'address'    => Input::get('address'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if(Supplier::insert( $data )) {
            session::flash('tips', sprintf('供应商%s添加成功！', $data['company']) );

            return Redirect::to('supplier');
        } else {
            session::flash('tips', '添加失败。');

            return Redirect::back();
        }
    }

    // 供应商编辑
    public function action_edit(){

        $supplier_id = Input::get('supplier_id');
        $supplier = Supplier::info($supplier_id);

        return View::make('supplier.edit')->with('supplier', $supplier);
    }

    // 更新供应商信息
    public function action_update(){
        $supplier_id = Input::get('supplier_id');

        $data = [
            'company' => Input::get('company'),
            'phone'   => Input::get('phone'),
            'email'   => Input::get('email'),
            'address' => Input::get('address'),
        ];

        Supplier::update($supplier_id, $data);

        return Redirect::to('channel');
    }

    // 删除供货商
    public function action_delete(){
        $supplier_id = Input::get('supplier_id');

        $supplier_id = intval($supplier_id);

        if (empty($supplier_id)) {
            return Response::json([ 'status' => 'fail', 'message' => '供货商id无效']);
        }

        $result = Supplier::delete($supplier_id);

        if ($result) {
            $return = [ 'status' => 'success', 'message' => '删除成功！'];
        } else {
            $return = [ 'status' => 'fail', 'message' => '删除失败！'];
        }

        return Response::json($return);
    }
}