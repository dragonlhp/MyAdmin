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
use app\services\admin\SerMenu;
use app\models\BasicMenu;


class MenuController extends QrckController {

    public function behaviors(){

        $behaviors = parent::behaviors();
        $behaviors[ 'verbs' ] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['GET'],
                'view' => ['GET'],
                'create' => ['POST'],
                'update' => ['PUT'],
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

        $parent_id = Yii::$app->request->get("parent_id", 0);
        $tree = SerMenu::gettree($parent_id);
        return $tree;
    }

    /**
     *
     * 获取单个菜单的数据
     *
     * @access public
     * @since 1.0
     * @return array
     * @return null|static
     * @throws BadRequestHttpException
     */
    public function actionView(){

        $id = Yii::$app->request->get("id", 0);
        $data = BasicMenu::findOne($id);
        if($data) {
            return $data;
        }else {
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
     * @return BasicMenu
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate(){

        $model = new BasicMenu();


        $parm = Yii::$app->getRequest()->getBodyParams();
        $model->load($parm, '');
        if($model->save()) {
            return $model;
        }elseif(!$model->hasErrors()) {
            throw new ServerErrorHttpException('创建数据失败。');
        }
    }

    /**
     *
     * 更新菜单数据
     *
     * @access public
     * @since 1.0
     * @return array
     * @return null|static
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate(){

        $id = Yii::$app->request->get("id", 0);

        $model = BasicMenu::findOne($id);

        if($model) {
            $parm = Yii::$app->getRequest()->getBodyParams();
            $model->load($parm, '');

            if($model->save()) {
                return $model;
            }elseif(!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }

        }else {
            throw new BadRequestHttpException('请求数据错误。');
        }

    }

    /**
     *
     * 删除菜单数据
     *
     * @access public
     * @since 1.0
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDelete(){

        $parm = Yii::$app->getRequest()->getBodyParams();
        $ids = isset($parm[ 'ids' ]) ? $parm[ 'ids' ] : "";

        if(empty($ids)) {
            throw new BadRequestHttpException('请求数据错误。', StateCode::REQUEST_ERROR);
        }else {
            $errflag = false;
            $ids = explode(",", $ids);
            foreach($ids as $i) {
                $model = BasicMenu::findOne($i);
                $count = BasicMenu::find()->where(['parent_id' => $i])->count();
                if($count > 0) {
                    $errflag = true;
                }else {
                    $model->delete();
                }
            }
            if(!$errflag) {
                return ['code' => StateCode::SUCCESS];//删除成功
            }else {
                return ['code' => StateCode::SOME_ERROR];//部分数据操作失败。
            }

        }
    }

}