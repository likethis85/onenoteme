<?php foreach ($models as $model):?>
<ul class="comment-item">
	<li class="user-small-thumbnail"><img src="http://www.qiushibaike.com/system/avabak/150036/thumb/UC_Photo_1.jpg" /></li>
	<li class="user-name"><?php echo $model->commentUserName;?></li>
	<li class="comment-content"><?php echo $model->content;?></li>
	<div class="clear"></div>
</ul>
<?php endforeach;?>
<?php if ($count > $pages->pageSize):?>
	<div class="view-more"><?php echo CHtml::link('点击更多评论', aurl('post/show', array('id'=>$postid), '', 'comment-list'), array('target'=>'_blank'));?></div>
<?php endif;?>
