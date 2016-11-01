<?php
return [
 //   ['class' => 'frontend\rules\url\CategoryUrlRule', 'connectionID' => 'db'],
    [
        'class' => 'frontend\rules\url\CategoryUrlRule',
        'pattern' => '<categoryType>/<categoryAlias>',
        'route' => 'site/redirect',
        'mode' => \yii\web\UrlRule::PARSING_ONLY,
    ],
    [
        'class' => 'frontend\rules\url\CategoryUrlRule',
        'pattern' => '<categoryType>/<categoryAlias>/<city>',
        'route' => 'site/index',
        'mode' => \yii\web\UrlRule::PARSING_ONLY,
    ],
];