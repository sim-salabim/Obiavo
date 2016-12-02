<?php

use yii\bootstrap\Modal;

Modal::begin([
    'id' => 'cities',
    'header' => '<h2>Выберите город</h2>',
    'clientOptions' => ['show' => true]
]);
?>

<?php  foreach ($cities as $city){ ?>
<div><?= $city->_text?> - <?= $city->region->_text?></div>
<?php } ?>

<?php Modal::end(); ?>