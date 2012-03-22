<div class="fl cd-container">
    <ul class="subnav-links hot-links">
    	<!-- <li><a href="<?php echo aurl('post/hour');?>">60分钟</a></li> -->
    	<li><a href="<?php echo aurl('post/hour8');?>">8小时</a></li>
    	<li><a href="<?php echo aurl('post/day');?>">24小时</a></li>
    	<li><a href="<?php echo aurl('post/week');?>">7天</a></li>
    	<li><a href="<?php echo aurl('post/month');?>">30天</a></li>
    	<div class="clear"></div>
    </ul>
    
    <?php $this->renderPartial('list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fr cd-sidebar">
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>