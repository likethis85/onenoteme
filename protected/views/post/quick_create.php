<div class="panel panel15 quick-create" id="quick-create" style="display:none;">
<?php echo CHtml::form(aurl('post/quickadd'), 'post');?>
<textarea id="quick-content" tabindex="1" title="段子输入框"></textarea>
<div class="quick-kind">
    <a title="图片" data-type="image" class="site-icon image" href="javascript:void(0);" tabindex="3">图片</a>
    <a title="视频" data-type="video" class="site-icon video" href="javascript:void(0);" tabindex="3">视频</a>
    <a title="音乐" data-type="music" class="site-icon music" href="javascript:void(0);" tabindex="3">音乐</a>
</div>
<div class="quick-btn">
    <a tabindex="2" href="javascript:void(0);" title="发布段子按钮" class="site-bg"></a>
</div>
<div class="clear"></div>
<?php echo CHtml::endForm();?>
</div>