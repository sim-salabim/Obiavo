<?php
return [
 //   ['class' => 'frontend\rules\url\CategoryUrlRule', 'connectionID' => 'db'],
    '<action:(login|registration|logout|recovery|reset)>' => 'auth/<action>',
    'GET /vybor-goroda' => 'location/vybor-goroda',
    'GET /podat-obiavlenie' => 'ad/new-add/',
    'POST /get-sub-categories' => 'categories/get-sub-categories',
    'POST /get-category-placement' => 'categories/get-category-placement',
    'GET /im' => 'users/im',
    'POST /publish-add' => 'ad/add',
    'POST /files-upload' => 'files/upload',
    'POST /remove-file' => 'files/remove',
    'GET /nastroiki' => 'users/settings',
    'GET /moi-obiavleniya' => 'users/my-ads',
    'GET /poisk' => 'ad/search',
    /**
     * Класс правила ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationUrlRule',
        'pattern' => '/<domain:\w+>/',
        'route' => 'site/index',
    ],
    /**
     * Класс правила КАТЕГОРИЯ/ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationCategoryUrlRule',
        'pattern' => '/<category:([0-9a-zA-Z\-]+)>/<city:\w+>',
        'route' => 'categories/index',
        'defaults' => ['city' => null],
    ],
    /**
     * Класс правила ПРОДАТЬ/КАТЕГОРИЯ/ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationCategoryUrlRule',
        'pattern' => '/<category:([0-9a-zA-Z\-]+)>/<placement:\w+>/<city:\w+>',
        'route' => 'categories/index',
        'defaults' => ['city' => null],
    ],
    /**
     * Класс правила ГОРОД
     */
    [
        'class' => 'frontend\rules\url\SelectLocationUrlRule',
        'pattern' => '/select-location/<domain:\w+>',
        'route' => 'location/select-location',
    ],
    /**
     * Объявления
     */
    [
        'class' => 'frontend\rules\url\AdUrlRule',
        'pattern' => '/<adUrl:([0-9a-zA-Z\-]+)>',
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
];