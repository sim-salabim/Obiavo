<?=  $this->render('/partials/_ads_list.php',
    [
        'ads' => $ads,
        'title' => __('My ads'),
        'no_ads_title' => __('You have no ads yet'),
    ]) ?>