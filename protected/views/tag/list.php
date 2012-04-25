<div class="fl cd-container">
	<h2 class="cd-catption">热门标签· · · · · · </h2>
	<div class="tag-list">
	<?php foreach($tags as $key => $tag):?>
        <?php echo CHtml::link($tag->name, $tag->getUrl(), array('target'=>$target, 'rel'=>'tag', 'class'=>$levels[$key]));?>
    <?php endforeach;?>
    </div>
</div>

<div class="fr cd-sidebar">
    <div class="cdc-block">
		<script type="text/javascript">/*wdz_300*250，创建于2012-4-25*/ var cpro_id = 'u866941';</script><script src="http://cpro.baidu.com/cpro/ui/c.js" type="text/javascript"></script>
	</div>
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>