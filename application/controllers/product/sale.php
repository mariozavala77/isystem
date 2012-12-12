<?php

/**
 * 产品销售控制器
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:sale.php  2012年11月06日 星期二 15时38分29秒Z $
 */
class Product_Sale_Controller extends Base_Controller {

    // 销售列表
    public function action_index()
    {

        $fields   = ['name'];
        $channels = Channel::filter($fields)->get();
        $fields   = ['company'];
        $agents   = Agent::filter($fields)->get();
        $status   = [0 => '已下架', '1'=> '已上架'];

        return View::make('product.sale.list')->with('channels', $channels)
                                              ->with('agents', $agents)
                                              ->with('status', $status);
    }

    // 列表
    public function action_filter()
    {
        $fields = [ 'title', 'sku', 'price', 'c.name', 'a.company', 
                    'is_sold', 'ps.created_at', 'pss.id as operate'];
        $products = Product_Sale_Sku::filter($fields);

        $data = Datatables::of($products)->make();

        return Response::json($data);
    }

    // 编辑销售信息
    public function action_edit()
    {
        $sale_sku_id  = Input::get('id');
        $product_sale = Product_Sale_Sku::info($sale_sku_id);
        $product      = Product::info($product_sale->product_id);

        if(!$product) {
            $tips = '还没有做产品池产品映射，不能编辑。';
            return View::make('product.sale.tips')->with('tips', $tips);
        } else {
            return View::make('product.sale.edit')->with('product_sale', $product_sale)
                                                  ->with('product', $product);
        }
    }

    // 更新销售
    public function action_update()
    {
        $input = Input::all();

        $sale_id = $input['sale_id'];

        $data = [
            'title'             => $input['title'],
            'price'             => $input['price'],
            'keywords'          => $input['keywords'],
            'short_description' => $input['short_description'],
            'description'       => $input['description'],
            ];

        Product_Sale::update($sale_id, $data);

        return Redirect::to('product/sale');
    }

    // 上架
    public function action_listing()
    {
        $channel_id  = Input::get('channel_id');
        $sale_sku_id = Input::get('sale_sku_id');

        $info = Product_Sale_Sku::info($sale_sku_id);
        $sale_sku_id = Product_Sale_Sku::onSale($info->product_id, $channel_id);

        $result = ['status'=>'fail', 'message'=>'上架失败！'];

        if($sale_sku_id) {
            
        } else {
        
        }
    
    }

    // 代理商认购商品信息
    public function action_info()
    {
        $sale_id = intval(Input::get('sale_id'));

        if(empty($sale_id)){
            return Response::json(['status' => 'fail', 'message' => '信息不完整']);
        }

        $sale_info = Product_Sale_Sku::info($sale_id);
        $sale = Product_Sale::info($sale_info->sale_id);

        if(empty($sale_info)){
            return Response::json(['status' => 'fail', 'message' => '没有找到销售产品的记录']);
        }

        $sale_info->validation = empty($sale_info->validation)?'':json_decode($sale_info->validation);

        $channel = Channel::info($sale_info->channel_id);
        
        $sale_info->channel = $channel->name;
        $sale_info->agent = Agent::info($sale->agent_id);

        return Response::json(['status' => 'success', 'message' => $sale_info]);
    }

    // 产品映射
    public function action_mapping()
    {
        $product_id = Input::get('product_id');
        $sku = Input::get('sku');
        $task_id = Input::get('task_id');
        if(Product_Sale::mapping($sku, $product_id)){
            if( ! empty($task_id)){
                $data = ['handle'      => 1, 
                         'modified_at' => date('Y-m-d H:i:s')
                        ];
                Tasks::update($task_id, $data);
                $data = ['uid'        => $this->user_id,
                         'comment'    => '产品已经映射',
                         'taskid'     => $task_id,
                         'created_at' => time()
                        ];
                Tasks_Comment::insert($data);
            }
            $result = ['status' => 'success', 'message' => 'ok'];
        }else{
            $result = ['status' => 'fail', 'message' => 'wrong'];
        }
        return Response::json($result);
    }
}
