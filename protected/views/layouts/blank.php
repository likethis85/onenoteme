<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta name="author" content="waduanzi.com" />
<meta name="copyright" content="Copyright (c) 2011-2913 waduanzi.com All Rights Reserved." />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<script type="text/javascript">
var wdz_logined = <?php echo (int)!user()->isGuest;?>;
var wdz_quick_login_url = '<?php echo aurl('site/quicklogin');?>';
var _hmt = _hmt || [];
_hmt && _hmt.push(['_setCustomVar', 1, 'guest', <?php echo (int)user()->isGuest;?>, 2]);
</script>
<?php echo param('header_html');?>
</head>
<body>
<?php echo $content;?>

<?php $this->renderPartial('/public/tongji');?>
</body>
</html>


<?php
cs()->registerMetaTag('text/html; charset=utf-8', null, 'content-type')
    ->registerCssFile(sbu('libs/bootstrap/css/bootstrap.min.css'))
    ->registerCssFile(sbu('styles/cd-basic.css'))
    ->registerCssFile(sbu('styles/cd-main.css'))
    ->registerCoreScript('jquery')
    ->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('scripts/cd-main.js'), CClientScript::POS_END)
    ->registerLinkTag('alternate', 'application/rss+xml', aurl('feed'), null, array('title'=>app()->name . ' » Feed'));

YII_DEBUG || cs()->scriptMap = array(
    'bootstrap.min.css' => sbu('styles/cd-all.min.css?t=2013014001'),
    'cd-basic.css' => sbu('styles/cd-all.min.css?t=2013014001'),
    'cd-main.css' => sbu('styles/cd-all.min.css?t=2013014001'),
    'bootstrap.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'jquery.lazyload.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'jquery.infinitescroll.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'jquery.masonry.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'cd-main.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'json.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
);
?>
