<?php

/**
 * 仓库模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Stock.php  2012年11月10日 星期六 01时53分28秒Z $
 */
class Stock extends Base_Model
{

    /**
     * 列表
     *
     * @param: $fields array 字段
     * @param: $filter array 过滤
     *
     * return object
     */
    public static function filter($fields, $filter = [])
    {
        $query = DB::table('stock')->left_join('storage', 'stock.storage_id', '=', 'storage.id')
                                   ->left_join('products_extensions as pe', 'stock.product_id', '=', 'pe.product_id')
                                   ->select($fields);
        static::formatFilter($query, $filter);
        $query->where('pe.language', '=', 'cn');

        return $query;
    }

    /**
     * 获取产品库存信息
     *
     * @param: $product_id integer 产品池ID
     * @param: $storage_Id integer 仓库ID
     *
     * return integer
     */
    public static function sellable($product_id, $storage_id) 
    {
        return DB::table('stock')->where('product_id', '=', $product_id)
                                 ->where('storage_id', '=', $storage_id)
                                 ->only('sellable');
    }

    /**
     * 从外面仓库获取库存
     *
     * @param: $storage_id integer 仓库ID
     *
     * return void
     */
    public static function sync($storage_id) 
    {
        $storage = Storage::info($storage_id);
        if($storage->channel_id) {
            $channel = Channel::info($storage->channel_id);
            $storage->accredit = $channel->accredit;
        }

        $options = unserialize($storage->accredit);

        $stock = new StockAPI($storage->type, $options);
        $infos = $stock->info();

        foreach($infos as $info) {
            $stock_id = static::exists($storage_id, $info['code']);
            if($stock_id) {
                $info['updated_at'] = date('Y-m-d H:i:s');
                static::update($stock_id, $info);
            } else {
                $info['created_at'] = date('Y-m-d H:i:s');
                static::insert($info);
            }
        }

        echo 'ok';
    }

    /**
     * 检测库存信息是否存在
     *
     * @param: $storage_id integer 仓库ID
     * @param: $code       string  仓库编码
     *
     * return integer 库存表ID
     */
    public static function exists($storage_id, $code) 
    {
        return DB::table('stock')->where('storage_id', '=', $storage_id)
                                 ->where('code', '=', $code)
                                 ->only('id');
    }

    /**
     * 插入仓库产品数据
     *
     * @param: $data array 数据
     *
     * return boolean
     */
    public static function insert($data) 
    {
        return DB::table('stock')->insert_get_id($data);
    }

    /**
     * 更新仓库产品信息
     *
     * @param: $stock_id integer 记录ID
     * @param: $data     array   数据
     *
     * return boolean
     */
    public static function update($stock_id, $data) 
    {
        return DB::table('stock')->where('id', '=', $stock_id)
                                 ->update($data);
    }

    /**
     * 获取库存信息
     *
     * @param: $stock_id integer 记录ID
     *
     * return object
     */
    public static function info($stock_id)
    {
        return DB::table('stock')->where('id', '=', $stock_id)
                                 ->first();
    }

    /**
     * 调仓
     *
     * @param: $stock_id    integer 记录ID
     * @param: $storage_id  integer 目标仓库ID
     * @param: $quantity    integer 数量
     * @param: $to_stock_id integer 目标仓库记录ID
     * 
     * return array
     */
    public static function adjust($stock_id, $storage_id, $quantity, $to_stock_id)
    {
        $datetime = date('Y-m-d H:i:s');
        $from_stock = Stock::info($stock_id);
        if(!$to_stock_id) {
            $data = [
                'product_id'  => $from_stock->product_id,
                'storage_id'  => $storage_id,
                'created_at'  => $datetime,
                'modified_at' => $datetime,
                ];
            $to_stock_id = Stock::insert($data);
        }

        // 转仓数据
        $data = [
            'product_id'      => $from_stock->product_id,
            'from_stock_id'   => $stock_id,
            'to_stock_id'     => $to_stock_id,
            'from_storage_id' => $from_stock->storage_id,
            'to_storage_id'   => $storage_id,
            'quantity'        => $quantity,
            'created_at'      => $datetime,
            ];

        $status = false;
        if(Stock_Adjust::insert($data)) {
            $data = [
                'sellable'    => $from_stock->sellable - $quantity,
                'modified_at' => $datetime,
                ];
            if(Stock::update($stock_id, $data)) $status = true;
        }

        if($status) {
            $result = ['status' => 'success', 'message' => '调节成功！'];
        } else {
            $result = ['status' => 'fail', 'message' => '调节失败！'];
        }

        return $result;
    }

    /**
     * 库存导入
     *
     * @param: $filepath string 导入文件绝对地址
     *
     * return array
     */
    public static function import($filepath)
    {
        try {
            $import = new Import('stock', $filepath);
            $result = ['status' => 'success', 'message' => '导入成功！'];
        } catch(Import\ImportRowException $e) {
            $result = ['status' => 'fail', 'message' => $e->getMessage() ];
        } catch(Import\ImportFileException $e) {
            $result = ['status' => 'fail', 'message' => $e->getMessage() ];
        } catch(Import\ImportException $e) {
            $result = ['status' => 'fail', 'message' => $e->getMessage() ];
        }

        return $result;
    }
    

}

?>
