<?php
return [
 //   ['class' => 'frontend\rules\url\CategoryUrlRule', 'connectionID' => 'db'],
    '<action:(login|registration)>' => 'auth/<action>',
    /**    
     * Класс правила ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationUrlRule',
        'pattern' => '/<city:\w+>',
        'route' => 'site/index',        
    ],
    /**
     * Класс правила КАТЕГОРИЯ/ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationCategoryUrlRule',
        'pattern' => '/<category:\w+>/<city:\w+>',
        'route' => 'categories/index',
        'defaults' => ['city' => null],        
    ],
    /**
     * Класс правила ПРОДАТЬ/КАТЕГОРИЯ/ГОРОД
     */
    [
        'class' => 'frontend\rules\url\LocationCategoryUrlRule',
        'pattern' => '/<placement:\w+>/<category:\w+>/<city:\w+>',
        'route' => 'categories/index',
        'defaults' => ['city' => null],        
    ],
];