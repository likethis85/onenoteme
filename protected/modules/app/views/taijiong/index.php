<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>王宝强《泰囧》超贱表情制作器</title>
<meta name="keywords" content="王宝强超贱表情制作器,超贱表情制作器,王宝强《泰囧》超贱表情,王宝强超贱表情,超贱表情,王宝强神ps,王宝强ps" />
<meta name="description" content="王宝强《泰囧》超贱表情制作器，轻轻松松制作王宝强超贱表情。" />
<link rel="stylesheet" type="text/css" href="<?php echo sbu('libs/bootstrap/css/bootstrap.min.css');?>" />
<script type="text/javascript" src="<?php echo sbu('libs/jquery.min.js');?>"></script>
<style type="text/css">
.clear {clear:both; height:0; line-height:0; font-size:0;}
.space10px {clear:both; height:10px; line-height:0; font-size:0;}
.space15px {clear:both; height:15px; line-height:0; font-size:0;}
.space20px {clear:both; height:20px; line-height:0; font-size:0;}

.cdc-container {margin:15px;}
.cdc-container .pull-right {width:300px;}
.cdc-container .done-pic {text-align:center;}
.cdc-container .done-pic img {width:409px;}
form.taijiong {margin:0 auto;}
form.taijiong textarea {height:50px; width:280px;}
#result-tip {color:red; margin-top:15px;}
.btn-follow {margin-top:15px;}
</style>
<script type="text/javascript">
var picurl = '<?php echo sbu('images/originalpic.jpg');?>';
</script>
<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
<div class="container-fluid cdc-container">
    <div class="pull-right">
        <?php echo CHtml::beginForm(aurl('app/taijiong/makepic'), 'post', array('class'=>'taijiong'));?>
            <fieldset>
                <legend>王宝强《泰囧》超贱表情制作器</legend>
                <label>第&nbsp;1&nbsp;段台词</label>
                <textarea name="text1"></textarea>
                <label>第&nbsp;2&nbsp;段台词</label>
                <textarea class="span5" name="text2"></textarea>
                <label>第&nbsp;3&nbsp;段台词</label>
                <textarea class="span5" name="text3"></textarea>
                <div class="clear"></div>
                <input type="button" class="btn btn-primary btn-block btn-large btn-make" value="贱一把" />
                <div class="alert alert-error" id="result-tip">请输入 3 段台词</div>
                <div class="btn-follow">
                    <wb:follow-button uid="1639121454" type="red_3" width="100%" height="24" ></wb:follow-button>
                    <iframe src="http://follow.v.t.qq.com/index.php?c=follow&a=quick&name=cdcchen&style=4&t=1339123981202&f=1" frameborder="0" scrolling="auto" width="182" height="27" marginwidth="0" marginheight="0" allowtransparency="true"></iframe>
                </div>
            </fieldset>
        <?php echo CHtml::endForm();?>
        <img width="300" src="http://s0.wabao.me/images/qrcode_wx.jpg" alt="挖段子网公众账号二维码" title="微博扫描挖段子网公众账号二维码，快速关注挖段子网" />
    </div>
    <div class="done-pic">
        <h2><a href="/" target="_blank">访问挖段子网</a></h2>
        <img src="<?php echo sbu('images/jiong_pic.jpg');?>" id="user-pic" />
    </div>
</div>

<!-- Baidu Button BEGIN -->
<script type="text/javascript" id="bdshare_js" data="type=slide&amp;img=0&amp;pos=left&amp;uid=541407" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);
</script>
<!-- Baidu Button END -->
<!-- 将此代码放在适当的位置，建议在body结束前 -->
<script id="bdimgshare_shell"></script>
<script>
var bdShare_config_imgshare = {
	"type":"list"
	,"size":"big"
	,"pos":"top"
	,"color":"white"
	,"list":["qzone","tsina","tqq","renren","t163"]
	,"srcSet":{"tsina":"<?php echo WEIBO_APP_KEY;?>","tqq":"<?php echo QQT_APP_KEY;?>"}
	,"uid":"541407"
};
document.getElementById("bdimgshare_shell").src="http://bdimg.share.baidu.com/static/js/imgshare_shell.js?cdnversion=" + Math.ceil(new Date()/3600000);
</script>
<?php echo param('tongji_code');?>
</body>
</html>

<script type="text/javascript">
$(function(){
	$('form.taijiong').on('click', '.btn-make', function(event){
	    var text1 = $.trim($('textarea[name=text1]').val());
	    var text2 = $.trim($('textarea[name=text2]').val());
	    var text3 = $.trim($('textarea[name=text3]').val());
	    if (text1.length == 0 || text2.length == 0 || text3.length == 0) {
	    	$('#result-tip').html('3&nbsp;段台词都必须填写...');
	    	return false;
	    }
		
		var form = $('form.taijiong');
		var url = form.attr('action');
		var data = form.serialize();
		var jqXhr = $.ajax(url, {
			type: 'POST',
			dataType: 'json',
			data: data,
			beforeSend: function(){
				$('#result-tip').html('正在生成超贱图片...');
			}
		});

		jqXhr.done(function(data, textStatus, xhr){
			if (data.errno == 0) {
				$('#user-pic').attr('src', data.url);
				picurl = data.url;
				$('#result-tip').html('超贱图片已经出生啦，赶紧分享给好友吧!');
				document.title = '#王宝强超贱表情#' + text1 + '，' + text2 + '，' + text3 + '#王宝强超贱表情制作器#';
			}
			else
				$('#result-tip').html(data.error);
		});
		
		jqXhr.fail(function(xhr, textStatus, errorThrown){
			$('#result-tip').html(errorThrown);
		});

	});
});
</script>

