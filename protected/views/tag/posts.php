<div class="fl cd-container">
	<h2 class="cd-catption">与<?php echo $tagname;?>相关的段子· · · · · · </h2>
    <?php $this->renderPartial('/post/list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fr cd-sidebar">
	<div class="cdc-block">
		<script type="text/javascript">/*wdz_300*250，创建于2012-4-25*/ var cpro_id = 'u866941';</script><script src="http://cpro.baidu.com/cpro/ui/c.js" type="text/javascript"></script>
	</div>
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>