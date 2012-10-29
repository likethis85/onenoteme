<button data-toggle="button" data-expand-text="收起分类" class="show-category-nav btn btn-block btn-large btn-success">展开分类</button>
<ul class="nav nav-tabs nav-stacked category-nav hide">
    <li><a href="<?php echo aurl('mobile/channel/duanzi');?>"><i class="icon-chevron-right"></i>挖段子</a></li>
    <li><a href="<?php echo aurl('mobile/channel/lengtu');?>"><i class="icon-chevron-right"></i>挖趣图</a></li>
    <li><a href="<?php echo aurl('mobile/channel/girl');?>"><i class="icon-chevron-right"></i>挖福利</a></li>
    <li><a href="<?php echo aurl('mobile/channel/movie');?>"><i class="icon-chevron-right"></i>挖电影</a></li>
    <li><a href="<?php echo aurl('mobile/channel/focus');?>"><i class="icon-chevron-right"></i>挖热点</a></li>
    <li><a href="<?php echo aurl('mobile/channel/video');?>"><i class="icon-chevron-right"></i>挖视频</a></li>
    <li><a href="<?php echo aurl('mobile/channel/music');?>"><i class="icon-chevron-right"></i>挖音乐</a></li>
</ul>

<script type="text/javascript">
$('.show-category-nav').toggle(function(event){
	$(this).button('expand').button('toggle');
	$(this).next('.category-nav').show();
}, function(){
	$(this).button('reset').button('toggle');
	$(this).next('.category-nav').hide();
});
</script>