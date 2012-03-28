<div class="post-list">
    <?php foreach ((array)$models as $index => $model):?>
    <div class="post-item">
    	<div class="post-user"><?php echo $model->PostUserName . '&nbsp;' . $model->createTime;?></div>
        <div class="post-content">
            <?php echo $model->content;?>
            <?php if ($model->pic) echo '<br />' . CHtml::image($model->pic, $model->title, array('class'=>'item-pic'));?>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->getTagsLinks('&nbsp;', '_self', 'mobile/tag');?></div><?php endif;?>
    </div>
    <?php if ($index == 1):?>
    <div class="post-item">
        <script type="text/javascript"><!--
            google_ad_client = "ca-pub-9725980429199769";
            /* iphone_320x50 */
            google_ad_slot = "7800978598";
            google_ad_width = 320;
            google_ad_height = 50;
            //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
    </div>
    <?php endif;?>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'header'=>'', 'maxButtonCount'=>6));?></div>
<?php endif;?>

<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>
