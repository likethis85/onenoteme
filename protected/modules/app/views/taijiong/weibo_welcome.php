<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>未授权时的页面</title>
<script src="http://tjs.sjs.sinajs.cn/t35/apps/opent/js/frames/client.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
function pageloaded ()
{
    App.AuthDialog.show({
    	client_id: '<?php echo TAIJIONG_WEIBO_APP_KEY;?>',
    	redirect_uri: '<?php echo aurl('app/taijiong/weibo');?>',
    	height: 120
    });
}
</script>
</head>
<body onload="pageloaded();">
<div class="cdc-container clearfix">
    <!-- <img src="<?php echo sbu('images/jiong_pic.jpg');?>" id="user-pic" /> -->
</div>
</body>
</html>