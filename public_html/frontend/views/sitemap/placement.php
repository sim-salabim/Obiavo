<?= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <? foreach($links as $link){?>
        <url>
            <loc><?= "https://".$current_domain."/".$link->category->_text->url."/".$link->placement->_text->url."/" ?></loc>
        </url>
    <? } ?>
</urlset>