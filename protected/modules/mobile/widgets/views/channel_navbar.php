<button data-toggle="button" data-expand-text="收起分类" class="show-category-nav btn btn-block btn-large btn-success">展开分类</button>
<ul class="nav nav-tabs nav-stacked category-nav hide">
<?php foreach ($channels as $key => $name):?>
    <li><?php echo l('<i class="icon-chevron-right"></i>' . $name, aurl('mobile/channel/posts', array('id'=>$key)));?></li>
<?php endforeach;?>
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