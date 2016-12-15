<?php
header("Access-Control-Allow-Origin: *");//跨域请求

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('ROOT_DIR') or define('ROOT_DIR', __DIR__);

require(__DIR__ . '/../../common/vendor/autoload.php');
require(__DIR__ . '/../../common/vendor/yiisoft/yii2/Yii.php');

require(__DIR__ . '/../../common/vendor/oss/autoload.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
