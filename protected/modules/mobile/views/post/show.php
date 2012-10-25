<div class="beta-post-detail">
    <div class="beta-title">
        <h1 class="post-title"><?php echo $post->titleLink;?></h1>
        <span class="comment-number"><?php echo l($post->comment_nums, $post->url, array('title'=>$post->title));?></span>
    </div>
    <p class="post-extra"><?php echo $post->authorName;?>&nbsp;|&nbsp;<?php echo $post->shortTime;?></p>
    <div id="beta-post-content"><?php echo $post->filterContent;?></div>
    <div class="beta-post-share clearfix">
        <a id="beta-digg-button" class="beta-digg-button" href="javascript:void(0);" data-url="<?php echo aurl('post/digg');?>" rel="nofollow" title="Like" data-id="<?php echo $post->id;?>">
            <span class="digg-plus">+</span><span class="digg-count"><?php echo $post->digg_nums;?></span>
        </a>
    </div>
    <div class="beta-create-form"><?php $this->renderPartial('/comment/_create_form', array('comment'=>$comment));?></div>
    <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'post'=>$post));?>
</div>

<div class="hide ajax-jsstr">
    <span class="ajax-send"><?php echo t('ajax_send');?></span>
    <span class="ajax-fail"><?php echo t('ajax_fail');?></span>
    <span class="ajax-rules-invalid"><?php echo t('ajax_comment_rules_invalid');?></span>
    <span class="ajax-has-joined"><?php echo t('you_have_joined');?></span>
</div>

<script type="text/javascript">
$(function(){
	<?php if ($post->getContainCode()) echo 'prettyPrint();';?>
	BetaPost.increaseVisitNums(<?php echo $post->id;?>, '<?php echo aurl('post/visit');?>');
	$(document).one('click', '#beta-digg-button', BetaPost.digg);
});
</script>

<?php cs()->registerCoreScript('cookie');?>
<?php cs()->registerScriptFile(sbu('libs/json.js'), CClientScript::POS_END);?>