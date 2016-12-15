<?php
/** 
 *这是一个处理菜单的管理文件 
 * 
 * 
 * @author      libin<hansen.li@silksoftware.com> 
 * @version     1.0 
 * @since       1.0 
 */  
namespace app\modules\admin\controllers;

use Yii;
use app\exts\QrckController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

use common\utils\UtilEncryption;
use app\services\common\StateCode;
use app\models\BasicRoleMenu;
use app\models\BasicUser;
use app\models\BasicRoleMenuMenu;


class RolemenuController extends QrckController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']=[
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET'],
                    'view' => ['GET'],
                    'create' => ['POST'],
                    'delete' => ['DELETE'],
                ],
            ];
        return $behaviors;
    }

    /** 
    * 
    * 获取菜单的数据列表
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionIndex(){
            $role_id=Yii::$app->request->get("role_id","");
            $page_no=Yii::$app->request->get("page_no",1);
            $page_size=Yii::$app->request->get("page_size",Yii::$app->params['default']['page_size']);
            $model=BasicRoleMenu::find();

            if(!empty($role_id)){
                $model->andWhere(['role_id'=>$role_id]);
            }

            $total=$model->count();
            $offset=($page_no-1)*$page_size;
            $data=$model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();
            $res=['total'=>$total,'rows'=>$data,'page_size'=>$page_size,'page_no'=>$page_no];
            return $res;
    }
    /** 
    * 
    * 获取单个菜单的数据
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionView(){
            $id=Yii::$app->request->get("id",0);
            $data=BasicRoleMenu::findOne($id);
            if($data){
                return $data;
            }else{
                throw new BadRequestHttpException('请求数据错误。');
            }
            
    }
    /** 
    * 
    * 创建菜单数据 
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionCreate(){
        $model=new BasicRoleMenu();


        $parm=Yii::$app->getRequest()->getBodyParams();
        $model->load($parm, '');
        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
    }

    /** 
    * 
    * 删除菜单数据 
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionDelete(){
        $parm=Yii::$app->getRequest()->getBodyParams();
        $ids=isset($parm['ids'])?$parm['ids']:"";

        if(empty($ids)){
            throw new BadRequestHttpException('请求数据错误。',StateCode::REQUEST_ERROR);
        }else{
            $ids=explode(",", $ids);
            foreach ($ids as $i) {
                $model=BasicRoleMenu::findOne($i);
                $model->delete();
            }
             return ['code'=>StateCode::SUCCESS];//删除成功
            
        } 
    }

}