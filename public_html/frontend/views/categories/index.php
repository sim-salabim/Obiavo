<?php
 use frontend\helpers\ArrayHelper;
 use yii\helpers\Url;
 use common\models\Category;
 use frontend\widgets\Selectpicker;
 use frontend\helpers\TextHelper;
?>
<div class="content-header">
    <div class="navbar">
        <?php if (!empty($placements)) { ?>
            <?= $this->render('/partials/_action_select',
                [
                    'placements' => $placements,
                    'current_action' => $current_action,
                    'id' => uniqid("action-select-")
                ]);?>
        <?php } ?>
    </div>
</div>

<?= $this->render('list', compact('categories'));?>
<?=  $this->render('/partials/_ads_list.php',
    [
        'ads' => $ads,
        'title' => __('Ads'),
        'no_ads_title' => __('No ads found')
    ]) ?>
