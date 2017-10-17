<?=  $this->render('/partials/_ads_list.php',
    [
        'ads_search' => $ads_search,
        'title' => __('Ads'),
        'loaded' => $loaded,
        'no_ads_title' => __('No ads found')
    ]) ?>