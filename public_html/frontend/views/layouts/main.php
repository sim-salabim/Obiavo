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
             <?php $this->head() ?>
        <? // frontend\widgets\Frontend::widget()?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $this->render('header'); ?>
    <div id="wrapper">
        <div class="jumbotron jumbotron-fluid mt-2" style="background: #ffffff; border-bottom: 1px solid #c0c0c0;">
            <div class="container">
                <h1 class="display-3"><?= $this->title ?></h1>
                <p class="lead">Obiavo.ru - сайт бесплатных объявлений России. Ежедневно на сайте размещаются тысячи
                    частных объявлений. 34454 - Объявления.</p>
            </div>
        </div>
        <div class="container">
            <?= $this->render('breadcrumbs', ['breadcrumbs' => (isset($this->params['breadcrumbs'])) ? $this->params['breadcrumbs'] : []]); ?>
            <?= $this->render(
                'content',
                ['content' => $content]
            ) ?>
        </div>
    </div>

        <?= $this->render('footer'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
            integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
            crossorigin="anonymous"></script>

    <?php $this->endBody() ?>
    <script>
        function openNav() {
            console.log(document.getElementById("mySidenav").style.width);
            if (document.getElementById("mySidenav").style.width == "0px" || document.getElementById("mySidenav").style.width == "") {
                document.getElementById("mySidenav").style.width = "250px";
                document.getElementById("wrapper").style.marginLeft = "250px";
            } else {
                document.getElementById("mySidenav").style.width = "0px";
                document.getElementById("wrapper").style.marginLeft = "0px";
            }
        }
    </script>
    </body>
    </html>
<?php $this->endPage() ?>