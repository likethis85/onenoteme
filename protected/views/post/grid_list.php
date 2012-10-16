<?php if ($this->channel != CHANNEL_VIDEO):?>
<div class="panel panel10 bottom10px">
    <ul class="fleft hot-keyword">
        <li><span class="cred announce">挖段子全新改版上线！每天大量精彩内容即时同步更新。</span></li>
    </ul>
    <ul class="mode-switch fright">
        <li class="fall"><a href="<?php echo aurl($this->route, array('s'=>POST_LIST_STYLE_WATERFALL));?>">缩略图</a></li>
        <li class="list on"><a href="<?php echo aurl($this->route);?>">列表</a></li>
    </ul>
    <div class="clear"></div>
</div>
<?php endif;?>
<div class="post-list">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="panel panel20 post-item" data-id="<?php echo $model->id;?>">
    	<div class="post-author"><?php echo $model->authorName . '&nbsp;' . $model->createTime;?></div>
        <div class="item-detail">
            <div class="item-content">
                <h2><a href="<?php echo $model->url;?>" target="_blank" title="在新窗口中查看详细内容">∷</a></h2><?php echo $model->content;?>
            </div>
            <?php if (($model->channel_id == CHANNEL_LENGTU || $model->channel_id == CHANNEL_GIRL) && $model->thumbnail):?>
            <div class="post-image">
                <div class="thumbnail">
                <?php if ($model->channel_id == CHANNEL_LENGTU): //只有冷图采用缩略图方式 ?>
                    <?php if ($model->imageIsLong):?>
                    <a href="<?php echo $model->bmiddlePic;?>" class="size-switcher" target="_blank" title="点击查看大图">
                        <?php echo CHtml::image($model->thumbnail, $model->title, array('class'=>'thumb'));?>
                        <img class="original hide" />
                    </a>
                    <?php else:?>
                    <?php echo CHtml::image($model->bmiddlePic, $model->title, array('class'=>'original'));?>
                    <?php endif;?>
                    <?php if ($model->imageIsLong):?>
                    <div class="thumb-pall"></div>
                    <?php endif;?>
                <?php elseif ($model->channel_id == CHANNEL_GIRL): //福利图直接显示 ?>
                    <a href="<?php echo $model->originalPic;?>" target="_blank" title="点击查看大图">
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
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->tagLinks;?></div><?php endif;?>
        <ul class="item-toolbar cgray">
        	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="upscore site-bg" data-score="1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->up_score;?></a></li>
        	<li class="fleft"><a rel="nofollow" href="javascript:void(0);" class="downscore site-bg" data-score="-1" data-url="<?php echo aurl('post/score');?>"><?php echo $model->downScore;?></a></li>
        	<li class="fright"><a rel="nofollow" href="javascript:void(0);" class="share site-bg">分享</a></li>
        	<li class="fright"><a href="<?php echo aurl('post/show', array('id' => $model->id), '', 'comments');?>" class="comment site-bg" target="_blank"><?php echo $model->comment_nums ? $model->comment_nums : '评论';?></a></li>
        	<div class="clear"></div>
        </ul>
        <div class="comment-list comment-list-<?php echo $model->id;?> hide"></div>
    </div>
        <div class="site-bg item-shadow"></div>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="panel panel-pages"><div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div></div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	Waduanzi.initDialog();
	$('.post-image').on('click', '.thumbnail-more, .thumbnail a.size-switcher', Waduanzi.switchImageSize);
	$('.item-toolbar').on('click', 'a.upscore, a.downscore', Waduanzi.postUpDownScore);
});
</script>

<?php
if (user()->getIsGuest()) {
    cs()->registerCoreScript('jquery.ui');
    cs()->registerCssFile(cs()->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');
}
?>


