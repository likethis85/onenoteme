<ul class="post-list">
    <?php foreach ($models as $model):?>
    <li class="post-item">
        <div><?php echo $model->content;?></div>
        <div><?php echo $model->tagsLinks;?></div>
        <ul class="item-toolbar">
        	<li class="upscore fl" pid="<?php echo $model->id;?>"><?php echo $model->up_score;?></li>
        	<li class="downscore fl" pid="<?php echo $model->id;?>"><?php echo $model->down_score;?></li>
        	<li class="comment-nums fr">
        	    <a href="<?php echo aurl('post/show', array('id'=>$model->id), '', 'comment-list');?>" title="查看评论" target="_blank"><?php echo $model->comment_nums;?>条评论</a>
        	</li>
        	<div class="clear"></div>
        </ul>
    </li>
    <?php endforeach;?>
</ul>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>