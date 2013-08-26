<div class="post-line-list">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="panel panel10 post-item post-box">
    	<div class="post-author">
    	    <?php echo $model->getAuthorAvatar();?>
        	<?php echo $model->getAuthorNameLink();?>
    	</div>
        <div class="item-detail">
            <?php if (!$model->isTextType || $model->hasTitle): // 不是笑话并且不是鬼故事或是有单独标题的?>
            <h2 class="item-title"><?php echo $model->getTitleLink(0, '_self');?></h2>
            <?php endif;?>
            
            <?php if (in_array($model->channel_id, array(CHANNEL_FUNNY, CHANNEL_FOCUS)) && $model->getOriginalPic()): // 趣图?>
            <div class="post-image">
                <div class="thumbbox">
                    <?php if ($model->getImageIsLong(MOBILE_POST_LIST_IMAGE_MAX_WIDTH)):?>
                    <a href="<?php echo $model->getMiddlePic();?>" class="size-switcher" title="点击查看详细内容" data-bmiddle-url="<?php echo $model->getMiddlePic();?>">
                        <?php echo CHtml::image($model->getFixThumb(), $model->title, array('class'=>'thumb'));?>
                        <img class="bmiddle hide" alt="<?php echo $model->title;?>" />
                    </a>
                    <?php if ($model->getImageIsAnimation()):?><i class="site-bg icon-gif-sign"></i><?php endif;?>
                    <?php else:?>
                    <a href="<?php echo $model->getUrl();?>" title="点击查看大图" data-bmiddle-url="<?php echo $model->getMiddlePic();?>">
                        <?php echo CHtml::image($model->getMiddlePic(), $model->title, array('class'=>'bmiddle'));?>
                    </a>
                    <?php endif;?>
                    <?php if ($model->getImageIsLong(MOBILE_POST_LIST_IMAGE_MAX_WIDTH)):?>
                    <div class="thumb-pall"></div>
                    <?php endif;?>
                </div>
                <?php if ($model->getImageIsLong(MOBILE_POST_LIST_IMAGE_MAX_WIDTH)):?>
                <div class="thumbnail-more">
                    <div class="lines">
                        <?php for ($i=0; $i<$model->getLineCount(MOBILE_POST_LIST_IMAGE_MAX_WIDTH); $i++):?>
                        <div class="line3"></div>
                        <?php endfor;?>
                        <div class="sjx"></div>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <?php endif;?>
            
            <a href="<?php echo $model->url;?>">
                <div class="item-content"><?php echo $model->filterSummary;?></div>
            </a>
            
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->tagLinks;?></div><?php endif;?>
        <div class="item-toolbar">
            <ul>
            	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="upscore site-bg" data-id="<?php echo $model->id;?>" data-score="1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->up_score;?></a></li>
            	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="downscore site-bg" data-id="<?php echo $model->id;?>" data-score="-1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->downScore;?></a></li>
            	<li class="fright"><a href="<?php echo $model->getCommentUrl();?>" class="site-bg comment" title="查看评论"><?php echo $model->comment_nums > 0 ? $model->comment_nums : '吐槽';?></a></li>
            	<!--
            	<li class="fleft"><a href="<?php echo aurl('post/show', array('id' => $model->id));?>" data-url="<?php echo aurl('comment/list', array('id' => $model->id));?>" class="comment site-bg"><?php echo $model->comment_nums ? $model->comment_nums : '评论';?></a></li>
            	 -->
            	<div class="clear"></div>
            </ul>
        </div>
        <div class="comment-list comment-list-<?php echo $model->id;?> hide"></div>
    </div>
    <div class="site-bg item-shadow"></div>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="panel-rect panel-pages">
    <div class="pagination pagination-large pagination-centered cd-pagination"><?php $this->widget('CDLinkPager', array('pages'=>$pages, 'skin'=>'mobile'));?></div>
</div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$('.post-image').on('click', '.thumbnail-more, .size-switcher', CDMobile.switchImageSize);
	$('.item-toolbar').on('click', 'a.upscore, a.downscore', CDMobile.ratingPost);
	$('.item-toolbar').on('mouseenter', 'a.share, .sharebox', CDMobile.showShareBox);
	$('.item-toolbar').on('mouseleave', 'a.share, .sharebox', CDMobile.hideShareBox);
// 	$('.item-toolbar').on('click', 'a.comment', CDMobile.fetchComments);

	$(document).on('click', '.comment-arrows a', CDMobile.RatingComment);
	$(document).on('click', 'input.submit-comment', CDMobile.PostComment);
    $(document).on('focusin', 'textarea.comment-content', function(event){
    	var tthis = $(this);
    	tthis.addClass('expand');
        if ($.trim(tthis.val()) == tthis.attr('data-placeholder'))
            tthis.val('');
    });
    $(document).on('focusout', 'textarea.comment-content', function(event){
        var tthis = $(this);
        if ($.trim(tthis.val()).length == 0) {
        	tthis.val(tthis.attr('data-placeholder'));
        	tthis.removeClass('expand');
        }
    });
    
});
</script>

<?php
cs()->registerScriptFile(sbu('libs/json2.js'), CClientScript::POS_END);
?>


