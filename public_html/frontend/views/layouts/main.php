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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::decode(strip_tags($this->title)) ?></title>
             <?php $this->head() ?>
    </head>
    <body>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.12&appId=<?= Yii::$app->params['uploadPath']?>&autoLogAppEvents=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <?php $this->beginBody() ?>

    <?= $this->render('header'); ?>
    <div id="wrapper">
        <div class="jumbotron jumbotron-fluid mt-2" style="background: #ffffff;">
            <div class="container">
                <h1 class="h1-header">
                    <? if(isset($this->params['seo_h1']) AND $this->params['seo_h1'] != ''){
                        echo $this->params['seo_h1'];
                    }else{
                        echo $this->title;
                    } ?>
                </h1>
                <? if(!isset($this->params['no_hr']) OR (isset($this->params['no_hr']) AND !$this->params['no_hr'])){?>
                    <div class="w-100"><hr></div>
                <? } ?>
                <?= $this->render('breadcrumbs', ['breadcrumbs' => (isset($this->params['breadcrumbs'])) ? $this->params['breadcrumbs'] : []]); ?>
                <?= $this->render(
                    'content',
                    ['content' => $content]
                ) ?>
                <div class="row padding-top-30">
                    <? if(isset($this->params['seo_h2']) AND $this->params['seo_h2'] != ''){?>
<!--                        <div class="col-12">-->
<!--                            <h2>--><?// //$this->params['seo_h2'] ?><!--</h2>-->
<!--                        </div>-->
                    <? } ?>
                    <? if(isset($this->params['seo_text'])){?>
                        <div class="col-12">
                            <?= $this->params['seo_text'] ?>
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
            if (document.getElementById("mySidenav").style.width == "0px" || document.getElementById("mySidenav").style.width == "") {
                document.getElementById("mySidenav").style.width = "250px";
                var headerHeight = document.getElementById("main-header").offsetHeight;
                var heignt;
                if(headerHeight == 70){
                    document.getElementById("mySidenav").style.top = '72px';
                }else if(headerHeight == 86){
                    document.getElementById("mySidenav").style.top = '88px'
                }
                heignt = document.getElementById("wrapper").clientHeight + 38;
                document.getElementById("mySidenav").style.height = heignt+"px";
            } else {
                document.getElementById("mySidenav").style.width = "0px";
            }
        }
    </script>
    </body>
    </html>
<?php $this->endPage() ?>