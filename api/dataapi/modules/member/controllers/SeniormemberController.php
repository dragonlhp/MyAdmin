<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 2016年11月17日
 * 功能说明: 会员管理
 */

namespace app\modules\member\controllers;


use app\models\BsCoupon;
use app\models\BsCredit;
use app\models\BsLogsCheck;
use app\models\BsMember;
use app\models\BsMemberCompany;
use app\models\BsMemberIncubator;
use app\models\BsMemberSenior;
use app\models\BsMemberService;
use app\models\BsMenu;
use app\models\BsSeniorCompany;
use app\models\BsSeniorIncubator;
use app\models\BsSeniorIndustry;
use app\models\BsSeniorPlatform;
use app\models\BsSeniorSales;
use app\models\BsSeniorService;
use app\models\BsSeniorServiceContent;
use app\models\BsSeniorShareholder;
use app\services\common\Cmscommon;
use app\services\common\ComStarred;
use app\services\common\DumpCode;
use app\services\common\ServStarred;
use app\services\common\StateCode;
use common\services\BdataServices;
use common\utils\UtilEncryption;
use common\utils\UtilFilter;
use Yii;
use app\exts\QrckController;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class SeniormemberController extends QrckController
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
                'status' => ['DELETE'],
                'setlevel' => ['GET'],
            ],
        ];
        return $behaviors;
    }


    /**
     *
     * 获取高级会员申请数据列表
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionIndex()
    {
        $page_no = Yii::$app->request->get("page", 1);
        $type = Yii::$app->request->get("type", 1);
        $page_size = Yii::$app->request->get("rows", Yii::$app->params['default']['page_size']);
        $model = BsMember::find()->where('status >0 and status<9 and mtype < 4 ');
        $status = Yii::$app->request->get("status", "");
        $keyword = Yii::$app->request->get("keyword", "");
        $keytype = Yii::$app->request->get("keytype", "");
        if (!empty($status)) {
            $status = UtilFilter::addslashesStr($status);
            $model->andWhere(" status = $status");
        }
        //文本搜索模糊查询
        if (!empty($keyword)) {
            $keyword = UtilFilter::addslashesStr($keyword);
            $keytype = UtilFilter::addslashesStr($keytype);
            $keytypes = explode(',', $keytype);
            $Where = '';
            foreach ($keytypes as $k => $keytype) {
                if ($k == 0) {
                    $Where = "    $keytype like '%$keyword%' ";
                } else {
                    $Where .= " or $keytype like '%$keyword%' ";
                }
            }
            $model->andWhere(" $Where ");
        }


        if ($type == 1) {
            $select_arr = $this->getMemberId();
            $select_id = substr($select_arr['member_id'], 0, -1);
            if (!empty($select_id)) {
                $model->andWhere("id in ($select_id)");
            } else {
                $res = ['total' => '', 'rows' => []];
                return $res;
            }

        } elseif ($type == 0) {
            $select_arr = $this->getMemberWaitingId();
            $select_id = substr($select_arr['member_id'], 0, -1);
            if (!empty($select_id)) {
                $model->andWhere("id in ($select_id)");
            } else {
                $res = ['total' => '', 'rows' => []];
                return $res;
            }
        } else {
            $res = ['total' => '', 'rows' => []];
            return $res;
        }


        $total = $model->count();
        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();
        $datas = [];
        foreach ($data as $key => $item) {
            unset($item['pwd']);   //驱除密码字段
            $datas[$key] = $item;

            //添加验证状态数据
            $datas[$key]['type'] = $select_arr['status'][$item['id']];
        }

        $res = ['total' => $total, 'rows' => $datas];
        return $res;

    }

    /**
     * 获取高级会员申请待审核名单id
     * @return string
     */
    protected function getMemberWaitingId()
    {
        $data['company'] = BsSeniorCompany::find()->where('status = 1')->select('member_id,status')->asArray()->all();
        $data['incubator'] = BsSeniorIncubator::find()->where('status = 1')->select('member_id,status')->asArray()->all();
        $data['service'] = BsSeniorService::find()->where('status = 1')->select('member_id,status')->asArray()->all();
        $ret = [];
        $ret['member_id'] = '';
        foreach ($data as $value) {
            foreach ($value as $items) {
                foreach ($items as $kkk => $item) {
                    if ($kkk == 'member_id') {
                        $ret['member_id'] .= "$item,";
                    } else {
                        $ret['status'][$items['member_id']] = $item;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * 获取高级会员申请已审核核名单id
     * @return string
     */
    protected function getMemberId()
    {
        $data['company'] = BsSeniorCompany::find()->where('status != 1')->select('member_id,status')->asArray()->all();
        $data['incubator'] = BsSeniorIncubator::find()->where('status != 1')->select('member_id,status')->asArray()->all();
        $data['service'] = BsSeniorService::find()->where('status != 1')->select('member_id,status')->asArray()->all();
        $ret = [];
        $ret['member_id'] = '';
        foreach ($data as $value) {
            foreach ($value as $items) {
                foreach ($items as $kkk => $item) {
                    if ($kkk == 'member_id') {
                        $ret['member_id'] .= "$item,";
                    } else {
                        $ret['status'][$items['member_id']] = $item;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     *
     * 获取单个会员基本数据数据
     *
     * @access public
     * @since 1.0
     * @return null|static
     * @throws BadRequestHttpException
     */
    public function actionView()
    {
        $id = Yii::$app->request->get("member_id", 0);


        $BsMemberdata = BsMember::find()->where('id = :id', [':id' => $id])->asArray()->one();
        $data = null;
        if ($BsMemberdata['mtype'] == 1) {
            $data = $this->Company($id);
        } elseif ($BsMemberdata['mtype'] == 2) {
            $data = $this->Incubator($id);
        } elseif ($BsMemberdata['mtype'] == 3) {
            $data = $this->Service($id);

        } else {

            throw new BadRequestHttpException('请求数据错误。');
        }

        return $data;
    }

    /**
     * 获取企业信息
     * @param $id
     * @return array|mixed|null|\yii\db\ActiveRecord
     */
    private function Company($id)
    {

        //bs_senior_company行业
        $data = BsSeniorCompany::find()->where('member_id = :id', [":id" => $id])->asArray()->one();
        if ($data) {
            $data['username'] = $this->_login_user_name;
            $data = $this->BsSeniorOther($data, $id);
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * @param $id
     * @return array|mixed|null|\yii\db\ActiveRecord
     * 获取孵化器信息
     */
    private function Incubator($id)
    {
        //bs_senior_incubator 孵化器
        $data = BsSeniorIncubator::find()->where('member_id = :id', [":id" => $id])->asArray()->one();
        if ($data) {
            $data = $this->BsSeniorPlatform($data, $id);
            $data['username'] = $this->_login_user_name;
            $data = $this->BsSeniorOther($data, $id);
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * @param $id
     * @return array|mixed|null|\yii\db\ActiveRecord
     * 获取服务机构信息
     */
    private function Service($id)
    {
        //bs_senior_service 服务机构
        $data = BsSeniorService::find()->where('member_id = :id', [":id" => $id])->asArray()->one();
        if ($data) {
            $data['username'] = $this->_login_user_name;
            $data = $this->BsSeniorOther($data, $id);
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * 获取股东构成和销售额
     * @param $data
     * @param $id
     * @return mixed
     */
    protected function BsSeniorOther($data, $id)
    {
        //bs_senior_industry  行业信息
        $data['industry'] = BsSeniorIndustry::find()->where('member_id = :mid and senior_id = :sid', [':mid' => $id, ':sid' => $data['id']])->asArray()->all();

        //bs_senior_shareholder  股东构成
        $data['shareholder'] = BsSeniorShareholder::find()->select('name,investment,equity_ratio,education,school,workcond,investment_type')
            ->where('member_id = :mid and senior_id = :sid', [':mid' => $id, ':sid' => $data['id']])->asArray()->all();

        //bs_senior_sales 销售收入
        $year = date('Y') - 4;//前三年销售额
        $data['sales'] = BsSeniorSales::find()->select('year,income,profits,tax')
            ->where("member_id = :mid and senior_id = :sid and year > $year ", [':mid' => $id, ':sid' => $data['id']])->asArray()->all();

        //服务机构:服务内容
        $data['service_content'] = BsSeniorServiceContent::find()->select('senior_service_id,content_id')
            ->where("senior_service_id = {$data['id']}")->asArray()->all();
        return $data;
    }

    /**
     * 获取孵化器平台建设表
     * @param $data
     * @param $id
     * @return mixed
     */
    protected function BsSeniorPlatform($data, $id)
    {

        $data['platform'] = BsSeniorPlatform::find()
            ->where('member_id = :mid and senior_id = :sid', [':mid' => $id, ':sid' => $data['id']])->asArray()->all();
        return $data;
    }

    /**
     * 变更高级会员状态
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionSetlevel()
    {
        $parm['id'] = Yii::$app->request->get("id", 0);
        $parm['levle'] = Yii::$app->request->get("level", 0);

        $ids = isset($parm['id']) ? $parm['id'] : "";
        $status = isset($parm['levle']) ? $parm['levle'] : "";

        if (empty($ids) || empty($status)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);
            foreach ($ids as $i) {
                $model = BsMember::findOne($i);
                $model->setattribute("level", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }

    }

    /**
     * 保存高级会员审核日志
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSeniorapproval()
    {
        $Params = Yii::$app->getRequest()->getBodyParams();
        $this->credit($Params);
        $updateMember = $this->updateMember($Params);
        $model = new BsLogsCheck();
        unset($Params['member_id']);
        $model->setAttributes($Params);
        $model->setAttribute('creater', $this->_login_user_id);
        $model->setAttribute('created', date("Y-m-d H:i:s"));
        if ($model->save() || $updateMember) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('部分数据操作失败,请检查参数。');
        }
    }

    /**
     * 企业评级
     * @param $ID
     */
    private function credit($ID)
    {

        if ($ID['cstatus'] == 2) {  //判断是否通过审核

            $BsMemberdata = BsMember::find()->select('mtype')->where('id = :id', [':id' => $ID['member_id']])->asArray()->one();

            if ($BsMemberdata['mtype'] == 1 || $BsMemberdata['mtype'] == 3) { //判断是否为

                if ($BsMemberdata['mtype'] == 1) {
                    //获取企业信息
                    $data = $this->Company($ID['member_id']);
                    //评星处理
                    $save = ComStarred::Grade($data);

                } elseif ($BsMemberdata['mtype'] == 3) {

                    $data = $this->Service($ID['member_id']);
                    $data['rankings'] = BsCoupon::find()->select('distinct COUNT(id) AS count,service_member_id,SUM(contract_price) as price')
                        ->where('status = 2')->groupBy('service_id')->orderBy('COUNT(id) asc')->asArray()->all();
                    $save = ServStarred::Grade($data);
                }
//                  return $save;
                $modle = BsCredit::find()->where('member_id = :mid', [":mid" => $ID['member_id']]);
                $count = $modle->count();
                if ($count > 0) {       //更新
                    $modleu = BsCredit::find()->where('member_id = :mid', [":mid" => $ID['member_id']])->one();
                    $modleu->setAttribute('member_id', $ID['member_id']);
                    $modleu->setAttribute('level', $save);
                    $modleu->setAttribute('year', date('Y'));
                    $modleu->save();
                } else {
                    $modles = new BsCredit();   //保存
                    $modles->setAttribute('member_id', $ID['member_id']);
                    $modles->setAttribute('level', $save);
                    $modles->setAttribute('year', date('Y'));
                    $modles->save();
                }
            }

        }
    }

    /**
     * 更新会员审核状态
     * @param $Params
     * @return bool
     */
    public function updateMember($Params)
    {
        $id = $Params['member_id'];

        $BsMemberdata = BsMember::find()->where('id = :id', [':id' => $id])->asArray()->one();
        if ($BsMemberdata['mtype'] == 1) {

            //企业
            $data = BsSeniorCompany::findOne($Params['cid']);
            $data->setAttribute('status', $Params['cstatus']);
            if ($data->save()) {
                return true;
            } else {
                return false;
            }
        } elseif ($BsMemberdata['mtype'] == 2) {

            //孵化器
            $data = BsSeniorIncubator::findOne($Params['cid']);
            $data->setAttribute('status', $Params['cstatus']);
            if ($data->save()) {
                return true;
            } else {
                return false;
            }
        } elseif ($BsMemberdata['mtype'] == 3) {

            //服务机构
            $data = BsSeniorService::findOne($Params['cid']);
            $data->setAttribute('status', $Params['cstatus']);
            if ($data->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     *
     * 修改会员数据的各种状态  启用,禁用，保留，删除等。
     *
     * @access public
     * @since 1.0
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
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
                $model = BsMember::findOne($i);
                $model->setattribute("status", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }

    //更新权限
    public function actionAccess()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();

        $ids = isset($parm['ids']) ? $parm['ids'] : "";
        $access = isset($parm['access']) ? $parm['access'] : "";


        if (empty($ids) || empty($access)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        } else {
            $ids = explode(",", $ids);
            foreach ($ids as $i) {
                $model = BsMember::findOne($i);
                $model->setattribute("access", $access);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }


    //取出权限列表树
    public function actionGetbsmenu()
    {
        $access = explode(',', Yii::$app->request->get("access", ""));


        $data = BsMenu::find()->select('id,parent_id as pid,name as text,des')->asArray()->all();
        $datas = [];
        foreach ($data as $key => $item) {
            $datas[$key] = $item;
            if (in_array($item['id'], $access)) {
                $datas[$key]['checked'] = 'true';
            }
            if ($item['des'] !== null) {
                $datas[$key]['text'] = $item['text'] . "({$item['des']})";
            } else {
                $datas[$key]['text'] = $item['text'];
            }

        }

        return Cmscommon::Getcat($datas);
    }

    //获取行业列表
    public function actionGetbsbdatatype()
    {
        $type = Yii::$app->request->get("type", 0);
        $get = new BdataServices();
        $datas = $get->getDataByType($type);
        $ret = [];
        foreach ($datas as $key => $data) {
            $ret[$key]['id'] = $data['id'];
            $ret[$key]['text'] = $data['name'];
        }

        return $ret;
    }


    //获取孵化器
    public function actionGetincubator()
    {
        $model = BsMemberIncubator::find()->select('id,name as text ,member_id')->asArray()->all();
        $arr = [];
        foreach ($model as $key => $item) {
            $arr[$key]['id'] = $item['member_id'];
            $arr[$key]['text'] = $item['text'];
        }
        return $arr;
    }

    //获取孵化器用户名
    public function actionGetincubatormember()
    {
        $id = Yii::$app->request->get("id", 0);
        $model = BsMember::find()->select('id,login_name')->where('id = :id', [':id' => $id])->asArray()->all();
        return $model[0]['login_name'];
    }

    public function actionRepwd()
    {
        $id = Yii::$app->request->get("id", 0);

        $model = BsMember::findOne($id);

        if ($model) {
            $parm = Yii::$app->getRequest()->getBodyParams();

            if (isset($parm['pwd']) && !empty($parm['pwd'])) {
                $parm['pwd'] = UtilEncryption::encryptMD5($parm['pwd'], Yii::$app->params['secret']['pwd']);
            } else {
                unset($parm['pwd']);
            }

            if (isset($parm['id'])) {
                unset($parm['id']);
            }

            if (isset($parm['role_ids'])) {
                if (is_array($parm['role_ids'])) {
                    $parm['role_ids'] = implode(",", $parm['role_ids']);
                } else {
                    $parm['role_ids'] = $parm['role_ids'];
                }
            }

            $model->load($parm, '');

            if ($model->save()) {
                unset($model['pwd']);
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }

        } else {
            throw new BadRequestHttpException('请求数据错误。');
        }
    }


}