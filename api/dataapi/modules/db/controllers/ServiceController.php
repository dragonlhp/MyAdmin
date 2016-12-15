<?php
/**
 *这是一个处理管理员的管理文件
 *
 *
 * @author      libin<hansen.li@silksoftware.com>
 * @version     1.0
 * @since       1.0
 */
namespace app\modules\db\controllers;

use Yii;
use app\exts\QrckController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

use common\utils\UtilEncryption;
use common\services\BdataServices;
use app\services\common\StateCode;
use app\services\common\Cmscommon;
use app\models\BsGbService;
use app\models\BsGbServiceContent;
use app\models\BsBdata;
use common\utils\UtilFilter;


class ServiceController extends QrckController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['GET'],
                'view' => ['GET'],
                'create' => ['POST'],
                'update' => ['PUT'],
                'status' => ['PUT'],
                'menus' => ['GET'],
                'pwd' => ['PUT'],
            ],
        ];
        return $behaviors;
    }

    /**
     *
     * 获取管理员的数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex()
    {
        $page_no = Yii::$app->request->get("page_no", 1);
        $keyword=Yii::$app->request->get("keyword", "");

        $page_size = Yii::$app->request->get("page_size", Yii::$app->params['default']['page_size']);
        $model = BsGbService::find()->where("status < 9 ");
       
        $keyword=UtilFilter::addslashesStr($keyword);

        if(!empty($keyword)){
            $model=$model->andWhere("(name like '%".$keyword."%' or content like '%".$keyword."%' or link_name like '%".$keyword."%')");
        }
        $total = $model->count();


        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $sname = "";
                $sc = BsGbServiceContent::find()->where(['gb_service_id'=>$value['id']])->all();
                if($sc){
                    foreach ($sc as $k => $v) {
                        $bsd=BsBdata::findOne($v->content_id);
                        $sname .= empty($sname) ? $bsd->name : "," . $bsd->name;
                    }
                }
               
                $data[$key]['sname'] = $sname;
            }
        }

        $res = ['total' => $total, 'rows' => $data, 'page_size' => $page_size, 'page_no' => $page_no];
        return $res;
    }

    /**
     *
     * 获取单个管理员的数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionView()
    {
        $id = Yii::$app->request->get("id", 0);
        $data = BsGbService::findOne($id)->toArray();
        if ($data) {
            unset($data['pwd']);
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }

    }

    /**
     *
     * 创建管理员数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionCreate()
    {
        $model = new BsGbService();
        $parm = Yii::$app->getRequest()->getBodyParams();

        $ctype=isset($parm['ctype'])?$parm['ctype']:"";

        $model->load($parm, '');
        if ($model->save()) {
            if(count($ctype)>0 && is_array($ctype)){
                $sc= new BsGbServiceContent();
                foreach ($ctype as $key => $value) {
                    $rm=clone $sc;
                    $rm->setAttributes(['gb_service_id'=>$model->id,'content_id'=>$value],false);
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
     * 更新管理员数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get("id", 0);

        $model = BsGbService::findOne($id);

        if ($model) {
            $parm = Yii::$app->getRequest()->getBodyParams();
            $ctype=isset($parm['ctype'])?$parm['ctype']:"";
            $model->load($parm, '');

            if ($model->save()) {
                 BsGbServiceContent::deleteAll('gb_service_id = :gb_service_id', [':gb_service_id' =>$id]);
                 if(count($ctype)>0 && is_array($ctype)){
                    $bs= new BsGbServiceContent();
                    foreach ($ctype as $key => $value) {
                        $rm=clone $bs;
                        $rm->setAttributes(['gb_service_id'=>$model->id,'content_id'=>$value],false);
                        $rm->save();
                    }
                }
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }

        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }

    }

    /**

     *
     * 修改管理员数据的各种状态  ，删除，启用，停用等。
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionStatus()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();

        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        $status = isset($parm['status']) ? $parm['status'] : "";


        if (empty($ids) || empty($status)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);
            foreach ($ids as $i) {
                $model = BsGbService::findOne($i);
                $model->setattribute("status", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }

     /**
     *
     * 获取服务内容
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionGetcontent(){
        $sid=intval(Yii::$app->request->get("sid", ""));
        $bdata=new BdataServices();

        $content=$bdata->getDataByType(BdataServices::CONTENT_BTYPE_ID);

        if($sid >0 && $content){
            foreach ($content as $key => $value) {
                $num=BsGbServiceContent::find()->where(['gb_service_id'=>$sid,'content_id'=>$value->id])->count();
                if($num >0){

                    $content[$key]->status=0;
                    //$value->setAttributes("status",0,false);
                    //$content[$key]=$value;
                }
            }
        }


        return $content;
    }
}