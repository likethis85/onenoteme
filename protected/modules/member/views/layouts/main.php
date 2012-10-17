<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo app()->charset?>" />
    <title><?php echo $this->pageTitle;?></title>
    <meta name="MSSmartTagsPreventParsing" content="true" />
    <meta name="author" content="24beta.com" />
    <meta name="generator" content="<?php echo BetaBase::powered();?>" />
    <meta name="copyright" content="Copyright (c) 2009-2012 24beta.com All Rights Reserved." />
    <link rel="start" href="<?php echo $this->homeUrl;?>" title="Home" />
    <link rel="home" href="<?php echo $this->homeUrl;?>" title="Home" />
    <link rel="stylesheet" type="text/css" href="<?php echo tbu('styles/beta-common.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo tbu('styles/beta-member.css');?>" />
</head>
<body>
<div class="beta-container">
    <div class="beta-header">
        <div class="beta-logo"><a href="<?php echo abu();?>"><?php echo app()->name;?></a></div>
    </div>
    <div class="beta-entry"><?php echo $content;?></div>
</div>
<div class="beta-footer clearfix">
    <div class="beta-container">
        <p><?php echo t('site_announce');?></p>
        <p><?php echo t('site_content_share_announce');?>&nbsp;&copy;2012&nbsp;<?php echo app()->name;?>&nbsp;<?php echo param('beian_code');?></p>
        <p>Powered by <a href="http://www.24beta.com"><?php echo BetaBase::powered();?></a>&nbsp;&nbsp;<a href="http://www.24beta.com/" target="_blank">24beta.com</a></p>
    </div>
</div>
</body>
</html>

<?php cs()->registerCoreScript('jquery');?>
<?php cs()->registerScriptFile(tbu('scripts/beta-member.js'), CClientScript::POS_END);?>
