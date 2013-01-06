<a class="btn-focus hide" href="javascript:void(0);" data-wxid="<?php echo param('wdz_weixin_account_id');?>">
    <div class="avatar"><img class="wx-avatar" src="<?php echo sbu('images/wdz_wxlogo.png');?>" alt="微信LOGO" /></div>
    <div class="wxname"><?php echo param('wdz_weixin_account_name');?></div>
    <div class="wxid">微信号：<?php echo param('wdz_weixin_account_id');?></div>
    <b>&gt;</b>
</a>
<div class="beta-post-detail">
    <div class="beta-title">
        <h1 class="post-title"><?php echo $post->titleLink;?></h1>
        <span class="comment-number"><?php echo l($post->comment_nums, $post->url, array('title'=>$post->title));?></span>
    </div>
    <p class="post-extra"><?php echo $post->authorName;?>&nbsp;|&nbsp;<?php echo $post->shortTime;?></p>
    <div id="beta-post-content">
        <?php echo $post->filterContent;?>
        <?php if ($post->bmiddlePic) echo image($post->bmiddlePic, $post->title, array('class'=>'bmiddle'));?>
        <?php if ($post->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $post->getTagLinks('mobile/tag/posts', '&nbsp;', '_self');?></div><?php endif;?>
    </div>
    <div class="group-btn acenter weixin-btn hide">
        <a class="btn btn-large btn-inverse" id="add-weixin" href="weixin://profile/<?php echo param('wdz_weixin_account_id');?>">添加微信公号</a>
        <button class="btn btn-large" id="share-friend" data-title="<?php echo $post->title;?>" data-image="<?php echo $post->bmiddlePic;?>" data-desc="<?php echo $post->getSummary(100);?>">分享到朋友圈</button>
    </div>
    <div class="beta-create-form"><?php $this->renderPartial('/comment/_create_form', array('comment'=>$comment));?></div>
    <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'post'=>$post));?>
</div>

<script type="text/javascript">
$(function(){
	//if (typeof(WeixinJSBridge) == 'object')
		$('.btn-focus, .weixin-btn').removeClass('hide');
	
	CDMobile.increaseVisitNums(<?php echo $post->id;?>, '<?php echo aurl('mobile/post/views');?>');
	$('.beta-post-detail').on('click', '#share-friend', CDMobile.shareToWeixinFriend);

	$(document).on('click', '.btn-focus', function(event){
		event.preventDefault();
    	var originalWxid = $(this).attr('data-wxid');
    	if (typeof originalWxid != 'undefined' && originalWxid.length > 0)
    		CDMobile.addContact(originalWxid);
	});
});
</script>

<?php cs()->registerCoreScript('cookie');?>
<?php cs()->registerScriptFile(sbu('libs/json.js'), CClientScript::POS_END);?>