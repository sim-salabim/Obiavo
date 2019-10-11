<?php

use yii\helpers\Url;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->getFullName()?></p>

                <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Категории', 'icon' => 'bars', 'url' => ['/categories']],
                    ['label' => 'Языки', 'icon' => 'language', 'url' => ['/languages']],
                    ['label' => 'Страны', 'icon' => 'flag-o', 'url' => ['/countries']],
                    ['label' => 'Настройка порядка городов', 'icon' => 'building-o', 'url' => ['/cities/order-country-list']],
                    ['label' => 'Реклама', 'icon' => 'usd', 'url' => ['/advertising']],
                    ['label' => 'Пoльзователи', 'icon' => 'users', 'url' => ['/users']],
                    ['label' => 'Пoльзователи', 'icon' => 'users', 'url' => ['/users']],
                    ['label' => 'Типы объявлений', 'icon' => '', 'url' => ['/placements']],
                    ['label' => 'CMS страницы', 'icon' => 'file-o', 'url' => ['/cms']],
                    ['label' => 'Соц Сети', 'icon' => 'facebook-square', 'url' => ['/sn']],
                    ['label' => 'Основные группы соцсетей', 'icon' => 'object-group', 'url' => ['/sn-main-groups']],
                    ['label' => 'Сообщества соцсетей', 'icon' => 'child', 'url' => ['/sn-groups']],
                    ['label' => 'Автопостинг', 'icon' => 'eye', 'url' => ['/autoposting-tasks']],
                    ['label' => 'Sitemap', 'icon' => 'map-o', 'url' => ['/sitemap']],
                    ['label' => 'Настройки', 'icon' => 'cog', 'url' => ['/settings']],
                    ['label' => 'SEO подачи', 'icon' => 'eye', 'url' => ['/application-seo']],
                    ['label' => 'Подсчет обьявлений', 'icon' => 'magnet', 'url' => ['/counter']],
                    ['label' => 'Модерация', 'icon' => 'binoculars', 'url' => ['/moderation']],

                    ['label' => 'Tools', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'На главную', 'icon' => 'dashboard go-frontend-domain', 'url' => \Yii::$app->params['staticDomain']],
                    /*
                    [
                        'label' => 'Same tools',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'fa fa-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                     */
                ],
            ]
        ) ?>

    </section>

</aside>

<style>
.user-panel > .info > p {
    white-space: normal;
}
</style>