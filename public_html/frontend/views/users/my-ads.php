<?=  $this->render('/partials/_ads_list.php',
    [
        'ads_search' => $ads_search,
        'loaded' => $loaded,
        'title' => __('My ads'),
        'no_ads_title' => __('You have no ads yet'),
    ]) ?>