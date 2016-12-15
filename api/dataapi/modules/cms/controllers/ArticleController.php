<?php


namespace app\modules\cms\controllers;

use app\exts\QrckController;
use app\models\CmsArticle;
use app\models\CmsArticleAccessory;
use app\services\common\DumpCode;
use app\services\common\StateCode;
use common\services\SerFilesUpload;
use common\utils\UtilFilter;
use kucha\ueditor\UEditor;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;


class ArticleController extends QrckController
{
    /**
     * 未完成工作
     * 文章条件查询,文章附件管理
     */
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
                'delete' => ['DELETE'],
                'status' => ['PUT']
            ],
        ];
        return $behaviors;
    }

    /**
     * 获取Article的数据列表
     * @access public
     * @sinc 1.0
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionIndex()
    {


        $category_id = Yii::$app->request->get("category_id", "");
        $status = Yii::$app->request->get("status", "");
        $keyword = Yii::$app->request->get("keyword", "");
        $keytype = Yii::$app->request->get("keytype", "");

        $page_no = Yii::$app->request->get("page", 1);
        $page_size = Yii::$app->request->get("rows", Yii::$app->params['default']['page_size']);
        $model = CmsArticle::find()->where('status<9');
        if (!empty($status)) {
            $status = UtilFilter::addslashesStr($status);
            $model->andWhere(" status = $status");
        }


        if (!empty($category_id)) {
            $category_id = UtilFilter::addslashesStr($category_id);
            $category = explode(',', $category_id);

            $values = "";
            foreach ($category as $key => $item) {

                if ($key == 0) {
                    $values .= "category_id = $item ";
                } else {
                    $values .= "or category_id = $item ";
                }
            }


            $model->andWhere("( $values )");
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

        $total = $model->count();
        $offset = ($page_no - 1) * $page_size;
        $data = $model->orderBy('id DESC')->offset($offset)->limit($page_size)->asArray()->all();

        return ['total' => $total, 'rows' => $data];

        /**
         * 一下代码为追加附件信息
         *
         * $dataAll = array();
         * foreach ($data as $key => $item) {
         * $dataAll[$key] = $item;
         * $dataAll[$key]['accessory'] = $this->Accessory($item['id']);
         * }
         * return ['total' => $total, 'rows' => $dataAll];
         *
         */
    }

    /**
     * 获取附件信息
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    private function Accessory($id)
    {
        $model = CmsArticleAccessory::find()->where("article_id = $id")->asArray()->all();
        return $model;
    }

    /**
     * 获取Article单个数据
     * @return null|static
     */
    public function actionView()
    {

        $id = Yii::$app->request->get('id', 0);
        $data = CmsArticle::findOne($id)->toArray();
        if ($data) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(StateCode::SUCCESS);
        }
        $data['accessory'] = $this->Accessory($id);
        return $data;
    }

    /**
     * 创建Article数据
     * @return Article
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
//        DumpCode::P(Yii::$app->getRequest()->getBodyParams());exit;

        $model = new CmsArticle();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->setAttribute('creater', $this->_login_user_id);


        if ($model->save()) {
            if (!empty($_FILES)) {
                $upret = $this->upload($model->id);
            } else {
                $upret = true;
            }
            if ($upret) {
                return $model;
            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('创建数据失败。');
            }
            return $model;
        }

    }


    /**
     * 修改Article数据
     * @return null|static
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate()
    {

        $id = Yii::$app->request->get("id", 0);
        $model = CmsArticle::findOne($id);
        if ($model) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            $model->setAttribute('updater', $this->_login_user_id);
            if ($model->save()) {
                if (!empty($_FILES) && count($_FILES) !== 0) {
                    $upret = $this->upload($model->id);
                    if ($upret) {
                        return $model;
                    } else {
                        throw new ServerErrorHttpException('文件上错误。');
                    }
                } else {
                    return $model;

                }

            } elseif (!$model->hasErrors()) {
                throw new ServerErrorHttpException('更新数据失败。');
            }
        } else {
            throw new BadRequestHttpException('更新数据失败。');
        }

    }

    /**
     * 附件删除
     * @return null|static
     * @throws ServerErrorHttpException
     */
    public function actionDeleteacc()
    {
        $name = Yii::$app->request->post('name', '');
        $article_id = Yii::$app->request->post('article_id', 1);
        $data = CmsArticleAccessory::find()->where(['and', "name='$name'", "article_id= $article_id"])->asArray()->all();
        $path = Yii::$app->params['default']['Upload_server_url'];
        $filename = $data[0]['path'];
        $dre = SerFilesUpload::delete($path, $filename);

        if ($dre == 'ok') {

            $model = CmsArticleAccessory::findOne($data[0]['id']);

            if ($model) {
                if ($model->delete() === false) {
                    throw new ServerErrorHttpException('删除失败');
                }
            }
            return $model;
        }
    }


    /**
     * 删除Article数据
     * @return null|static
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function actionDelete()
    {

        $id = Yii::$app->request->get('id', 0);

        $model = CmsArticle::findOne($id);

        if ($model) {
            if ($model->setAttributes(['status' => 9]) === false) {
                throw new ServerErrorHttpException('删除失败');

            } else {
                throw new BadRequestHttpException('请求数据错误!');

            }

        }
        return $model;
    }


    /**
     *
     * 修改Article数据的各种状态  ，删除，发布，取消发布等。
     *
     * @access public
     * @since 1.0
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
                $model = CmsArticle::findOne($i);
                $model->setattribute("status", $status);
                $model->save();
            }
            return ['code' => StateCode::SUCCESS];
        }
    }

    /**
     * 文件上传
     * @return string
     */
    static private function upload($id)
    {
        if (!empty($_FILES)) {
            //上传文件
            $img = null;
            $serverurl = Yii::$app->params['default']['Upload_server_url'];
            $data = [
                'path' => 'mg/cms/',
                'size' => Yii::$app->params['default']['upload_max_size'],
                'type' => Yii::$app->params['default']['Upload_type'],
            ];

            $filename = Yii::$app->params['default']['Upload_temp_url'];
            $img = SerFilesUpload::arrCurlupload($serverurl, $filename, $data);
            if (!$img) {
                return "文件服务器错误";

            }


            $models = new CmsArticleAccessory();
            foreach ($img as $key => $data) {
                $model = clone $models;
                $model->setAttributes(['name' => $data['name']]); //创建时间
                $model->setAttributes(['size' => "{$data['size']}"]); //创建时间
                $model->setAttributes(['path' => $data['img']]); //创建时间
                $model->setAttributes(['article_id' => $id]); //创建时间
                $model->setAttributes(['created' => date('Y-m-d H:i:s', time())]); //创建时间
                $model->setAttributes(['creater' => $key]); //创建作者


                $re[] = $model->save();

            }

            if ($re) {
                return $img;
            }

        } else {
            return false;
        }


    }

    function actionUeditor()
    {

    }

    function FileUpLoad()
    {
        //上传文件
        $img = null;
        $serverurl = Yii::$app->params['default']['Upload_server_url'];
        $data = [
            'path' => 'mg/cms/',
            'size' => Yii::$app->params['default']['upload_max_size'],
            'type' => Yii::$app->params['default']['Upload_type'],
        ];

        $filename = Yii::$app->params['default']['Upload_temp_url'];
        $img = SerFilesUpload::arrCurlupload($serverurl, $filename, $data);
        if (!$img) {
            return "文件服务器错误";

        } else {
            return $img;
        }
    }


}
