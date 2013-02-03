<div class="fleft cd-container">
    <?php $this->renderPartial('/post/line_list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fright cd-sidebar">
    <div class="cdc-block">
        <!--cnzz tui-->
        <script  type="text/javascript" charset="utf-8"  src="http://tui.cnzz.net/cs.php?id=1000021168"></script>
        <!--cnzz tui-->
    </div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_01'));?>
	<div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_02'));?>
</div>
<div class="clear"></div>
