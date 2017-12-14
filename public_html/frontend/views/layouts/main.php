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
        <? if(isset($this->params['canonical']) AND $this->params['canonical']){?>
            <link rel="canonical" href="<?= $this->params['canonical'] ?>">
        <? } ?>
        <? if(isset($this->params['prev']) AND $this->params['prev']){?>
            <link rel="prev" href="<?= $this->params['prev'] ?>">
        <? } ?>
        <? if(isset($this->params['next']) AND $this->params['next']){?>
            <link rel="next" href="<?= $this->params['next'] ?>">
        <? } ?>
        <? if(isset($this->params['seo_desc']) AND $this->params['seo_desc']){?>
            <meta name="description" content="<?= $this->params['seo_desc'] ?>">
        <? } ?>
        <? if(isset($this->params['seo_keywords']) AND $this->params['seo_keywords']){?>
            <meta name="keywords" content="<?= $this->params['seo_keywords'] ?>">
        <? } ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::decode(strip_tags($this->title)) ?></title>
             <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $this->render('header'); ?>
    <div id="wrapper">
        <div class="jumbotron jumbotron-fluid mt-2" style="background: #ffffff;">
            <div class="container">
                <h1 class="display-3">
                    <? if(isset($this->params['seo_h1']) AND $this->params['seo_h1'] != ''){
                        echo $this->params['seo_h1'];
                    }else{
                        echo $this->title;
                    } ?>
                </h1>
                <?= $this->render('breadcrumbs', ['breadcrumbs' => (isset($this->params['breadcrumbs'])) ? $this->params['breadcrumbs'] : []]); ?>
                <?= $this->render(
                    'content',
                    ['content' => $content]
                ) ?>
                <div class="container">
                    <? if(isset($this->params['seo_h2']) AND $this->params['seo_h2'] != ''){?>
                        <div class="col-12">
                            <h2><?= $this->params['seo_h2'] ?></h2>
                        </div>
                    <? } ?>
                    <? if(isset($this->params['seo_text'])){?>
                        <div class="col-12">
                            <p class="lead"><?= $this->params['seo_text'] ?></p>
                        </div>
                    <? } ?>
                </div>
            </div>
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