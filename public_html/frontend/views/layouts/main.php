<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

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
    <script src="https://unpkg.com/react@15/dist/react.min.js"></script>
    <script src="https://unpkg.com/react-dom@15/dist/react-dom.min.js"></script>

    <?php $this->head() ?>
    <?= frontend\widgets\Frontend::widget()?>
    <!-- Latest compiled and minified CSS -->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">-->

<!-- Latest compiled and minified JavaScript -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>-->


</head>
<body>    
<?php $this->beginBody() ?>

<h1><?= $this->title?></h1>
<div id="loadcontent-container" style="display: none"></div>
<div class="wrap main-flex-container">

        <?= $this->render(
            'content',
            ['content' => $content]
        ) ?>

        <?= $this->render(
            'right'
        ) ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-right">О проекте Контакты Оферта</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
