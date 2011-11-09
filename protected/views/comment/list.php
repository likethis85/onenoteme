<div class="comment-list">
    <?php foreach ($models as $c):?>
    <ul class="comment-item">
    	<li class="user-small-thumbnail"><img src="http://www.qiushibaike.com/system/avatars/289248/thumb/20111009173804159.jpg" /></li>
        <li class="user-name"><?php echo CHtml::link($c->commentUserName, '#', array('target'=>'_blank'));?></li>
        <li class="comment-content"><?php echo $c->content;?></li>
        <div class="clear"></div>
    </ul>
    <?php endforeach;?>
    <div class="pages"><?php $this->widget('CLinkPager', array('pages' => $pages));?></div>
</div>
