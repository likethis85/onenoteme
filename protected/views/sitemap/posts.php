<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ((array)$posts as $p):?>
<url>
    <loc><?php echo aurl('post/show', array('id'=>$p['id']));?></loc>
    <priority>0.90</priority>
</url>
<?php endforeach;?>
</urlset>
