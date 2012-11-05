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
                return false;
            }
        }

        $PHPExcel = $PHPRead->load($filepath);
        $data = $PHPExcel->getSheet(0)->toArray();

        array_shift($data);
        $result = true;
        foreach( $data as $datum ) {
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

            if(!Product::insert( $product )) $result = false;
        }

        return $result;
    }

}
?>
