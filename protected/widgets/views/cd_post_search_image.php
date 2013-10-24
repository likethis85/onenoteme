<ul class="clearfix best-image">
    <?php foreach ($models as $model):?>
    <li>
        <?php echo $model->getRectThumbLink();?>
        <?php echo $model->getTitleLink(20);?>
    </li>
    <?php endforeach;?>
    <div class="clear"></div>
</ul>