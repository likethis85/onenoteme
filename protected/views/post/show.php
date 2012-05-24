<div class="fleft cd-container">
	<div class="panel panel20 post-detail">
		<div class="content-block post-content">
		    <p><?php echo $post->content;?></p>
		    <?php if ($post->tags):?><div class="post-tags">标签：<?php echo $post->tagLinks;?></div><?php endif;?>
        </div>
        <?php if ($post->bmiddle):?><div class="content-block post-picture"><?php echo CHtml::image($post->bmiddle, $post->title);?></div><?php endif;?>
		<div class="content-block arrow fleft">
            <a class="site-bg arrow-up" data-id="<?php echo $post->id;?>" data-value="1" data-url="<?php echo aurl('post/score');?>" href="javascript:void(0);">喜欢</a>
            <a class="site-bg arrow-down" data-id="<?php echo $post->id;?>" data-value="0" data-url="<?php echo aurl('post/score');?>" href="javascript:void(0);">讨厌</a>
            <div class="clear"></div>
        </div>
        <div class="content-block info fleft">
            <span>评分:<?php echo $post->score;?></span>&nbsp;&nbsp;
            <span>浏览:<?php echo (int)$post->view_nums;?></span>&nbsp;&nbsp;
            <span>喜欢:<?php echo (int)$post->up_score;?></span>
        </div>
        <div class="content-block social fright">
            <!-- JiaThis Button BEGIN -->
            <div id="jiathis_style_32x32">
            <a class="jiathis_button_qzone"></a>
            <a class="jiathis_button_tsina"></a>
            <a class="jiathis_button_tqq"></a>
            <a class="jiathis_button_renren"></a>
            <a class="jiathis_button_kaixin001"></a>
            <a href="http://www.jiathis.com/share?uid=1622045" class="jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" target="_blank"></a>
            </div>
            <script type="text/javascript" >
            var jiathis_config={
            	data_track_clickback:true,
            	summary:"",
            	hideMore:true
            }
            </script>
            <script type="text/javascript" src="http://v2.jiathis.com/code/jia.js?uid=1622045" charset="utf-8"></script>
            <!-- JiaThis Button END -->
        </div>
        <div class="clear"></div>
        <div class="content-block social-comments">
            <!-- UY BEGIN -->
            <div id="uyan_frame"></div>
            <script type="text/javascript" id="UYScript" src="http://v1.uyan.cc/js/iframe.js?UYUserId=1622045" async=""></script>
            <!-- UY END -->
        </div>
	</div>
</div>

<div class="fright cd-sidebar">
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>

<script type="text/javascript">
$(function(){
    $('.post-detail').on('click', '.arrow a', Waduanzi.RatingPost);
});
</script>

