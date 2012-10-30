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
    <div class="group-btn">
        <a class="btn btn-large" href="weixin://profile/waduanzi">添加微信号</a>
        <button class="btn btn-large" id="share-friend" data-title="<?php echo $post->title;?>" data-image="<?php echo $post->bmiddlePic;?>" data-desc="<?php echo $post->getSummary(100);?>">分享到微信朋友圈</button>
    </div>
    <div class="beta-create-form"><?php $this->renderPartial('/comment/_create_form', array('comment'=>$comment));?></div>
    <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'post'=>$post));?>
</div>
<a href="<?php echo aurl('mobile/default/info');?>">info</a>
<script type="text/javascript">
$(function(){
	BetaPost.increaseVisitNums(<?php echo $post->id;?>, '<?php echo aurl('mobile/post/views');?>');
	$('.beta-post-detail').on('click', '#share-friend', Beta24.shareToWeixinFriend);
});
</script>

<?php cs()->registerCoreScript('cookie');?>
<?php cs()->registerScriptFile(sbu('libs/json.js'), CClientScript::POS_END);?>