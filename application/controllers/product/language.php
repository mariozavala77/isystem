<?php
/**
 * 产品语言控制器
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Language.php  2012年11月14日 星期三 11时26分51秒Z $
 */

class Product_Language_Controller extends Base_Controller {


    // 获取产品语言版本
    public function action_index() {
        $product_id = Input::get('product_id');
        $languages = Product::language($product_id, true);

        $data = [];
        foreach(Config::get('application.support_language') as $key => $value) {
            if($key == 'cn') continue;
            $datum = [
                'language'  => $key,
                'name'      => $value,
                'exists'    => false
                ];

            if(in_array($key, $languages)) {
                $datum['exists'] = true;
            }
            $data[] = $datum;
        }
        
        return Response::json($data);
    }

    // 语言版本添加
    public function action_add() {
        $language = Input::get('language');
        $product_id = Input::get('product_id');
        $product = Product::info($product_id);

        return View::make('product.language.add')->with('language', $language)
                                                 ->with('product', $product);
    }

    // 语言版本添加保存
    public function action_insert() {
        $data = [
            'product_id'        => Input::get('product_id'),
            'language'          => Input::get('language'),
            'name'              => Input::get('name'),
            'keywords'          => Input::get('keywords'),
            'short_description' => Input::get('short_description'),
            'description'       => Input::get('description'),
            'created_at'        => date('Y-m-d H:i:s'),
            ];

        Product_Extension::insert($data);

        return Redirect::to('product');
    }

    // 语言版本编辑
    public function action_edit() {
        $language = Input::get('language');
        $product_id = Input::get('product_id');
        $product_cn = Product::info($product_id);
        $product = Product::info($product_id, $language);
        return View::make('product.language.edit')->with('language', $language)
                                                  ->with('product_cn', $product_cn)
                                                  ->with('product', $product);
    }

    // 语言版本更新保存
    public function action_update() {
        $input = Input::all();
        $product_id = $input['product_id'];
        $data = [
            'name'              => $input['name'],
            'keywords'          => $input['keywords'],
            'short_description' => $input['short_description'],
            'description'       => $input['description'],
            ];

        Product_Extension::update($product_id, $input['language'], $data);

        return Redirect::to('product');
    }

}
?>
