<?= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <? foreach($categories as $cat){?>
        <url>
            <loc><?= "https://".$current_domain."/".$city->domain."/".$cat->_text->url."/" ?></loc>
        </url>
    <? } ?>
</urlset>