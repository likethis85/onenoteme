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
body {margin:0; padding:0;}
.clear {clear:both; height:0; line-height:0; font-size:0;}
.space10px {clear:both; height:10px; line-height:0; font-size:0;}
.space15px {clear:both; height:15px; line-height:0; font-size:0;}
.space20px {clear:both; height:20px; line-height:0; font-size:0;}

.cdc-container {width:760px;}
.cdc-container .pull-right {width:300px; margin-right:10px;}
.cdc-container .done-pic {text-align:center;}
.cdc-container .done-pic img {width:410px;}
form.taijiong {margin:0 auto;}
form.taijiong textarea {height:50px; width:280px;}
#result-tip {color:red; margin-top:15px;}
</style>
<script type="text/javascript">
var picurl = '<?php echo sbu('images/originalpic.jpg');?>';
</script>
</head>
<body>
<div class="cdc-container clearfix">
    <div class="pull-right">
        <?php echo CHtml::beginForm(aurl('app/taijiong/makepic'), 'post', array('class'=>'taijiong'));?>
            <fieldset>
                <legend>王宝强《泰囧》超贱表情制作器</legend>
                <label>第&nbsp;1&nbsp;段台词</label>
                <textarea name="text1"></textarea>
                <label>第&nbsp;2&nbsp;段台词</label>
                <textarea name="text2"></textarea>
                <label>第&nbsp;3&nbsp;段台词</label>
                <textarea name="text3"></textarea>
                <div class="clear"></div>
                <input type="button" class="btn btn-primary btn-block btn-large btn-make" value="贱一把" />
                <div class="alert alert-error" id="result-tip">请输入 3 段台词</div>
                <input type="button" class="btn btn-reverse btn-block brn-large hide" value="发布到微博" id="postweibo" />
            </fieldset>
        <?php echo CHtml::endForm();?>
    </div>
    <div class="done-pic pull-left">
        <img src="<?php echo sbu('images/jiong_pic.jpg');?>" id="user-pic" />
    </div>
</div>
<script src="http://tjs.sjs.sinajs.cn/t35/apps/opent/js/frames/client.js" language="JavaScript"></script>
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
				$('#postweibo').hide();
				$('#result-tip').html('正在生成超贱图片...');
			}
		});

		jqXhr.done(function(data, textStatus, xhr){
			if (data.errno == 0) {
				$('#user-pic').attr('src', data.url);
				picurl = data.url;
				$('#result-tip').html('图片已经出生啦，赶紧分享给好友吧!');
				document.title = '#王宝强超贱表情#' + text1 + '，' + text2 + '，' + text3 + '#王宝强超贱表情制作器#';
				$('#postweibo').val('发布到微博').show();
			}
			else
				$('#result-tip').html(data.error);
		});
		
		jqXhr.fail(function(xhr, textStatus, errorThrown){
			$('#result-tip').html(errorThrown);
		});

	});

	$('form.taijiong').on('click', '#postweibo', function(event){
		var tthis = $(this);
		var text1 = $.trim($('textarea[name=text1]').val());
	    var text2 = $.trim($('textarea[name=text2]').val());
	    var text3 = $.trim($('textarea[name=text3]').val());
		var content = text1 + '，' + text2 + '，' + text3;
		var url = '<?php echo aurl('app/taijiong/post');?>';
		console.log(url);
		console.log(content);
		var jqXhr = $.ajax(url, {
			type: 'POST',
			dataType: 'json',
			data: {content:content, picurl:picurl},
			beforeSend: function(){
				tthis.val('正在发布...');
			}
		});
		jqXhr.done(function(data, textStatus, xhr){
			if (data.errno == 0)
    			tthis.val('发布成功！').fadeOut(2000);
			else
				tthis.val('发布出错...');
		});
		jqXhr.fail(function(xhr, textStatus, errorThrown){
			tthis.val('发布出错...');
		});
	});
});
</script>

