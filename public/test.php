<?php
$url = 'http://f.waduanzi.com/pics/2011/11/24/20111124000719_4ecd1a379ee41.jpg?ad=asdf#d';

$info = parse_url($url);
$p = pathinfo($info['path'], PATHINFO_EXTENSION);

print_r($p);