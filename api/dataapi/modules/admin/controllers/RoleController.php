<?php
/** 
 *这是一个处理角色的管理文件 
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
use app\models\BasicRole;
use app\models\BasicUser;
use app\models\BasicRoleMenu;




class RoleController extends QrckController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']=[
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET'],
                    'view' => ['GET'],
                    'userroles'=>['GET'],
                    'create' => ['POST'],
                    'update' => ['PUT'],
                    'delete' => ['DELETE'],
                ],
            ];
        return $behaviors;
    }

    /** 
    * 
    * 获取角色的数据列表
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionIndex(){
            $page_no=Yii::$app->request->get("page_no",1);
            $page_size=Yii::$app->request->get("page_size",Yii::$app->params['default']['page_size']);
            $model=BasicRole::find();
            $total=$model->count();


            $offset=($page_no-1)*$page_size;
            $data=$model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();
            $res=['total'=>$total,'rows'=>$data,'page_size'=>$page_size,'page_no'=>$page_no];
            return $res;
    }
    /** 
    * 
    * 获取用户的角色列表，如果不传用户id，就返回所有
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionUserroles(){
        $uid=Yii::$app->request->get("uid","");
        $roles=BasicRole::find()->orderBy('id asc')->asArray()->all();;
        if(!empty($uid)){
            $users=BasicUser::findOne($uid);
            if($users){
                $ids=$users['role_ids'];
                $ids=explode(",", $ids);
                $ids=array_flip($ids);
                foreach ($roles as $key => $value) {
                    if(array_key_exists($value['id'], $ids)){
                        $roles[$key]['checkflag']=1;
                    }else{
                        $roles[$key]['checkflag']=0;
                    }
                }
            }
            
        }
        return $roles;
    }
    /** 
    * 
    * 获取单个角色的数据
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionView(){
            $id=Yii::$app->request->get("id",0);
            $data=BasicRole::findOne($id)->toArray();
            if($data){
                $menus=BasicRoleMenu::find()->where(['role_id'=>$id])->all();
                $rm="";
                foreach ($menus as $key => $value) {
                    $rm.=empty($rm)?$value->menu_id:",".$value->menu_id;
                }
                $data['menu_ids']=$rm;
                return $data;
            }else{
                throw new BadRequestHttpException('请求数据错误。');
            }
            
    }
    /** 
    * 
    * 创建角色数据 
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
    public function actionCreate(){
        $model=new BasicRole();


        $parm=Yii::$app->getRequest()->getBodyParams();

        $access=isset($parm['access'])?$parm['access']:"";


        $model->load($parm, '');
        if ($model->save()) {
           
            if(count($access)>0){
                $rolemenu= new BasicRoleMenu();
                foreach ($access as $key => $value) {
                    $rm=clone $rolemenu;
                    $rm->setAttributes(['role_id'=>$model->id,'menu_id'=>$value],false);
                    $rm->save();
                }
            }
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
    }
    /** 
    * 
    * 更新角色数据 
    * 
    * @access public 
    * @since 1.0 
    * @return array 
    */ 
     public function actionUpdate(){
        $id=Yii::$app->request->get("id",0);

        $model=BasicRole::findOne($id);

        if($model){
            $parm=Yii::$app->getRequest()->getBodyParams();
            $access=isset($parm['access'])?$parm['access']:"";
            $model->load($parm, '');

            if ($model->save()) {

                BasicRoleMenu::deleteAll('role_id = :role_id', [':role_id' =>$id]);
                if(count($access)>0){
                    $rolemenu= new BasicRoleMenu();
                    foreach ($access as $key => $value) {
                        $rm=clone $rolemenu;
                        $rm->setAttributes(['role_id'=>$model->id,'menu_id'=>$value],false);
                        $rm->save();
                    }
                }
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }
            
        }else{
            throw new BadRequestHttpException('请求数据错误。');
        }
        
    }

    /** 
    * 
    * 删除角色数据 
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
            $errflag=false;
            $ids=explode(",", $ids);
            foreach ($ids as $i) {
                $model=BasicRole::findOne($i);
                //$count=BasicUser::find()->where(['role_id'=>$i])->count();
                // if($count>0){
                //     $errflag=true;
                // }else{
                    $model->delete();
                    BasicRoleMenu::deleteAll('role_id = :role_id', [':role_id' =>$i]);
                //}
            }
            if(!$errflag){
                return ['code'=>StateCode::SUCCESS];//删除成功
            }else{
                return ['code'=>StateCode::SOME_ERROR];//部分数据操作失败。
            }
            
        } 
    }

}