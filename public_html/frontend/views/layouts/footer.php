<!-- Footer -->
<footer class="page-footer font-small mdb-color lighten-3 pt-4">
    <?
    $current_lang_id = \frontend\components\Location::getDefaultLanguageId();
    ?>
    <!-- Footer Links -->
    <div class="container text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 mr-auto my-md-4 my-0 mt-4 mb-1">

                <!-- Content -->
                <p class="font-weight-bold"><?= Yii::$app->location->country->domain ?></p>
                <p>© <?= __('Obiavo') ?> - <?= __('free ads site') ?><?if(\frontend\components\Location::getDefaultLanguageId() == \common\models\Language::LANG_EN){ echo " ".__('in'); }?> <?= $pp_place ?>.</p>
                <p class="text-secondary">
                    <a rel="nofollow" href="<? if($current_lang_id == \common\models\Language::LANG_RU){ echo "/polzovatelskoe-soglashenie/";}else{ echo "/user-agreement/";}?>"><?= __('User agreement') ?></a>. <a rel="nofollow" href="<? if($current_lang_id == \common\models\Language::LANG_RU){ echo "/oferta/";}else{ echo "/offer/";}?>"><?= __('_Offer') ?></a>. <a rel="nofollow" href="<? if($current_lang_id == \common\models\Language::LANG_RU){ echo "/cookies/";}else{ echo "/cookies-policy/";}?>"><?= __('Information about cookies') ?></a>.</p>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none">

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 mr-auto my-md-4 my-0 mt-4 mb-1">

                <!-- Links -->
                <p class="font-weight-bold"><?= __('Read') ?></p>

                <ul class="list-unstyled">
                    <li class="footer-li-margin">

                        <a rel="nofollow"
                           href="<? if($current_lang_id == \common\models\Language::LANG_RU){ echo "/o-proekte/";}else{ echo "/about/";}?>"><?= __('_About the project') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="<? if($current_lang_id == \common\models\Language::LANG_RU){ echo "/rules/";}else{ echo "/terms-of-use/";}?>"><?= __('Rules') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="<? if($current_lang_id == \common\models\Language::LANG_RU){ echo "/reklama/";}else{ echo "/advertising/";}?>"><?= __('_Advertisement') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="/help/"><?= __('Help') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="<? if($current_lang_id == \common\models\Language::LANG_RU){ echo "/kontakty/";}else{ echo "/contacts/";}?>"><?= __('_Contacts') ?></a>

                    </li>
                </ul>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none">

            <!-- Grid column -->

            <!-- Grid column -->



            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 mr-auto my-4 my-0 mt-4 mb-1">
                <?= \common\models\Cms::getByTechname('site-header')->_text->seo_text3?>
            </div>
            <!-- Grid column -->

        </div>
        <!-- Grid row -->

    </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">
        <p></p>
    </div>
    <!-- Copyright -->

</footer>
<!-- Footer -->