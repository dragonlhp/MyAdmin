<?php
/**
 *这是一个处理优惠券的管理文件
 *
 *
 * @author      libin<hansen.li@silksoftware.com>
 * @version     1.0
 * @since       1.0
 */

namespace app\modules\need\controllers;

use app\models\BsCoupon;
use app\models\BsMember;
use app\models\BsMemberCompany;
use app\models\BsMemberIncubator;
use app\models\BsMemberPerson;
use app\models\BsMemberService;
use app\models\BsSdApply;
use app\models\BsSdApplyAll;
use app\models\BsSdCapitalReq;
use app\models\BsSdCapitalSup;
use app\models\BsSdCarrierReq;
use app\models\BsSdCarrierSup;
use app\models\BsSdPatentReq;
use app\models\BsSdPatentSup;
use app\models\BsSdProductReq;
use app\models\BsSdProductSup;
use app\models\BsSdRequiredAll;
use app\models\BsSdSkillReq;
use app\models\BsSdSkillSup;
use app\models\BsSdSupplyAll;
use app\services\common\DumpCode;
use Yii;
use app\exts\QrckController;
use yii\filters\VerbFilter;
use app\services\common\StateCode;
use common\utils\UtilFilter;

use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;


class NeedController extends QrckController
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
            ],
        ];

        return $behaviors;
    }

    /**
     *
     * 获取全部的数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex(){
        $page_no = Yii::$app->request->get("page_no", 1);
        $page_size = Yii::$app->request->get("rows", Yii::$app->params['default']['page_size']);
        $pro_type = Yii::$app->request->get("pro_type", "");
        $model = BsSdRequiredAll::find();
        if (!empty($pro_type)) {
            $pro_type = UtilFilter::addslashesStr($pro_type);
            $model->andWhere(" pro_type = $pro_type");
        }
        $total = $model->count();
        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('pro_type DESC')->offset($offset)->limit($page_size)->asArray()->all();

        $datas=[];
        foreach ($data as $key => $item) {
            if ($item['owner_type'] == 2) {
                $Member = BsMember::find()->where(['id'=>$item['owner']])->one();
                $datas[$key] = $item;

                if($Member['mtype']==1){
                    //到相应的表去查询
                    $company = BsMemberCompany::find()->where(['member_id'=>$Member['id']])->one();
                    $datas[$key]['owner'] = $company['name'] ;
                }elseif($Member['mtype']==2){

                    $incubator = BsMemberIncubator::find()->where(['member_id'=>$Member['id']])->one();
                    $datas[$key]['owner'] = $incubator['name'] ;
                }elseif($Member['mtype']==3){
                    $service = BsMemberService::find()->where(['member_id'=>$Member['id']])->one();
                    $datas[$key]['owner'] = $service['name'] ;
                }elseif($Member['mtype']==4){
                    $person = BsMemberPerson::find()->where(['member_id'=>$Member['id']])->one();
                    $datas[$key]['owner'] = $person['name'] ;
                }
            } else {
                $datas[$key] = $item;
                $datas[$key]['owner'] = '系统管理员' ;
            }
        }

        $res = ['total' => $total, 'rows' => $datas];
//        $res = [ 'rows' => $datas];
        return $res;
    }


    /**
     *
     * 获取优惠券的待审核数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionClist()
    {
        
        $page_no = Yii::$app->request->get("page_no", 1);
        $page_size = Yii::$app->request->get("page_size", Yii::$app->params['default']['page_size']);
        $model = BsSdApply::find()->where('status = 1');

        $total = $model->count();
        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();

        $res = ['total' => $total, 'rows' => $data, 'page_size' => $page_size, 'page_no' => $page_no];
        return $res;
    }

    /**
     *
     * 获取单个优惠券的数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionView()
    {
        $id = Yii::$app->request->get("id", 0);
        $data = BsSdApplyAll::find()->where(['id'=>$id])->one();
//        $data = BsSdApply::findOne($id);
//        $data = BsSdApplyAll::find($id);
        if ($data) {
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }

    /**
     *
     * 创建优惠券数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionCreate()
    {
        //var_dump(Yii::$app->getRequest()->getBodyParams());exit;
        $model = new BsSdApply();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
        return $model;
    }

    /**
     *
     * 更新优惠券数据
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get("id", 0);

        $model = BsSdApply::findOne($id);

        if ($model) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            if ($model->save()) {
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }
        } else {
            throw new BadRequestHttpException('更新数据失败。');
        }
    }

    /**
     * 获取分类Tree
     * @return array
     */
    public function actionGetcategory()
    {

        $data = CmsCategory::find()->select('id,pid,name as text')->asArray()->all();


        return Cmscommon::Getcat($data);


    }

    /**
     *
     * 修改优惠券数据的各种状态  ，删除，发布，取消发布等。
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionCheckstatus()
    {
        $parm = Yii::$app->getRequest()->getBodyParams();
        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        if (empty($ids)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $arrs = json_decode($parm['ids'],true);

            foreach ($arrs as $val) {
                if($val['pro_type']==1){
//                    1：载体  2：资金 3：技术 4：专利 5：产品
                    BsSdCarrierReq::updateAll(['status'=>2],['id'=>$val['id']]);
                }elseif ($val['pro_type']==2){
                    BsSdCapitalReq::updateAll(['status'=>2],['id'=>$val['id']]);
                }elseif ($val['pro_type']==3){
                   BsSdSkillReq::updateAll(['status'=>2],['id'=>$val['id']]);
                }elseif ($val['pro_type']==4){
                    BsSdPatentReq::updateAll(['status'=>2],['id'=>$val['id']]);
                }elseif ($val['pro_type']==5){
                    BsSdProductReq::updateAll(['status'=>2],['id'=>$val['id']]);
                }
            }
            return ['code' => StateCode::SUCCESS];
        }
    }
    /**
     * @return null|static
     * @throws BadRequestHttpException
     */
    public function actionStatus(){
        $id = Yii::$app->request->post("id", 0);
        $pro_type = Yii::$app->request->post("pro_type", 0);
        $status = Yii::$app->request->post('status',0);
        if($pro_type==1){
        //1：载体  2：资金 3：技术 4：专利 5：产品
            $model = BsSdCarrierReq::findOne($id);
            if($model){
                $res = BsSdCarrierReq::updateAll(['status'=>$status],['id'=>$id]);
                if ($res) {
                    return $res;
                } elseif (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('删除失败。');
                }
            }else{
                throw new ServerErrorHttpException('删除失败。');
            }
        }elseif ($pro_type==2){
            $model = BsSdCapitalReq::findOne($id);
            if($model){
                $res = BsSdCapitalReq::updateAll(['status'=>$status],['id'=>$id]);
                if ($res) {
                    return $res;
                } elseif (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('删除失败。');
                }
            }else{
                throw new ServerErrorHttpException('删除失败。');
            }
        }elseif ($pro_type==3){
            $model = BsSdSkillReq::findOne($id);
            if($model){
                $res = BsSdSkillReq::updateAll(['status'=>$status],['id'=>$id]);
                if ($res) {
                    return $res;
                } elseif (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('删除失败。');
                }
            }else{
                throw new ServerErrorHttpException('删除失败。');
            }
        }elseif ($pro_type==4){
            $model = BsSdPatentReq::findOne($id);
            if($model){
                $res = BsSdPatentReq::updateAll(['status'=>$status],['id'=>$id]);
                if ($res) {
                    return $res;
                } elseif (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('删除失败。');
                }
            }else{
                throw new ServerErrorHttpException('删除失败。');
            }
        }elseif ($pro_type==5){
            $model = BsSdProductReq::findOne($id);
            if($model){
                $res = BsSdProductReq::updateAll(['status'=>$status],['id'=>$id]);
                if ($res) {
                    return $res;
                } elseif (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('删除失败。');
                }
            }else{
                throw new ServerErrorHttpException('删除失败。');
            }
        }
    }
    /*
     * 获取服务机构名称
     */
    public function actionGetservice()
    {
        $id = Yii::$app->request->get("service_id", 0);
        $data = BsMemberService::findOne($id);
        if ($data) {
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }

    /*
     * 获取服务机构名称
     */
    public function actionGetserviceall()
    {
        $data = BsMemberService::find()->select('id,name as text')->asArray()->all();
        if ($data) {
            return $data;
        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }
}