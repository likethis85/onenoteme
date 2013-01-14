<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>未授权时的页面</title>
<style type="text/css">
body {margin:0; padding:0;}
</style>
<script src="http://tjs.sjs.sinajs.cn/t35/apps/opent/js/frames/client.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
function pageloaded ()
{
    App.AuthDialog.show({
    	client_id: '<?php echo TAIJIONG_WEIBO_APP_KEY;?>',
    	redirect_uri: 'http://apps.weibo.com/taijiong',
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