<div class="panel panel10">
    <div class="beta-post-detail">
    	<div class="content-block post-content">
		    <div class="post-author">
        	    <?php echo $post->getAuthorAvatar();?>
            	<?php echo $post->getAuthorNameLink();?>
        	</div>
		    <div class="item-title">
		        <?php if (($post->channel_id != CHANNEL_DUANZI && $post->channel_id != CHANNEL_GHOSTSTORY) || $post->hasTitle):?>
                    <h1><?php echo h($post->title);?></h1>
                <?php endif;?>
            </div>
		    <?php if ($post->tags):?><div class="post-tags">标签：<?php echo $post->tagLinks;?></div><?php endif;?>
        </div>
        <?php if ($post->videoHtml):?>
        <div class="content-block video-player"><?php echo $post->videoHtml;?></div>
        <?php elseif ($post->bmiddlePic):?>
        <div class="content-block post-picture thumbbox">
            <?php echo CHtml::image($post->bmiddlePic, $post->filterTitle . ', ' . $post->getTagText(','));?>
        </div>
        <?php endif;?>
        <div class="item-content"><?php echo $post->filterContent;?></div>
        <div class="line1px"></div>
        <!-- 广告位 开始 -->
        <div class="cdc-block">
            <?php $this->widget('CDAdvert', array('solt'=>'mobile_post_content_bottom'));?>
        </div>
        <!-- 广告位 结束 -->
        <div class="beta-create-form"><?php $this->renderPartial('/comment/_create_form', array('comment'=>$comment));?></div>
        <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'post'=>$post));?>
    </div>
</div>

<script type="text/javascript">
$(function(){
	CDMobile.increaseVisitNums(<?php echo $post->id;?>, '<?php echo aurl('mobile/post/views');?>');
});
</script>

<?php cs()->registerCoreScript('cookie');?>
<?php cs()->registerScriptFile(sbu('libs/json2.js'), CClientScript::POS_END);?>
