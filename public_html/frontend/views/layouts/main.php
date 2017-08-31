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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::decode(strip_tags($this->title)) ?></title>
<!--        <link rel="stylesheet" href="/css/site.css">-->
<!--        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">-->

        <?php $this->head() ?>
        <?// frontend\widgets\Frontend::widget()?>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <header class="header">
        <?= $this->render('header');?>
    </header>

    <div class="jumbotron jumbotron-fluid mt-2" style="background: #ffffff; border-bottom: 1px solid #c0c0c0;">
        <div class="container">
            <h1 class="display-3"><?= $this->title?></h1>
            <p class="lead">Obiavo.ru - сайт бесплатных объявлений России. Ежедневно на сайте раpмещаются тысячи частных объявлений. 34454 - Объявления.</p>
        </div>
    </div>
    <div class="container">
        <?= $this->render(
            'content',
            ['content' => $content]
        ) ?>
    </div>

    <footer class="footer">
        <?= $this->render('footer');?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>