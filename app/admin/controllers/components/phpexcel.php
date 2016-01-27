<?php

App::import('Vendor', 'phpexcel', array('file' => 'PHPExcel.php'));
class PhpexcelComponent extends Object
{
    public $column = array();

    /**/
    public function output($filename, $data)
    {
        $column1 = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $column2 = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        for ($i = 0;$i < sizeof($column1);++$i) {
            $this->column[] = $column1[$i];
        }
        for ($i = 0;$i < sizeof($column1);++$i) {
            for ($j = 0;$j < sizeof($column2);++$j) {
                $this->column[] = $column1[$i].$column2[$j];
            }
        }
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_in_memory_gzip; //cache_to_discISAM
        $cacheSettings = array();
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        $objExcel = new PHPExcel();
        $objWriter = new PHPExcel_Writer_Excel5($objExcel); // 用于 2003 格式   _2007//2007 格式
    //	$objWriter->setOffice2003Compatibility(true);  
        $objActSheet = $objExcel->getActiveSheet();
        //由PHPExcel根据传入内容自动判断单元格内容类型   
//		$objActSheet->setCellValue('A1', '字符串内容');  // 字符串内容   
//		$objActSheet->setCellValue('A2', '10010E-000001');            // 数值   
//		$objActSheet->setCellValue('A3', '1001000000001');          // 布尔值   
//		$objActSheet->setCellValueExplicit('b2', '1001000000001', PHPExcel_Cell_DataType::TYPE_STRING);  设置单元格格式

        foreach ($data as $k => $v) {
            //循环行
            foreach ($v as $kk => $vv) { //循环列
                if ($kk >= sizeof($this->column)) {
                    continue;
                }//超过32列自动跳出
                if (is_numeric($vv) && strlen($vv) > 10) {
                    $objActSheet->setCellValueExplicit($this->column[$kk].($k + 1), $vv, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objActSheet->getStyle($this->column[$kk].($k + 1))
                                ->getNumberFormat()
                                 ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
                } else {
                    $objActSheet->setCellValue($this->column[$kk].($k + 1), $vv); //单元格赋值
                }
            }
        }
        unset($data);
        //pr(	$objActSheet);//exit();
        $outputFileName = $filename;

        $ua = $_SERVER['HTTP_USER_AGENT'];
        $encoded_filename = urlencode($outputFileName);
        $encoded_filename = str_replace('+', '%20', $encoded_filename);
        //pr(memory_get_usage());die;
        if (preg_match('/MSIE/', $ua)) {
            header('Content-Disposition: attachment; filename="'.$encoded_filename.'"');
        } elseif (preg_match('/Firefox/', $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\''.$outputFileName.'"');
        } else {
            header('Content-Disposition: attachment; filename="'.$outputFileName.'"');
        }
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Transfer-Encoding: binary');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        $objWriter->save('php://output');
    }
}
