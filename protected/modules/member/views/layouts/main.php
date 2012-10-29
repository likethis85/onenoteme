<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo app()->charset?>" />
    <title><?php echo $this->pageTitle;?></title>
    <meta name="MSSmartTagsPreventParsing" content="true" />
    <meta name="author" content="waduanzi.com" />
    <meta name="copyright" content="Copyright (c) 2011-2912 waduanzi.com All Rights Reserved." />
    <meta name="copyright" content="Copyright (c) 2009-2012 24beta.com All Rights Reserved." />
    <link rel="start" href="<?php echo $this->memberHomeUrl;?>" title="Home" />
    <link rel="home" href="<?php echo $this->memberHomeUrl;?>" title="Home" />
    <link rel="stylesheet" type="text/css" href="<?php echo tbu('styles/cd-basic.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo tbu('styles/cd-member.css');?>" />
</head>
<body>
<div class="beta-container">
    <div class="beta-header">
        <div class="beta-logo"><a href="<?php echo $this->siteHomeUrl;?>"><?php echo app()->name;?></a></div>
    </div>
    <div class="beta-entry"><?php echo $content;?></div>
</div>
<div class="beta-footer clearfix">
    <div class="beta-container">
        <p>&copy;2012&nbsp;<?php echo app()->name;?>&nbsp;<?php echo param('beian_code');?></p>
    </div>
</div>
</body>
</html>

<?php cs()->registerCoreScript('jquery');?>
<?php cs()->registerScriptFile(tbu('scripts/cd-member.js'), CClientScript::POS_END);?>
