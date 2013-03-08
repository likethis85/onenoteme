<div class="panel panel10">
    <div class="beta-post-detail">
        <div class="beta-title">
            <h1 class="post-title"><?php echo $post->titleLink;?></h1>
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