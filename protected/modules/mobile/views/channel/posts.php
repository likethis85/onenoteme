<script type="text/javascript">
<!--
_hmt && _hmt.push(['_setCustomVar', 2, 'channel_id', '<?php echo $this->channel;?>', 3]);
//-->
</script>

<?php $this->renderPartial('/post/list', array('models'=>$models, 'pages'=>$pages, 'channel'=>$this->channel));?>
