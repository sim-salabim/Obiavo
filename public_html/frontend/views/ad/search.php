<?=  $this->render('/partials/_ads_list.php',
    [
        'title' => __('Ads'),
        'ads_search' => (new \common\models\Ads())->getList($library_search),
        'no_ads_title' => __('No ads found'),
        'library_search' => $library_search
    ]) ?>