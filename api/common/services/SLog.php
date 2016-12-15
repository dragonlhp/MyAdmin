<?php
namespace common\services;
class SLog
{
    public static function log($str){
        error_log($str.'    耗时 : '.self::microtime_float()."\n", 3, "time.log");
    }
    /**
     * 获取毫秒时间戳
     * @return number
     */
    public static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}