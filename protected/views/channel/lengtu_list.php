<div class="fleft cd-container">
    <?php $this->renderPartial('/post/grid_list', array('models'=>$models, 'pages'=>$pages));?>
</div>
<div class="fright cd-sidebar">
    <div class="cdc-block">
        <script type="text/javascript">
        alimama_pid="mm_12551250_2904829_10392377";
        alimama_width=300;
        alimama_height=250;
        </script>
        <script src="http://a.alimama.cn/inf.js" type="text/javascript"></script>
    </div>
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>

<script type="text/javascript">
$(function(){
	$('.post-image').on('click', '.thumbnail-more, .thumbnail a.size-switcher', function(event){
	    event.preventDefault();
	    var itemDiv = $(this).parents('.post-item');
	    itemDiv.find('.post-image .thumbnail-more').toggle();
	    itemDiv.find('.post-image .thumbnail a .thumb').toggle();
	    itemDiv.find('.post-image .thumb-pall').toggle();
	    var originalUrl = itemDiv.find('.post-image .thumbnail a').attr('href');
	    itemDiv.find('.post-image .thumbnail a .original').attr('src', originalUrl).toggle();
	    var itemPos = itemDiv.position();
	    $('body').scrollTop(itemPos.top);
	});
});
</script>




