<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->

<url>
  <loc><?php echo aurl('site/index');?></loc>
  <changefreq>always</changefreq>
  <priority>1.00</priority>
</url>
<url>
  <loc><?php echo aurl('channel/joke');?></loc>
  <changefreq>always</changefreq>
  <priority>0.80</priority>
</url>
  <loc><?php echo aurl('channel/lengtu');?></loc>
  <changefreq>always</changefreq>
  <priority>0.80</priority>
</url>
  <loc><?php echo aurl('channel/girl');?></loc>
  <changefreq>always</changefreq>
  <priority>0.80</priority>
</url>
  <loc><?php echo aurl('channel/video');?></loc>
  <changefreq>always</changefreq>
  <priority>0.80</priority>
</url>
<?php foreach ((array)$posts as $p):?>
<url>
  <loc><?php echo aurl('post/show', array('id'=>$p['id']));?></loc>
  <priority>0.60</priority>
</url>
<?php endforeach;?>
</urlset>