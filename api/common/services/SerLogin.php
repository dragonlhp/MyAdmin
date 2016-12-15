<?php
namespace common\services;
use common\models\BsMember;
use common\utils\UtilEncryption;


class SerLogin{
    /*
     * 返回值是状态1表示登录成，2和3 表示密码或者用户名错误，4表示邮箱未激活
     */
    public static function isLogin($login_name,$pwd){



        $res = BsMember::find()->where(['login_name'=>$login_name,'status'=>2])->one();

        if(!$res){
            return 2;
        }
        $newPwd = UtilEncryption::encryptMd5($pwd,\Yii::$app->params['pwd']);

        if($res['pwd']!==$newPwd){
            return 3;
        }
        if($res['email_status']==1){
            return 4;
        }
        return  $res;
    }
}