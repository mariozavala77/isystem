<?php

/**
 * 上传辅助函数
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:UploadHelper.php  2012年11月01日 星期四 19时59分43秒Z $
 */
class UploadHelper {

    /**
     * 获取产品上传路径
     *
     * @param: $root     string  上传根目录
     * @param: $filename string  文件名称
     * @param: $file     boolean 是否返回完整路径
     *
     * return string
     */
    public static function path( $root, $filename, $file = false ) {
        $fileinfo = pathinfo($filename);

        $dirs = static::_hashDir( $fileinfo );
        foreach($dirs as $dir) {
            $root .= $dir . DS;
        }
    
        if($file)
            return $root.$fileinfo['basename'];
        else
            return [ 'dir' => $root , 'name' => $fileinfo['basename'] ];
    }

    /**
     * 格式化名字
     *
     * 不带type简单将文件名改成统一小写
     *
     * @param: $filename string 文件名
     * @param: $type     string 格式化类型
     *
     * return string
     */
    public static function rename($filename, $type='') {
        if(!empty($type)) {
            $info = pathinfo($filename);

            switch ($type) {
                case 'timestamp':
                    $filename = microtime(true).'.'.$info['extension'];
                    break;
                default:
                    break;
            }
        }
        $filename = strtolower($filename);

        return $filename;
    }

    /**
     * 截取MD5目录
     *
     * @param: $fileinfo array 文件信息
     *
     * return array
     */
    private static function _hashDir( $fileinfo ) {
        $new_filename = md5($fileinfo['filename']);

        $dirs = [
           substr($new_filename, 0, 8),
           substr($new_filename, 8, 8),
           substr($new_filename, 16, 8),
           substr($new_filename, 24, 8)
        ];

        return $dirs;
    }




}
?>
