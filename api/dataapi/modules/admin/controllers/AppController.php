<?php
/**
 *这是一个处理管理员的管理文件
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
use app\services\common\Cmscommon;
use app\models\BasicUser;
use app\models\BasicRole;
use app\models\BasicRoleMenu;
use app\models\BasicMenu;


class AppController extends QrckController
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
        $page_size = Yii::$app->request->get("page_size", Yii::$app->params['default']['page_size']);
        $model = BasicUser::find()->where("status < 9 ");
        $total = $model->count();


        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $rolename = "";
                if (!empty($value['role_ids'])) {
                    $roles = BasicRole::find()->where("id in (" . $value['role_ids'] . ")")->all();
                    if (count($roles)) {
                        foreach ($roles as $k => $v) {
                            $rolename .= empty($rolename) ? $v->name : "," . $v->name;
                        }
                    }
                }
                $data[$key]['role'] = $rolename;
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
        $data = BasicUser::findOne($id)->toArray();
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
        $model = new BasicUser();


        $parm = Yii::$app->getRequest()->getBodyParams();

        if (isset($parm['pwd'])) {
            $parm['pwd'] = UtilEncryption::encryptMD5($parm['pwd'], Yii::$app->params['secret']['pwd']);
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

        $model = BasicUser::findOne($id);

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
                $model = BasicUser::findOne($i);
                $model->setattribute("status", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }

    /**
     *
     * 获取管理员的权限菜单
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionMenus()
    {
        $pid = Yii::$app->request->get("pid", "");
        $type = intval(Yii::$app->request->get("t", "1"));//需求返回类型   1：数组  2：树
        $uid = intval($this->_login_user_id);

        $users = BasicUser::findOne($uid);//获取登录用户信息
        $menus = array();
        $mids = array();
        if ($users) {
            $roleid = $users->role_ids;

            if (!empty($roleid)) {
                $rolemenu = BasicRoleMenu::find()->where("role_id in (" . $roleid . ")")->all();
                if ($rolemenu) {
                    foreach ($rolemenu as $key => $value) {

                        $bmenu = BasicMenu::findOne($value->menu_id)->toArray();
                        @$mids[$bmenu['id']] = 1;

                        if (!empty($pid)) {
                            if ($bmenu['parent_id'] == $pid) {
                                $menus[] = $bmenu;
                                //当子集选中的时候，父集自动选中，除了最高级和指定的父集以外
                                $parent_id = $bmenu['parent_id'];
                                if (!array_key_exists($parent_id, $mids) && $pid != $parent_id && $parent_id > 0) {
                                    $pmenu = BasicMenu::findOne($parent_id)->toArray();
                                    $menus[] = $pmenu;

                                    @$mids[$parent_id] = 1;
                                }
                            }
                        } else {
                            $menus[] = $bmenu;
                            //当子集选中的时候，父集自动选中，除了最高级以外
                            $parent_id = $bmenu['parent_id'];
                            if (!array_key_exists($parent_id, $mids) && $parent_id > 0) {
                                $pmenu = BasicMenu::findOne($parent_id)->toArray();
                                $menus[] = $pmenu;
                                @$mids[$parent_id] = 1;
                            }
                        }

                    }
                    if ($type == 2) {
                        $menus = Cmscommon::Getcat($menus, 'id', 'parent_id');
                    }
                }
            }

        }

        return $menus;
    }

    /**
     *
     * 修改密码
     *
     * @access public
     * @since 1.0
     * @return array
     */
    public function actionPwd()
    {

        $parm = Yii::$app->getRequest()->getBodyParams();
        $oldpwd = isset($parm['oldpwd']) ? trim($parm['oldpwd']) : "";
        $newpwd = isset($parm['newpwd']) ? trim($parm['newpwd']) : "";
        if (empty($oldpwd) || empty($newpwd)) {
            throw new BadRequestHttpException('请求参数不能为空。', StateCode::REQUEST_ERROR);
        } else {
            $uid = $this->_login_user_id;
            $users = BasicUser::findOne($uid);
            $pwdsign = UtilEncryption::encryptMD5($oldpwd, Yii::$app->params['secret']['pwd']);
            if ($pwdsign == $users->pwd) {
                $newpwd = UtilEncryption::encryptMD5($newpwd, Yii::$app->params['secret']['pwd']);
                $users->setattribute("pwd", $newpwd);
                $res = $users->save();
                return $res;
            } else {
                throw new BadRequestHttpException('旧密码不正确。', StateCode::REQUEST_ERROR);
            }
        }
    }

}