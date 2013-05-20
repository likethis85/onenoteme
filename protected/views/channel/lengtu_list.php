<script type="text/javascript">
<!--
_hmt && _hmt.push(['_setCustomVar', 2, 'channel_id', <?php echo $this->channel;?>, 3]);
//-->
</script>

<div class="fleft cd-container">
    <?php $this->renderPartial('/post/line_list', array('models'=>$models, 'pages'=>$pages, 'channel'=>$this->channel));?>
</div>
<div class="fright cd-sidebar">
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_01'));?>
    <div class="panel panel10 bottom15px">
        <iframe width="100%" height="250" frameborder="0" scrolling="no" src="http://app.wumii.com/ext/widget/hot?prefix=http%3A%2F%2Fwww.waduanzi.com%2F&num=10&t=1"></iframe>
    </div>
	<div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_02'));?>
    <!-- 最新段子 开始 -->
    <?php $this->widget('CDPostSearch', array('title'=>'最新内涵图', 'channel'=>CHANNEL_FUNNY, 'mediaType'=>MEDIA_TYPE_IMAGE));?>
    <!-- 最新段子 结束 -->
</div>
<div class="clear"></div>
