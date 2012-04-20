<div class="fl cd-container">
    <?php $this->renderPartial('/post/list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fr cd-sidebar">
	<div class="cdc-block">
		<script type="text/javascript" src="http://union.163.com/gs2/union/adjs/6156606/0/1?w=336&h=280"></script>
	</div>
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>