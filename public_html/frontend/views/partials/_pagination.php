<?
/**
 * ads_search - результат текущей выборки Ads()::getList()
 * library_search - обьект с параметрами списка, AdsSearch()
 * root_url - url без параметров
 */
$root_url = isset($root_url) ? $root_url : false;
$sort = (isset($_GET['sort'])) ? 'sort='.$_GET['sort'].'&' : '';
$direction = (isset($_GET['direction'])) ? 'direction='.$_GET['direction'].'&' : '';
$query = (isset($_GET['query'])) ? 'query='.$_GET['query'].'&' : '';
$nav_str = $sort.$direction.$query.'{key:page}';
$pages_amount = ceil(($ads_search['count'] / $library_search->limit));
$action = isset($current_action)  ? $current_action."/" : '';
$city = (Yii::$app->request->get('city')) ? Yii::$app->request->get('city')."/" : '';
$link = (isset($current_category) and $current_category) ? "/$current_category->url/$action".$city : '';
if($sort != '' OR $direction != ''){
    $link .= "?".$sort.$direction;
}
$link .= ($link == '' AND $query != '') ? "?".$query : $query;
if($link == '' AND $root_url) $link .= '/'.$root_url;
?>    <div class="col-12 placeholder-padding-0"><hr></div>
<div class="col-lg-12">

    <nav class="padding-top-10">
        <ul class="pagination">
            <? if($library_search->page != 1){?>
            <li class="page-item ">
                <? $prev_href = ($library_search->page == 2) ? $link : "?".str_replace('{key:page}','page='.($library_search->page - 1),$nav_str) ?>
                <a class="pagination-link" href="<?= $prev_href ?>">
                    <span aria-hidden="true"><?= __('Prv.') ?></span>
                </a>
            </li>
            <? } ?>
            <? if($library_search->page > 3){?>
                <li class="page-item"><span class="pagination-link multidot">...</span></li>
            <? } ?>
            <?
            $i = 1;
            while($i <= $pages_amount){?>
                <li class="page-item ">
                    <? if($i != $library_search->page){?>
                        <? if( $i > ($library_search->page - 3) AND $i < ($library_search->page + 3)){?>
                            <? $href = "?".str_replace('{key:page}','page='.$i,$nav_str) ?>
                            <a class="pagination-link" href="<?= $href ?>"><?= $i ?></a>
                        <? } ?>
                    <? }else{ ?>
                        <span class="pagination-link pagination-link-active"><?= $i ?></span>
                    <? } ?>
                </li>
            <? ++$i;
            } ?>
            <? if(($library_search->page + 3) <= $pages_amount){?>
                <li class="page-item"><span class="pagination-link multidot">...</span></li>
            <? } ?>
            <? if($library_search->page != $pages_amount){?>
            <li class="page-item ">
                <a class="pagination-link" href="?<?= str_replace('{key:page}','page='.($library_search->page + 1),$nav_str) ?>">
                    <span aria-hidden="true"><?= __('Nxt.') ?></span>
                </a>
            </li>
            <? } ?>
        </ul>
    </nav>
</div>