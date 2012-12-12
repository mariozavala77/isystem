<?php
/**
 * 销售产品SKU模型
 *
 * @author: shaoqi <shaoqisq123@gmail.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id$
 */
class Product_Sale_Sku 
{

    /**
     * 销售列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加过滤
     *
     * return object
     */
    public static function filter($fields, $filter = [])
    {
        $query = DB::table('products_sale_sku as pss')->left_join('products_sale as ps', 'ps.id', '=', 'pss.sale_id')
                                                      ->left_join('agents as a', 'a.id', '=', 'ps.agent_id')
                                                      ->left_join('channels as c', 'c.id', '=', 'pss.channel_id')
                                                      ->select($fields);

        foreach($filter as $key => $value) {
            $query->where($key, '=', $value);
        }

        return $query;
    }

    /**
     * 插入数据
     *
     * @param: $data array 插入的数据
     *
     * return boolean
     */
    public static function insert($data)
    {
        return DB::table('products_sale_sku')->insert_get_id( $data );
    }

    /**
     * 更新数据
     *
     * @param: $sale_sku_id integer 代理商认购商品上架的id
     * @param: $data        array   更新的数据
     * return boolen
     */
    public static function update($sale_sku_id, $data)
    {
        return DB::table('products_sale_sku')->where('id', '=', $sale_sku_id)->update($data);
    }

    /**
     * 在售商品详细信息
     *
     */
    public static function info($sale_id)
    {
        return DB::table('products_sale_sku as pss')->left_join('products_sale as ps', 'ps.id', '=', 'pss.sale_id')
                                                     ->select(['pss.*', 'ps.title', 'ps.price', 'ps.keywords', 'ps.short_description', 'ps.description'])
                                                     ->where('pss.id', '=', $sale_id)
                                                     ->first();
    }

    /**
     * 生成SKU
     */
    public static function generateSku() {
        $sku = 'UFC' . Str::upper('-' . Str::random(4) . '-' . Str::random(4));
        $sale_sku_id = DB::table('products_sale_sku')->where('sku', '=', $sku)->only('id');
        if($sale_sku_id) {
            $sku = static::makeSku();
        }

        return $sku;
    }

    /**
     * 是否上架在售
     *
     * @param: $product_id integer 产品ID
     * @param: $channel_id integer 渠道ID
     *
     * return integer
     */
    public static function onSale($product_id, $channel_id)
    {
        return DB::table('products_sale_sku')->where('product_id', '=', $product_id)
                                             ->where('channel_id', '=', $channel_id)
                                             ->only('id');
    }

    /**
     * 产品上架处理
     *
     * @param: $channel_id  integer 渠道ID
     * @param: $sale_sku_id integer SKU映射ID
     *
     * return array
     */
    public static function listing($channel_id, $sale_sku_id)
    {
        $datetime = date('Y-m-d H:i:s');
        $result   = ['status'=>'fail', 'message'=>'上架失败！'];

        $info = Product_Sale_Sku::info($sale_sku_id);
        $sale_sku_id = Product_Sale_Sku::onSale($info->product_id, $channel_id);


        if($sale_sku_id) {
            $data = ['is_sold' => 1];
            Product_Sale_Sku::update($sale_sku_id, $data);
            
        } else {
            $sku = Product_Sale_Sku::generateSku();

            $data = [
                'channel_id' => $channel_id,
                'product_id' => $info->product_id,
                'sale_id'    => $info->sale_id,
                'is_sold'    => 1,
                'sold_at'    => $datetime,
                'sku'        => $sku,
                ];
            $sale_sku_id = Product_Sale_Sku::insert($data);
        }
       
        if($sale_sku_id) {

            $params = static::queueParams($channel_id, $sale_sku_id);

            $data = [
                    'type'       => 'product',
                    'action'     => 'listing',
                    'entity_id'  => $sale_sku_id,
                    'channel_id' => $channel_id,
                    'params'     => serialize($params),
                    'created_at' => $datetime,
                    ];

            if(Queue::insert($data)) {
                $result = ['status'=>'success', 'message'=>'上架成功！'];
            } else {
                $data = [
                    'user_id' => $this->id,
                    'content' => printf('SKU为%s的产品上架失败！', $sku),
                    'created_at' => $datetime,
                    ];
                Logs::insert($data);
            }
        }

        return $result;
    }

    /**
     * 通过在售产品SKU获取产品池ID
     *
     * @param: $sku string 在售产品SKU
     *
     * return integer
     */
    public static function map($sku) {
        return DB::table('products_sale_sku')->where('sku', '=', $sku)->only('product_id');
    }

    /**
     * 构造队列参数
     *
     * @param: $channel_id  integer 渠道ID
     * @param: $sale_sku_id integer 销售ID
     *
     * return array
     */
    public static function queueParams($channel_id, $sale_sku_id)
    {
        $channel   = Channel::info($channel_id);
        $sale_info = (array)Product_Sale_Sku::info($sale_sku_id);
        $sale_info['images'] = (array)Product_Image::get($sale_info['product_id']);

        $params = [
            'class'      => $channel->type,
            'channel_id' => $channel_id,
            'options'    => unserialize($channel->accredit),
            'params'     => $sale_info,
            ];

        return $params;
    }

    public static function existence($sale_id, $channel_id){
        return DB::table('products_sale_sku')->where('sale_id', '=', $sale_id)
                                             ->where('channel_id', '=', $channel_id)
                                             ->only('id');
    }
}
