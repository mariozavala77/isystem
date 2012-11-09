<?php

namespace Import;

class Import_base {

    /**
     * 读取表格数据
     *
     * @param: $filename string 文件绝对路径
     *
     * @throws: ImportException
     *
     * return array
     */
    public function read( $filename ) {
        $PHPExcel = new \PHPExcel();
        $PHPRead = new \PHPExcel_Reader_Excel2007();
        if( !$PHPRead->canRead($filename) ) {
            $PHPRead = new \PHPExcel_Reader_Excel5();
            if( !$PHPRead->canRead($filename) ) {
                Throw new ImportException ('导入文件不可读');
            }
        }

        $PHPExcel = $PHPRead->load($filename);
        $data = $PHPExcel->getSheet(0)->toArray();

        array_shift($data);     // 删除标题行数据

        return $data;
    }

    /**
     * 过滤单元格空格
     *
     * @param: $data array 一维数据
     *
     * return array 过滤后的数据
     */
    public function trim($data) {
        foreach($data as $key => $datum) {
            $data[$key] = trim($datum);
        }

        return $data;
    }
}
?>
