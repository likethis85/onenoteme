<div class="post-list">
    <?php foreach ((array)$models as $index => $model):?>
    <div class="post-item">
    	<div class="post-user"><?php echo $model->authorName . '&nbsp;' . $model->createTime;?></div>
        <div class="post-content">
            <?php echo $model->content;?>
            <?php if ($model->bmiddlePic) echo '<br />' . CHtml::image($model->bmiddlePic, $model->title, array('class'=>'item-pic'));?>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->getTagLinks('wap/tag', '&nbsp;', '_self');?></div><?php endif;?>
    </div>
    <!-- 首页侧边栏广告位1 开始 -->
    <?php if ($index == 1):?>
    <div class="admob">
        <?php $this->widget('CDAdvert', array('solt'=>'mobile_banner'));?>
    </div>
    <?php endif;?>
    <!-- 首页侧边栏广告位1 结束 -->
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'skin'=>'wap'));?></div>
<?php endif;?>

<!-- 首页侧边栏广告位2 开始 -->
<div class="admob">
    <?php $this->widget('CDAdvert', array('solt'=>'mobile_banner'));?>
</div>
<!-- 首页侧边栏广告位2 结束 -->