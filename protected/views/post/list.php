<div class="post-list">
    <?php foreach ((array)$models as $model):?>
    <div class="post-item">
    	<div class="post-user"><?php echo $model->PostUserName . '&nbsp;' . $model->createTime;?></div>
        <div>
            <a href="<?php echo aurl('post/show', array('id'=>$model->id));?>" target="_blank" title="新窗口中查看段子">: :</a>
            <?php echo $model->content;?>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->tagsLinks;?></div><?php endif;?>
        <ul class="item-toolbar cgray">
        	<li class="upscore fl" pid="<?php echo $model->id;?>"><?php echo $model->up_score;?></li>
        	<li class="downscore fl" pid="<?php echo $model->id;?>"><?php echo $model->down_score;?></li>
        	<li class="comment-nums fr">
        	    <a href="<?php echo aurl('post/show', array('id'=>$model->id), '', 'comment-list');?>" title="查看评论" target="_blank"><?php echo $model->comment_nums;?>条评论</a>
        	</li>
        	<div class="clear"></div>
        </ul>
        <div class="clear"></div>
    </div>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>

<script type="text/javascript">
$(function(){
	$('.post-item').hover(
		function(e){$(this).addClass('post-item-hover');},
		function(e){$(this).removeClass('post-item-hover');}
	);
});
</script>
