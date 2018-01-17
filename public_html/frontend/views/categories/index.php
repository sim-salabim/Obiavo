<?php
?>
<hr>
<?
$row_list = (isset($row_list)) ? $row_list : false;
$current_category = (isset($current_category))  ? $current_category : null;
?>
<?= $this->render('list', compact('categories', 'row_list', 'current_category'));?>
<?=  $this->render('/partials/_ads_list.php',
    [
        'ads_search' => $ads_search,
        'library_search'=> $library_search,
        'title' => __('Ads'),
        'no_ads_title' => __('No ads found'),
        'current_category' => $current_category
    ]) ?>
