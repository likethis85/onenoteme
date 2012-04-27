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
    <div class="admob">
    	<script type="text/javascript">/*wdz_300*250，创建于2012-4-25*/ var cpro_id = 'u866941';</script><script src="http://cpro.baidu.com/cpro/ui/c.js" type="text/javascript"></script>
    </div>
    <?php endif;?>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'header'=>'', 'maxButtonCount'=>6));?></div>
<?php endif;?>

<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>
