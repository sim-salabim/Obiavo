<?php

use yii\bootstrap\Modal;

Modal::begin([
    'id' => 'cities',
    'header' => "<h2>{$modal->title}</h2>",
    'clientOptions' => ['show' => true]
]);

echo $modal->content;

Modal::end();