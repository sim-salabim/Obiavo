<?= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <? foreach($links as $link){?>
        <url>
            <loc><?= "https://".$current_domain."/".$link->_text->url."/" ?></loc>
        </url>
        <? foreach($cities as $city){ ?>
            <url>
                <loc><?= "https://".$current_domain."/".$city['domain']."/".$link->_text->url."/" ?></loc>
            </url>
        <? } ?>
    <? } ?>
</urlset>