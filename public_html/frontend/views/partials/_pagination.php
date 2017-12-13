<?
/**
 * ads_search - результат текущей выборки Ads()::getList()
 * library_search - обьект с параметрами списка, AdsSearch()
 */
$sort = (isset($_GET['sort'])) ? 'sort='.$_GET['sort'].'&' : '';
$direction = (isset($_GET['direction'])) ? 'direction='.$_GET['direction'].'&' : '';
$nav_str = $sort.$direction.'{key:page}';
$pages_amount = ceil(($ads_search['count'] / $library_search->limit));
?>
<nav class=padding-top-10">
    <ul class="pagination">
        <li class="page-item <? if($library_search->page == 1){?>disabled<? } ?>">
            <a class="page-link" href="?<?= str_replace('{key:page}','page='.$library_search->page - 1,$nav_str) ?>">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?
        $i = 1;
        while($i <= $pages_amount){?>
            <li class="page-item <? if($i == $library_search->page){?>active disabled<? } ?>">
                <a class="page-link" href="?<?= str_replace('{key:page}','page='.$i,$nav_str) ?>"><?= $i ?></a>
            </li>
        <? ++$i;
        } ?>
        <li class="page-item  <? if($library_search->page == $pages_amount){?>disabled<? } ?>">
            <a class="page-link" href="?<?= str_replace('{key:page}','page='.($library_search->page + 1),$nav_str) ?>">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>