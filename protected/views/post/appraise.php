<h2>我觉得这个段子@#$%^*^%$#</h2>
<div class="fl cd-container">
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
		<h2 class="content-title">审核标准</h2>
		<ul class="site-notice">
			<li>允许通过的帖子：好笑、幽默、看了让人有所思考、经典名句、至理名言。</li>
			<li>不允许通过的帖子：云里雾里，让人看了不知所云、链接广告、老贴重发、低谷、色情、恶心、违法内容、不好笑或不经典、垃圾内容。</li>
		</ul>
	</div>
</div>

<span id="jqvar" scoreurl="<?php echo aurl('post/vote');?>" class="hide"></span>