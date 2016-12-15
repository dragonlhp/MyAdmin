<?php

/**
 * 基础模块的能力
 *
 * @author   shixiu<2881501959@qq.com>
 * @version  2.0
 * @since    2.0
 */
namespace app\exts;

use Yii;
use yii\rest\Controller;
use common\utils\UtilEncryption;
use common\utils\UtilFilter;
use app\models\BasicUser;
use yii\web\ForbiddenHttpException;
use app\services\common\StateCode;

class QrckController extends Controller
{
    protected $_login_user_id;
    protected $_login_user_name;

    public function beforeAction($action) {

        //return true;
        //var_dump($_SERVER);exit;
        $loginflag=false;
        $accessflag=true;
        if(parent::beforeAction($action)) {
            $method = $action->id;
            $controller = $action->controller->id;
            $module = $action->controller->module;
            if($module) {
                $route = $module->id. '/' .$controller . '/' . $method;
            } else {
                $route = $controller . '/' . $method;
            }

            $username=@$_SERVER['HTTP_X_AUTH_CLIENT'];
            $token=@$_SERVER['HTTP_X_AUTH_TOKEN'];
            $lname=UtilFilter::addslashesStr($username);

            if(!empty($username) && !empty($token)){

                $users=BasicUser::find()->where(['login_name' => $lname,'status'=>1])->one();
                if($users){
                    $pwd=$users->pwd;
                    $estr=UtilEncryption::encryptHashHmac($pwd,Yii::$app->params['secret']['token']);
                    if($estr == $token){
                        $loginflag=true;
                        $this->_login_user_id=$users->id;
                        $this->_login_user_name=$users->name;


                    }
                }
            }
        }

        if($loginflag &&  $accessflag){
            return true;
        }else{
            if(!$loginflag){
                throw new ForbiddenHttpException("未登录",StateCode::LOGIN_REQUIRE);
            }else{
                throw new ForbiddenHttpException("没有权限",StateCode::ACCESS_ERROR);
            }
        }
    }
}