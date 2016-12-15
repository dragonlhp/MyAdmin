<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
    ];
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [self::className(), 'depends' => self::className()]);
    }
    public static function addCss($view, $jsfile) {
        $view->registerCssFile($jsfile,['depends'=>[self::className()],'position'=>$view::POS_HEAD]);

    }
}
