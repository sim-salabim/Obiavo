<!-- Footer -->
<footer class="page-footer font-small mdb-color lighten-3 pt-4">

    <!-- Footer Links -->
    <div class="container text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div >

                <!-- Content -->
                <p class="font-weight-bold"><?= Yii::$app->location->country->domain ?></p>
                <p>© <?= __('Obiavo') ?> - <?= __('free ads site') ?> <?= __('in') ?> <?= $pp_place ?>.</p>
                <p class="text-secondary"><a rel="nofollow" href="/polzovatelskoe-soglashenie/"><?= __('User agreement') ?></a>. <a rel="nofollow" href="/oferta/"><?= __('_Offer') ?></a>. <a rel="nofollow" href="/cookies/"><?= __('Information about') ?> cookies</a>.</p>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none">

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 mr-auto my-md-4 my-0 mt-4 mb-1">

                <!-- Links -->
                <p class="font-weight-bold"><?= __('Read') ?></p>

                <ul class="list-unstyled">
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="/o-proekte/"><?= __('About the project') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="/rules/"><?= __('Rules') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="/reklama/"><?= __('_Advertisement') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="/help/"><?= __('Help') ?></a>

                    </li>
                    <li class="footer-li-margin">

                        <a rel="nofollow" href="/kontakty/"><?= __('_Contacts') ?></a>

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