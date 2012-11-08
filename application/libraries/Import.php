<?php

/**
 * 导入库
 *
 * 主要包括产品图片导入，产品信息导入。
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Import.php  2012年11月02日 星期五 10时09分20秒Z $
 */

class Import {

    /**
     * Excel导入产品
     *
     * @param: $filepath string 文件路径
     *
     * return boolean
     */
    public static function product( $filepath ) {
        $PHPExcel = new PHPExcel();

        $PHPRead = new PHPExcel_Reader_Excel2007();
        if( !$PHPRead->canRead($filepath) ) {
            $PHPRead = new PHPExcel_Reader_Excel5();
            if( !$PHPRead->canRead($filepath) ) {
                return ['status' => 'fail', 'message' => '上传的文件读取失败'];
            }
        }

        $PHPExcel = $PHPRead->load($filepath);
        $data = $PHPExcel->getSheet(0)->toArray();

        // 表头验证
        $header = array_shift($data);
        // 表格头
        $table_header = [ '名称','SKU','最低价格','最高价格','重量','尺寸', '图片', '描述' ];

        if($header != $table_header) {
            return ['status' => 'fail', 'message' => 'OMG，这不是一个标准产品导入模板'];
        }
        
        $result = static::_valid($data);
        if($result['status'] == 'fail') {
            return $result;
        }
        foreach( $data as $key => $datum ) {
            $product = [
                    'name'        => $datum[0],
                    'language'    => 'en',
                    'sku'         => $datum[1],
                    'min_price'   => $datum[2],
                    'max_price'   => $datum[3],
                    'weight'      => $datum[4],
                    'size'        => $datum[5],
                    'images'      => $datum[6],
                    'description' => $datum[7],
                ];

            if(!Product::insert( $product )) $result = ['status' => 'fail', 'message' => '第' . $key + 2 .'行写入产品表失败'];
        }

        return $result;
    }

    // 表格数据验证
    private static function _valid($data) {
        $maps = ['0' => '产品名称', '1' => 'SKU', '2' => '最小价格', '3' => '最大价格', '6' => '产品图片', '7' => '产品描述'];
        foreach($data as $row => $datum) {

            // 数据简单判断
            $rules = [
                '0' => 'required|max:255',
                '1' => 'required|max:20',
                '2' => 'required|match:/^\d+\.\d{2}$/',
                '3' => 'required|match:/^\d+\.\d{2}$/',
                '6' => 'required|match:/^(\w+\_\d+\.(jpg)\;)+$/',
                '7' => 'required',
                ];

            $messages = [
                'required'   => ':attribute忘记填啦！',
                'max'        => ':attribute填太多,装不下啦！',
                'match'      => ':attribute被核辐射了,变畸形啦！快去救救它。',
                ];

            $validation = Validator::make($datum, $rules, $messages);
            if ($validation->fails()) {
                preg_match('/^\d+/', $validation->errors->first(), $matchs);
                $index = isset($matchs[0]) ? $matchs[0] : -1;
                $current_row = $row + 2;
                $message = '亲，第' . $current_row . '行的' . str_replace($index, $maps[$index], $validation->errors->first());

                return ['status'=> 'fail', 'message' => $message];
            }

            // 产品图片验证
            $images = explode(';', $datum[6]);
            array_pop($images);
            foreach($images as $image) {
                $root = path('public') . 'uploads'. DS . 'images'. DS . 'products' . DS;
                $path = UploadHelper::path($root, $image, true);
                if(!file_exists($path)){
                    $message = '哎呀，有个图片[' . $image . ']掉队了，请再传一次。';
                    return ['status' => 'fail', 'message' => $message];
                }
            }
        }

        return ['status' => 'success'];
    }
}
?>
