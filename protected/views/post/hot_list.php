<ul class="subnav-links hot-links">
	<li><a href="<?php echo aurl('post/hour');?>">60分钟</a></li>
	<li><a href="<?php echo aurl('post/hour8');?>">8小时</a></li>
	<li><a href="<?php echo aurl('post/day');?>">24小时</a></li>
	<li><a href="<?php echo aurl('post/week');?>">7天</a></li>
	<li><a href="<?php echo aurl('post/month');?>">30天</a></li>
</ul>
<br />
<?php foreach ($models as $model):?>
<li><?php echo $model->content;?></li>
<?php endforeach;?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>