<ul class="clearfix best-image">
    <?php foreach ($models as $model):?>
    <li>
        <?php echo $model->getFixThumbLink();?>
        <?php echo $model->getTitleLink(18);?>
    </li>
    <?php endforeach;?>
    <div class="clear"></div>
</ul>