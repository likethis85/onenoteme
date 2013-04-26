<?php if ($this->channel != CHANNEL_VIDEO):?>
<div class="panel panel10 bottom10px">
    <ul class="fleft hot-keyword">
        <li><span class="cred announce">24小时更新：<?php echo Post::todayUpdateCount();?>篇。&nbsp;&nbsp;&nbsp;QQ群：49401589</span></li>
        <li><a href="<?php echo aurl('app/taijiong');?>" target="_blank">王宝强超贱表情制作器</a></li>
    </ul>
    <ul class="mode-switch fright">
        <li class="fall"><a href="<?php echo CDBaseUrl::fallStyleUrl($this);?>">瀑布流</a></li>
        <li class="grid"><a href="<?php echo CDBaseUrl::gridStyleUrl($this);?>">表格</a></li>
        <li class="list on"><a href="<?php echo CDBaseUrl::lineStyleUrl($this);?>">列表</a></li>
    </ul>
    <div class="clear"></div>
</div>
<?php endif;?>
<div class="post-line-list">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="panel panel15 post-item post-box">
    	<div class="post-author">
    	    <?php echo $model->getAuthorAvatar();?>
        	<?php echo $model->getAuthorNameLink();?>
    	</div>
        <div class="item-detail">
            <?php if (($model->channel_id != CHANNEL_DUANZI && $model->channel_id != CHANNEL_GHOSTSTORY) || $model->hasTitle): // 不是笑话并且不是鬼故事或是有单独标题的?>
            <h2 class="item-title"><?php echo $model->titleLink;?></h2>
            <?php endif;?>
            
            <?php if ($model->channel_id == CHANNEL_LENGTU && $model->thumbnail): // 趣图?>
            <div class="post-image">
                <div class="thumbbox">
                    <?php if ($model->getImageIsLong(150)):?>
                    <a href="<?php echo $model->getMiddlePic();?>" class="size-switcher" target="_blank" data-bmiddle-url="<?php echo $model->getMiddlePic();?>">
                        <?php echo CHtml::image($model->getFixThumb(), $model->title, array('class'=>'thumb', 'title'=>'点击析大图'));?>
                        <img class="bmiddle hide" alt="<?php echo $model->title;?>" title="点击查看缩略图" />
                    </a>
                    <?php if ($model->getImageIsAnimation()):?><i class="site-bg icon-gif-sign"></i><?php endif;?>
                    <?php else:?>
                    <a href="<?php echo $model->getUrl();?>" target="_blank" title="点击查看大图" data-bmiddle-url="<?php echo $model->getMiddlePic();?>">
                        <?php echo $model->getMiddleImage();?>
                    </a>
                    <?php endif;?>
                    <?php if ($model->getImageIsLong(150)):?>
                    <div class="thumb-pall"></div>
                    <?php endif;?>
                </div>
                <?php if ($model->getImageIsLong(150)):?>
                <div class="thumbnail-more">
                    <div class="lines">
                        <?php for ($i=0; $i<$model->getLineCount(150); $i++):?>
                        <div class="line3"></div>
                        <?php endfor;?>
                        <div class="sjx"></div>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <?php elseif ($model->channel_id == CHANNEL_GIRL && $model->getMiddlePic()): // 女神?>
            <div class="post-image">
                <?php if ($model->uploadImages):?>
                <ul class="thumb-list">
                    <?php foreach ($model->getUploadImageSquareThumbs() as $image):?>
                    <li><?php echo $image;?></li>
                    <?php endforeach;?>
                    <div class="clear"></div>
                </ul>
                <?php else:?>
                <div class="thumbbox acenter">
                    <a href="<?php echo $model->getUrl();?>" target="_blank" title="<?php echo $model->title;?>" data-bmiddle-url="<?php echo $model->getMiddlePic();?>">
                        <?php echo $model->getMiddleImage();?>
                    </a>
                </div>
                <?php endif;?>
            </div>
            <?php elseif ($model->channel_id == CHANNEL_VIDEO && $model->videoHtml): // 视频?>
            <div class="content-block video-player"><?php echo $model->videoHtml;?></div>
            <?php endif;?>
            
            <div class="item-content"><?php echo $model->filterSummary;?></div>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->tagLinks;?></div><?php endif;?>
        <div class="item-toolbar">
            <ul>
            	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="upscore site-bg" data-id="<?php echo $model->id;?>" data-score="1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->up_score;?></a></li>
            	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="downscore site-bg" data-id="<?php echo $model->id;?>" data-score="-1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->downScore;?></a></li>
            	<li class="fright"><a rel="nofollow" href="javascript:void(0);" class="share site-bg">分享</a></li>
            	<li class="fright"><a href="<?php echo aurl('post/show', array('id' => $model->id));?>" data-url="<?php echo aurl('comment/list', array('id' => $model->id));?>" class="comment site-bg"><?php echo $model->comment_nums ? (int)$model->comment_nums : '评论';?></a></li>
            	<li class="fright"><a rel="nofollow" href="javascript:void(0);" class="favorite site-bg" data-id="<?php echo $model->id;?>" data-url="<?php echo $model->likeUrl;?>"><?php echo $model->favorite_count ? (int)$model->favorite_count : '收藏';?></a></li>
            	<div class="clear"></div>
            </ul>
            <div class="sharebox">
                <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare" data="">
                    <a class="bds_qzone">QQ空间</a>
                    <a class="bds_tsina">新浪微博</a>
                    <a class="bds_tqq">腾讯微博</a>
                    <a class="bds_renren">人人网</a>
                    <div class="arrow"></div>
                </div>
            </div>
        </div>
        <div class="comment-list comment-list-<?php echo $model->id;?> hide"></div>
    </div>
    <div class="site-bg item-shadow"></div>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="panel panel-pages"><div class="cd-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div></div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$('.post-image').on('click', '.thumbnail-more, .size-switcher', Waduanzi.switchImageSize);
	$('.item-toolbar').on('click', 'a.upscore, a.downscore', Waduanzi.ratingPost);
	$('.item-toolbar').on('mouseenter', 'a.share, .sharebox', Waduanzi.showShareBox);
	$('.item-toolbar').on('mouseleave', 'a.share, .sharebox', Waduanzi.hideShareBox);
	$('.item-toolbar').on('click', 'a.favorite', Waduanzi.favoritePost);
	$('.item-toolbar').on('click', 'a.comment', Waduanzi.fetchComments);

	$(document).on('click', '.comment-arrows a', Waduanzi.RatingComment);
	$(document).on('click', 'input.submit-comment', Waduanzi.PostComment);
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


