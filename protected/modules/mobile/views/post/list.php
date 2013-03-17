<div class="post-line-list">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="panel panel10 post-item post-box">
    	<div class="post-author">
    	    <?php echo $model->getAuthorAvatar();?>
        	<?php echo $model->getAuthorNameLink();?>
    	</div>
        <div class="item-detail">
            <div class="item-title">
                <a href="<?php echo $model->url;?>" title="阅读全文内容">
                <?php echo ($model->channel_id == CHANNEL_DUANZI || $model->channel_id == CHANNEL_GHOSTSTORY) ? $model->filterSummary : $model->title;?>
                </a>
            </div>
            <?php if (($model->channel_id == CHANNEL_LENGTU || $model->channel_id == CHANNEL_GIRL) && $model->thumbnail):?>
            <div class="post-image">
                <div class="thumb">
                <?php if ($model->channel_id == CHANNEL_LENGTU): //只有冷图采用缩略图方式 ?>
                    <?php if ($model->imageIsLong):?>
                    <a href="<?php echo $model->bmiddlePic;?>" class="size-switcher" target="_blank" title="点击查看详细内容">
                        <?php echo CHtml::image($model->thumbnail, $model->title, array('class'=>'thumb'));?>
                        <img class="original hide" alt="<?php echo $model->title;?>" />
                    </a>
                    <?php if ($model->gif_animation):?><i class="site-bg icon-gif-sign"></i><?php endif;?>
                    <?php else:?>
                    <a href="<?php echo $model->url;?>" title="点击查看大图">
                        <?php echo CHtml::image($model->bmiddlePic, $model->title, array('class'=>'original'));?>
                    </a>
                    <?php endif;?>
                    <?php if ($model->imageIsLong):?>
                    <div class="thumb-pall"></div>
                    <?php endif;?>
                <?php elseif ($model->channel_id == CHANNEL_GIRL): //福利图直接显示 ?>
                    <a href="<?php echo $model->url;?>" title="点击查看详细内容">
                        <?php echo CHtml::image($model->bmiddlePic, $model->title, array('class'=>'original'));?>
                    </a>
                <?php endif;?>
                </div>
                <?php if ($model->channel_id == CHANNEL_LENGTU && $model->imageIsLong):?>
                <div class="thumbnail-more">
                    <div class="lines">
                        <?php for ($i=0; $i<$model->lineCount; $i++):?>
                        <div class="line3"></div>
                        <?php endfor;?>
                        <div class="sjx"></div>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <?php elseif ($model->channel_id == CHANNEL_VIDEO && $model->videoHtml):?>
            <div class="content-block video-player"><?php echo $model->videoHtml;?></div>
            <?php endif;?>
            <?php if ($model->channel_id != CHANNEL_DUANZI && $model->channel_id != CHANNEL_GHOSTSTORY):?>
            <div class="item-content"><?php echo $model->filterContent;?></div>
            <?php endif;?>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->tagLinks;?></div><?php endif;?>
        <div class="item-toolbar">
            <ul>
            	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="upscore site-bg" data-id="<?php echo $model->id;?>" data-score="1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->up_score;?></a></li>
            	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="downscore site-bg" data-id="<?php echo $model->id;?>" data-score="-1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->downScore;?></a></li>
            	<li class="fright"><a href="<?php echo $model->url;?>" class="view-detail" title="阅读全文内容">阅读全文</a></li>
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
    <div class="pagination pagination-centered"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'skin'=>'mobile'));?></div>
</div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$('.post-image').on('click', '.thumbnail-more, .thumb a.size-switcher', CDMobile.switchImageSize);
	$('.item-toolbar').on('click', 'a.upscore, a.downscore', CDMobile.ratingPost);
	$('.item-toolbar').on('mouseenter', 'a.share, .sharebox', CDMobile.showShareBox);
	$('.item-toolbar').on('mouseleave', 'a.share, .sharebox', CDMobile.hideShareBox);
	$('.item-toolbar').on('click', 'a.comment', CDMobile.fetchComments);

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


