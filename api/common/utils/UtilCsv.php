<?php
/**
 * csv服务
 */
namespace common\utils;

use Yii;

class UtilCsv
{

    public static function export($filename, $data,$charset="GBK")
    {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        $data = mb_convert_encoding($data, $charset, 'utf-8');
        echo $data;
    }
}
