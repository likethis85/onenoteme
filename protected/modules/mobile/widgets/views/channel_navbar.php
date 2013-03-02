<button data-toggle="button" data-expand-text="收起分类" class="show-category-nav btn btn-block btn-success">展开分类</button>
<ul class="nav nav-tabs nav-stacked category-nav hide">
    <li><a href="<?php echo aurl('mobile/channel/joke');?>"><i class="icon-chevron-right"></i>挖笑话</a></li>
    <li><a href="<?php echo aurl('mobile/channel/lengtu');?>"><i class="icon-chevron-right"></i>挖趣图</a></li>
    <li><a href="<?php echo aurl('mobile/channel/girl');?>"><i class="icon-chevron-right"></i>挖女神</a></li>
    <!-- <li><a href="<?php echo aurl('mobile/channel/video');?>"><i class="icon-chevron-right"></i>挖视频</a></li>
    <li><a href="<?php echo aurl('mobile/channel/movie');?>"><i class="icon-chevron-right"></i>挖电影</a></li> -->
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