<?php
return [
 //   ['class' => 'frontend\rules\url\CategoryUrlRule', 'connectionID' => 'db'],
    '<action:(login|registration|logout|recovery|reset)>' => 'auth/<action>',
    'GET /vybor-goroda' => 'location/vybor-goroda',
    'GET /podat-obiavlenie' => 'ad/new-add/',
    'POST /podat-obiavlenie' => 'ad/new-add-login/',
    'POST /get-sub-categories' => 'categories/get-sub-categories',
    'POST /get-category-placement' => 'categories/get-category-placement',
    'GET /im' => 'users/im',
    'POST /publish-add' => 'ad/add',
    'POST /files-upload' => 'files/upload',
    'POST /remove-file' => 'files/remove',
    'GET /nastroiki/' => 'users/settings',
    'POST /nastroiki/' => 'users/settings',
    'GET /moi-obiavleniya' => 'users/my-ads',
    'GET /poisk' => 'ad/search',
    'POST /apply-add/' => 'ad/add',

    /**
     * Класс правила ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationUrlRule',
        'pattern' => '/<domain:([0-9a-zA-Z\-]+)>/',
        'route' => 'site/index',
    ],
    /**
     * Класс правила КАТЕГОРИЯ/ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationCategoryUrlRule',
        'pattern' => '/<city:([0-9a-zA-Z\-]+)>/<category:([0-9a-zA-Z\-]+)>',
        'route' => 'categories/index',
        'defaults' => ['city' => null],
    ],
    /**
     * Класс правила ПРОДАТЬ/КАТЕГОРИЯ/ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationCategoryUrlRule',
        'pattern' => '/<city:([0-9a-zA-Z\-]+)>/<category:([0-9a-zA-Z\-]+)>/<placement:\w+>',
        'route' => 'categories/index',
        'defaults' => ['city' => null],
    ],
    /**
     * Класс правила ГОРОД
     */
    [
        'class' => 'frontend\rules\url\SelectLocationUrlRule',
        'pattern' => '/select-location/<domain:([0-9a-zA-Z\-]+)>/',
        'route' => 'location/select-location',
    ],
    /**
     * Объявления
     */
    [
        'class' => 'frontend\rules\url\AdUrlRule',
        'pattern' => '/<adUrl:([0-9a-zA-Z\-]+)>/',
        'route' => 'ad/view',
    ],
    [
        'class' => 'frontend\rules\url\AdUrlRule',
        'pattern' => '/<adUrl:([0-9a-zA-Z\-]+)>/<city:([0-9a-zA-Z\-]+)>',
        'route' => 'ad/view',
    ],
    /**
     *  CMS страницы
     */
    [
        'class' => 'frontend\rules\url\CmsUrlRule',
        'pattern' => '/<cmsUrl:([0-9a-zA-Z\-]+)>',
        'route' => 'cms/view',
    ],
    [
        'pattern'=>'sitemap-show',
        'route'=>'sitemap/index',
        'suffix'=>'/'
    ],
    [
        'pattern'=>'sitemap',
        'route'=>'sitemap/show-index',
        'suffix'=>'.xml'
    ],
    [
        'pattern'=>'sitemap<index:([0-9]+)>.city_<city:([0-9_]+)>',
        'route'=>'sitemap/city',
        'suffix'=>'.xml'
    ],
    [
        'pattern'=>'sitemap<index:([0-9a-zA-Z\-]+)>.city_<city:([0-9]+)>.pl_<offset:([0-9_]+)>',
        'route'=>'sitemap/placement-city',
        'suffix'=>'.xml'
    ],
    [
        'pattern'=>'sitemap<index:([0-9]+)>.catpl_<offset:([0-9_]+)>',
        'route'=>'sitemap/placement',
        'suffix'=>'.xml'
    ],
    [
        'pattern'=>'sitemap<index:([0-9]+)>',
        'route'=>'sitemap/idx',
        'suffix'=>'.xml'
    ],
    [
        'pattern'=>'robots',
        'route'=>'robotstxt/index',
        'suffix'=>'.txt'
    ]
];