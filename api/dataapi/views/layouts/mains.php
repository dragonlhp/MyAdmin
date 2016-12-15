<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use app\assets\AppAsset;
use app\models\SMenuSearch;
use app\models\SMemberMenu;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
<!--    --><?//=Html::jsFile("@web/assets/mg/bower_components/jquery/dist/jquery.min.js")?>
<!--    --><?//=Html::jsFile("@web/assets/js/bootstrap-datepicker.js")?>
<!--    --><?//=Html::jsFile("@web/assets/js/dateRangeUtil.js")?>
    <?php $this->head() ?>
</head>
<body>
   <!-- Navigation -->
 <div>
    <div id="page-wrapper">
        <div class="row">
             <div class="col-lg-12">
                   <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
             </div>
        </div>
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
