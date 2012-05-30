<?php foreach ((array)$comments as $model):?>
<div class="content-block comment-item">
    <div class="comment-arrows fleft radius4px">
        <a class="like site-bg arrow-up" data-id="<?php echo $model->id;?>" data-value="1" data-url="<?php echo aurl('comment/score');?>" href="javascript:void(0);">喜欢</a>
        <a class="dislike site-bg arrow-down" data-id="<?php echo $model->id;?>" data-value="0" data-url="<?php echo aurl('comment/score');?>" href="javascript:void(0);">讨厌</a>
    </div>
    <dl class="radius4px">
        <dt>评分:&nbsp;<span class="comment-score"><?php echo $model->score;?></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $model->createTime;?></dt>
        <dd><?php echo $model->filterContent;?></dd>
    </dl>
    <div class="clear"></div>
</div>
<?php endforeach;?>
<?php if ($pages instanceof CPagination && $pages->getPageCount() > 1):?>
<div class="pages" id="page-nav"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'header'=>'', 'footer'=>''));?></div>
<?php endif;?>


