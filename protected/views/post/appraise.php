<div class="fl cd-container">
	<h2>我得这个段子。。。</h2>
	<div class="post-content"><?php echo $model->content ? $model->content : '当前没有需要鉴定的段子';?></div>
	<?php if ($model):?>
	<div class="buttons">
    	<a id="refuse-post" pid="<?php echo $model->id;?>" href="javascript:void(0);">不怎么样，不能发表</a>
    	<a id="accept-post" pid="<?php echo $model->id;?>" href="javascript:void(0);">是个好段子，允许发表</a>
    	<a id="skip-post" href="javascript:void(0);" onclick="javascript:window.location.reload();">说不清楚，先跳过去</a>
    	<div class="clear"></div>
	</div>
	<?php endif;?>
</div>

<div class="fr cd-sidebar">
	<div class="content-block">
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
	</div>
</div>

<span id="jqvar" scoreurl="<?php echo aurl('post/vote');?>" class="hide"></span>