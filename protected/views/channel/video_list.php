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
    <!-- 56.com榜单 -->
    <div class="cdc-block acenter">
        <iframe frameborder="0" id="customMoudle" name="customMoudle" width="276" height="260" src="http://s1.56img.com/style/i/admin/v4/tpl/gq_share/v1/share_layer_list.html?style=rank_tags&title=&width=276&height=260&border=false&usrId=r239612568"></iframe>
    </div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_02'));?>
	<div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_03'));?>
</div>
<div class="clear"></div>
