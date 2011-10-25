<div class="fl cd-container">
	<div class="post-detail">
		<h1><?php echo $post->title;?></h1>
		<div class="content"><?php echo $post->content;?></div>
		<ul class="item-toolbar">
			<li class="fr"><?php echo $post->comment_nums;?>条评论</li>
        	<li class="upscore fl" pid="<?php echo $post->id;?>"><?php echo $post->up_score;?></li>
        	<li class="downscore fl" pid="<?php echo $post->id;?>"><?php echo $post->down_score;?></li>
        	<div class="clear"></div>
        </ul>
	</div>
	<div class="comment-list">
		<ul>
	    <?php foreach ($comments as $c):?>
			<li>
				<span><img src="http://www.qiushibaike.com/system/avatars/289248/thumb/20111009173804159.jpg" /></span>
			    <span><?php echo CHtml::link('买根苦瓜吧', '#', array('target'=>'_blank'));?></span>
			    <span class="comment-content"><?php echo $c->content;?></span>
			    <div class="clear"></div>
			</li>
	    <?php endforeach;?>
		</ul>
	</div>
</div>

<div class="fr cd-sidebar">
	<div class="cdc-block">
		<script type="text/javascript">
		<!--
            google_ad_client = "ca-pub-6304134167250488";
            /* meiapps_300x250_image_text */
            google_ad_slot = "7220156016";
            google_ad_width = 300;
            google_ad_height = 250;
        //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
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


<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>