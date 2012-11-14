<?php
/**
 * 代理商API 产品相关
 * 包含列表 产品上架编辑信息处理
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */

class AgentAPI_Product extends AgentAPI_Base
{

    /**
     * 产品列表
     *
     * @param $params array 相关参数至少包含 分页信息
     *
     * @return mixed
     */
    public static function lists($params){
        $fileds = ['p.id', 'category_id','price', 'min_price', 'max_price', 'weight', 'size', 'name', 'description'];
        $filter = ['language'=>'cn'];
        $table = Product::filter($fileds, $filter);
        $count = $table->count();

        $params['page'] = isset($params['page'])?$params['page']:1;
        $params['pagesize'] = isset($params['pagesize'])?$params['pagesize']:15;
        $page = max(1,intval($params['page']));
        $data = $table ->skip(($page-1)*$params['pagesize'])
                       ->take($params['pagesize'])
                       ->get();
        
        if(empty($data)){
            throw new AgentAPIException("not have product", -32004);
        }

        foreach($data as $key => $datum) {
            $imgs=[];
            $images      = Product_Image::get($datum->id);
            $root       = '/uploads/images/products/';
            foreach($images as $k => $val){
                $imgs[$k]= URL::to(str_replace('\\', '/', UploadHelper::path($root, $val->image, true)));
            }

            $data[$key]->images = $imgs;
            $category = Category::info($datum->category_id);
            $data[$key]->category = $category->name;
        }

        return ['data' => $data, 'total' => $count];
    }

    /**
     * 添加更新在售产品
     *
     * 需要提供 产品id,渠道id,代理商id,语言,单价,标题,关键词，短描述，描述
     *
     * @param $params array 编辑或者插入的数据
     *
     * @return array
     */
    public static function sale($params){
        $fields = [
            'product_id', 'channel_id', 'agent_id', 'language', 'price', 
            'title', 'keywords', 'short_description', 'description'
        ];

        try{
            $data = self::requeryParams($fields, $params);
        }catch(Exception $e){
            throw new AgentAPIException($e->getMessage(), -32004);
        }

        $product_sale_id = Product_Sale::getId($data['agent_id'], $data['product_id'], $data['channel_id']);

        if($product_sale_id){
            $data['status']=0;
            $requslt = Product_Sale::update($product_sale_id, $data);
            if($requslt){
                // 发送任务tasks
                return ['product_id'=>$data['product_id'], 'channel_id'=>$data['channel_id']];
            }else{
                throw new AgentAPILogException("代理商产品更新失败", 1);            }
        }else{
            // 创建的时间
            $data['created_at'] = date('Y-m-d H:i:s');
            $requset = Product_Sale::insert($data);
            if($requset){
                // 发送任务插入tasks
                return ['product_id'=>$data['product_id'], 'channel_id'=>$data['channel_id']];
            }else{
                throw new AgentAPILogException("代理商产品新增不成功", 1);
            }
        }
    }

    /**
     * 产品池详细信息
     * 需要提供 产品的id和语言名称
     * 
     * @param: $params array 参数
     *
     * return 返回产品信息以及图片列表
     */
    public static function info($params){
        $param = ['product_id', 'language'];

        try{
            $filter = self::requeryParams($param, $params);
        }catch(Exception $e){
            throw new AgentAPIException($e->getMessage(), -32004);
        }
        $fields = ['p.id as id', 'language', 'name', 'sku', 'weight', 'size', 'keywords', 
                   'description', 'price', 'min_price', 'max_price', 'short_description', 'created_at'];

        $info = Product::filter($fields, $filter)->get();

        if(empty($info)){
            throw new AgentAPIException('没有找到你需要的产品信息', -32004);
        }

        $img = Product_Image::get($filter['product_id']);

        return ['info' => $info[0], 'img' => $img];
    }
}