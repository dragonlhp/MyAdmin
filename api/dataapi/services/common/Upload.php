<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/11/1
 * 功能说明:
 */

namespace app\services\common;

namespace api\controllers;

use yii;

/**
 * 类名称： uploadcontroller
 * 类说明：
 */
class Upload extends \yii\rest\ActiveController {

    public $documentPath = '..\..\assets\uploads\cms\flinkimg';//上传路径
    public $modelClass = 'app\models\UploadForm';

    public function actions() {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }

    public function actionUpdate() {
        $postdata = fopen($_FILES['data']['tmp_name'], "r");
        /* Get file extension */
        $extension = substr($_FILES['data']['name'], strrpos($_FILES['data']['name'], '.'));
        /* Generate unique name */
        $filename = $this->documentPath . uniqid() . $extension;
        /* Open a file for writing */
        $fp = fopen($filename, "w");
        /* Read the data 1 KB at a time
          and write to the file */
        while ($data = fread($postdata, 1024))
            fwrite($fp, $data);
        /* Close the streams */
        fclose($fp);
        fclose($postdata);
        /* the result object that is sent to client */
        $result['filename'] = $filename;
        $result['document'] = $_FILES['data']['name'];
        $result['create_time'] = date("Y-m-d H:i:s");
        return $result;
    }

}