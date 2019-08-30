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
    <html
        lang="<?= Yii::$app->language ?>"
        <? if(isset($this->params['opengraph_html']) and $this->params['opengraph_html']){?>
            prefix="og: http://ogp.me/ns#"
        <? } ?>>
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130047868-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-130047868-1');
        </script>
        <link rel="icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <? if(isset($this->params['opengraph_title'])){?>
            <meta property="og:title" content="<?= $this->params['opengraph_title'] ?>" />
        <? } ?>
        <? if(isset($this->params['opengraph_website']) and $this->params['opengraph_website']){?>
            <meta property="og:type" content="website" />
        <? } ?>
        <? if(isset($this->params['opengraph_url'])){?>
            <meta property="og:url" content="<?= $this->params['opengraph_url'] ?>" />
        <? } ?>
        <? if(isset($this->params['opengraph_image'])){?>
            <meta property="og:image" content="<?= $this->params['opengraph_image'] ?>" />
        <? } ?>
        <? if(isset($this->params['opengraph_desc'])){?>
            <meta property="og:description" content="<?= $this->params['opengraph_desc'] ?>" />
        <? } ?>
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
        <? if(isset($this->params['opengraph_html']) and $this->params['opengraph_html']){?>
            <script type="application/ld+json">
                {
                "@context": "http://schema.org/",
                "@type": "Product",
                "name": "<?= $this->params['opengraph_title'] ?>",
                "image": [
                "<?= $this->params['opengraph_image'] ?>"
                ],
                "description": "<?= $this->params['opengraph_desc'] ?>",
                "mpn": "<?= $this->params['opengraph_ad_id'] ?>",
                "offers": {
                "@type": "Offer",
                "availability": "http://schema.org/InStock",
                "priceCurrency": "<?= $this->params['opengraph_ad_currency'] ?>",
                "price": "<?= $this->params['opengraph_ad_price'] ?>",
                "seller": {
                "@type": "Organization",
                "name": "<?= $this->params['opengraph_ad_user_name'] ?>"
                }
                }
                }
            </script>
        <? }?>
    </head>
    <?
    $region_add = '';
    $in_place = Yii::$app->location->country->_text->name_rp;
    $pp_place = Yii::$app->location->country->_text->name_pp;
    if(Yii::$app->location->city AND (isset($_COOKIE['city']) and $_COOKIE['city'])){
        $in_place = Yii::$app->location->city->_text->name_rp;
        $pp_place = Yii::$app->location->city->_text->name_pp;
        $region_add = "/".Yii::$app->location->city->domain;
    }else{
        if(Yii::$app->location->region){
            $in_place = Yii::$app->location->region->_text->name_rp;
            $pp_place = Yii::$app->location->region->_text->name_pp;
            $region_add = "/".Yii::$app->location->region->domain;
        }
    }
    ?>
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
    <? if(!isset($this->params['location_domain'])){
        $this->params['location_domain'] = "/";
    }?>
    <?= $this->render('header',
        [
            'location_domain' => $this->params['location_domain'],
            'region_add'=>$region_add,
            'pp_place' => $pp_place,
            'in_place' => $in_place
        ]);
    ?>
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
                <? if(
                        (!isset($this->params['publish_page_hr']) or !$this->params['publish_page_hr'])
                        and (!isset($this->params['no_hr']) OR (isset($this->params['no_hr']) AND !$this->params['no_hr']))){?>
                    <div class="row">
                        <div class="w-100"><hr></div>
                    </div>
                <? }elseif (isset($this->params['publish_page_hr']) and $this->params['publish_page_hr']) { ?>
                    <hr>
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

        <?=
        $this->render('footer',
        [
            'region_add'=>$region_add,
            'pp_place' => $pp_place,
            'in_place' => $in_place
        ]
        );
        ?>
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
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter51334729 = new Ya.Metrika2({
                        id:51334729,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks2");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/51334729" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <!--LiveInternet counter--><script type="text/javascript">
        new Image().src = "//counter.yadro.ru/hit?r"+
            escape(document.referrer)+((typeof(screen)=="undefined")?"":
                ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
            ";h"+escape(document.title.substring(0,150))+
            ";"+Math.random();</script><!--/LiveInternet-->
    </body>
    </html>
<?php $this->endPage() ?>