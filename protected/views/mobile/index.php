<h2 class="list-title"><?php echo $listTitle;?></h2>
<div class="post-list">
    <?php foreach ((array)$models as $model):?>
    <div class="post-item">
    	<div class="post-user"><?php echo $model->PostUserName . '&nbsp;' . $model->createTime;?></div>
        <div class="post-content">
            <?php echo $model->content;?>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->getTagsLinks('&nbsp;', '_self', 'mobile/tag');?></div><?php endif;?>
        <ul class="item-toolbar cgray" postid="<?php echo $model->id;?>">
        	<li class="upscore fl" pid="<?php echo $model->id;?>"><?php echo $model->up_score;?></li>
        	<li class="downscore fl" pid="<?php echo $model->id;?>"><?php echo $model->down_score;?></li>
        	<li class="comment-nums fr">
        	    <a href="<?php echo aurl('post/show', array('id'=>$model->id), '', 'comment-list');?>" title="新窗口中查看查看评论" target="_blank"><?php echo $model->comment_nums;?>条评论</a>
        	</li>
        	<div class="clear"></div>
        </ul>
        <div class="comment-list hide"></div>
        <div class="clear"></div>
    </div>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>
