<?php
namespace common\services;

use Yii;
use common\utils\UtilDes;
use common\utils\UtilLock;

class SerRoute
{
    /**
     * 设置参数变量
     * @param string $param
     * @return string
     */
    public static function setParam($param){
        $key = \Yii::$app->params['urlkey'];
        $param = UtilLock::encrypt($param,$key);
        $param=urlencode($param);
        return $param;
    }
     /**
     * 获取参数变量
     * @param string $param
     * @return string
     */
    public static function getParam($param){
        $key = \Yii::$app->params['urlkey'];
        $res=UtilLock::decrypt($param,$key);
       /* if(!$res || empty($res)){
            $param=urldecode($param);
            $res=UtilDes::decrypt($param,$key);
        }*/
        return $res;
    }
}

?>