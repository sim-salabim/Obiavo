<?php
use yii\helpers\Url;

/*
 * Тексты каких языков есть у данного объекта
 */
$languagesModelIds = yii\helpers\ArrayHelper::getColumn($languagesModel,'languages_id');
?>
<?php foreach ($languages as $language) { ?>
<div class="btn-group" role="group">
    <button type="button"
            class="btn btn-default loadcontent"
            data-toggle="dropdown"
            aria-expanded="true"
            data-link="<?= Url::toRoute(['save-lang','id' => $model->id,'languages_id' => $language->id]);?>">
        <span class="glyphicon <?= (in_array($language->id, $languagesModelIds)) ? 'glyphicon-ok' : 'glyphicon-minus';?>"
          <?= (in_array($language->id, $languagesModelIds)) ? '' : 'style="color:red"';?>
          aria-hidden="true"></span>
        <?= $language->code?>
    </button>
</div>
<?php } ?>