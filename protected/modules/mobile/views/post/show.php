<script type="text/javascript" src="http://player.youku.com/jsapi"></script>

<script type="text/javascript">
<!--
_hmt && _hmt.push(['_setCustomVar', 2, 'channel_id', <?php echo (int)$this->channel;?>, 3]);
//-->
</script>
<div class="panel panel10 post-detail">
	<div class="content-block post-content">
	    <div class="post-author">
    	    <?php echo $post->getAuthorAvatar();?>
        	<?php echo $post->getAuthorNameLink();?>
    	</div>
	    <div class="item-title">
	        <?php if (!$post->isTextType || $post->hasTitle):?>
                <h1><?php echo $post->filterTitle;?></h1>
            <?php endif;?>
        </div>
	    <?php if ($post->tags):?><div class="post-tags">标签：<?php echo $post->tagLinks;?></div><?php endif;?>
    </div>
    <div class="item-content">
        <?php if ($post->getIsVideoType() && $post->video):?>
        <div><?php echo $post->video->getMobileVideoHTML(290, 180);?></div>
        <?php endif;?>
        
        <?php echo $post->filterContent;?>
    </div>
    
    <a name="comments"></a>
    
    <!-- 广告位 开始 -->
    <div class="cdc-inner-block"><?php $this->widget('CDAdvert', array('solt'=>'mobile_post_content_bottom'));?></div>
    <!-- 广告位 结束 -->
    
    <div class="change-post clearfix">
        <?php if ($prevUrl):?>
        <a class="site-bg prev" href="<?php echo $prevUrl;?>"></a>
        <?php endif;?>
        <?php if ($nextUrl):?>
        <a class="site-bg next" href="<?php echo $nextUrl;?>"></a>
        <?php endif;?>
    </div>
    
    <div class="line1px"></div>

    <div class="beta-create-form"><?php $this->renderPartial('/comment/_create_form', array('comment'=>$comment));?></div>
    <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'post'=>$post));?>
</div>

<script type="text/javascript">
$(function(){
	var postid = <?php echo $post->id;?>;
	CDMobile.increaseVisitNums(postid, '<?php echo aurl('post/views');?>');
});
</script>

<?php cs()->registerCoreScript('cookie');?>
<?php cs()->registerScriptFile(sbu('libs/json2.js'), CClientScript::POS_END);?>
