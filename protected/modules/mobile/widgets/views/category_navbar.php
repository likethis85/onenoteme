<button data-toggle="button" data-expand-text="<?php echo t('close_category_list', 'mobile');?>" class="show-category-nav btn btn-block btn-large btn-success"><?php echo t('expand_category_list', 'mobile');?></button>
<ul class="nav nav-tabs nav-stacked category-nav hide">
<?php foreach ($models as $model):?>
    <li><?php echo l('<i class="icon-chevron-right"></i>' . $model->name, $model->getPostsUrl());?></li>
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