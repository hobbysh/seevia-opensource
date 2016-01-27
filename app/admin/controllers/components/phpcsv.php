<?php

class PhpcsvComponent extends Object
{
    public function output($filename, $data)
    {
        $tmp_file="../data/admin/cache/".$filename;
        $csv_content="";
        $fp = fopen($tmp_file, 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        $fd = fopen($tmp_file, "r");
        $csv_content = fread($fd, filesize($tmp_file));
        fclose($fd);
        @unlink($tmp_file);
        $csv_content=@iconv("utf-8", "GBK", $csv_content);
        $outputFileName = $filename;
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $encoded_filename = urlencode($outputFileName);
        $encoded_filename = str_replace('+', '%20', $encoded_filename);
        header('Content-Type: text/csv; charset=gb2312');
        header("Content-Type: application/vnd.ms-excel; charset=gb2312");
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
        echo $csv_content;
        die();
    }
}
