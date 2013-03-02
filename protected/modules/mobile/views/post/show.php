<a class="btn-focus hide" href="weixin://profile/<?php echo param('wdz_weixin_account_id');?>">
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
        <?php
            echo $post->filterContent;
            if ($post->channel_id != CHANNEL_VIDEO && $post->bmiddlePic)
                echo image($post->bmiddlePic, $post->title, array('class'=>'bmiddle'));
            elseif ($post->channel_id == CHANNEL_VIDEO && $post->videoHtml)
                echo $post->videoHtml;
        ?>
        <?php if ($post->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $post->getTagLinks('mobile/tag/posts', '&nbsp;', '_self');?></div><?php endif;?>
    </div>
    <!-- 广告位 开始 -->
    <div class="cdc-block">
        <?php $this->widget('CDAdvert', array('solt'=>'mobile_post_content_bottom'));?>
    </div>
    <!-- 广告位 结束 -->
    <div class="beta-create-form"><?php $this->renderPartial('/comment/_create_form', array('comment'=>$comment));?></div>
    <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'post'=>$post));?>
</div>

<script type="text/javascript">
$(function(){
	if (typeof(WeixinJSBridge) == 'object')
		$('.btn-focus').removeClass('hide');
	
	CDMobile.increaseVisitNums(<?php echo $post->id;?>, '<?php echo aurl('mobile/post/views');?>');
});
</script>

<?php cs()->registerCoreScript('cookie');?>
<?php cs()->registerScriptFile(sbu('libs/json.js'), CClientScript::POS_END);?>