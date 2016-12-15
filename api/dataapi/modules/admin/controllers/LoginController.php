<?php
/**
 *这是一个处理登录相关的文件
 *
 *
 * @author      libin<hansen.li@silksoftware.com>
 * @version     1.0
 * @since       1.0
 */
namespace app\modules\admin\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use app\services\common\StateCode;
use common\utils\UtilEncryption;
use common\utils\UtilFilter;
use yii\web\BadRequestHttpException;

use app\models\BasicUser;



class LoginController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']=[
                'class' => VerbFilter::className(),
                'actions' => [
                    'Token' => ['POST'],
                ],
            ];
        return $behaviors;
    }

    /**
    *
    * 获取token
    *
    * @access public
    * @since 1.0
    * @return array
    */
    public function actionToken(){
            $parm=Yii::$app->getRequest()->getBodyParams();

            $lname=isset($parm['login_name'])?trim($parm['login_name']):"";
            $lname=UtilFilter::addslashesStr($lname);
            $enter_pwd=isset($parm['pwd'])?trim($parm['pwd']):"";
            $pwd=UtilEncryption::encryptMD5($enter_pwd,Yii::$app->params['secret']['pwd']);

            $model=BasicUser::find();
            $model=$model->where("login_name='".$lname."' and pwd ='".$pwd."' and status=1")->asArray()->one();
            $result=array("login_name"=>$lname,'token'=>"");

            if($model){
                $result['token']=UtilEncryption::encryptHashHmac($pwd,Yii::$app->params['secret']['token']);
                return $result;
            }else{
               throw new BadRequestHttpException('请求数据错误。');
            }
    }
}