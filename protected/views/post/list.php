<div class="post-list">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="post-item">
    	<div class="post-user"><?php echo $model->PostUserName . '&nbsp;' . $model->createTime;?></div>
        <div class="item-detail">
            <a class="item-link" href="<?php echo aurl('post/show', array('id'=>$model->id));?>" target="_blank" title="新窗口中查看段子">: :</a>
            <span class="item-content">
                <?php echo $model->content;?>
            </span>
            <?php if ($model->picture):?>
            <div class="post-image">
                <span class="image-more">点击图片查看更多精彩～～～</span>
                <?php echo CHtml::image(sbu('images/grey.gif'), $model->title, array('data-original'=>$model->picture, 'class'=>'item-pic'));?>
            </div>
            <?php endif;?>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->tagsLinks;?></div><?php endif;?>
        <ul class="item-toolbar cgray" postid="<?php echo $model->id;?>">
        	<li class="upscore fl" pid="<?php echo $model->id;?>"><?php echo $model->up_score;?></li>
        	<li class="downscore fl" pid="<?php echo $model->id;?>"><?php echo $model->down_score;?></li>
        	<li class="fr"><span class="sns-share qzone"></span></li>
        	<li class="fr"><span class="sns-share qqt"></span></li>
        	<li class="fr"><span class="sns-share weibo"></span></li>
        	<li class="comment-nums fr">
        	    <a href="javascript:void(0);" url="<?php echo aurl('comment/list', array('pid'=>$model->id));?>" title="查看评论" class="view-comments" pid="<?php echo $model->id;?>"><?php echo $model->comment_nums;?>条评论</a>
        	    <a href="<?php echo aurl('post/show', array('id'=>$model->id), '', 'comment-list');?>" title="新窗口中查看查看评论" target="_blank">: :</a>
        	</li>
        	<div class="clear"></div>
        </ul>
        <div class="comment-list comment-list-<?php echo $model->id;?> hide"></div>
    </div>
    <?php if ($key == 1):?>
    <div class="post-item">
    <script type="text/javascript"><!--
        google_ad_client = "ca-pub-9725980429199769";
        /* wdz_468x60 */
        google_ad_slot = "5967469316";
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    </div>
    <?php endif;?>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>

<script type="text/javascript">
$(function(){
	$('.post-item').hover(
		function(e){$(this).addClass('post-item-hover');},
		function(e){$(this).removeClass('post-item-hover');}
	);

	$('.item-pic').toggle(
		function(e) {
			var offset = $(this).parent().offset();
		    $('body').scrollTop(offset.top);
	        $(this).parent().css('max-height', '9999px');
	    },
	    function(e) {
		    var offset = $(this).parents('.post-item').offset();
		    $('body').scrollTop(offset.top);
	        $(this).parent().css('max-height', '300px');
    	}
	);

	$('.post-item .post-image img').lazyload({effect: 'fadeIn', threshold: 200});
});
</script>

<?php cs()->registerScriptFile(sbu('libs/jquery.lazyload.min.js'), CClientScript::POS_END);?>
<?php cs()->registerScriptFile(sbu('scripts/snsshare.js'), CClientScript::POS_END);?>

