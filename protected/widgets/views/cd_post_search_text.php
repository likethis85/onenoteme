<ul class="clearfix best-text">
    <?php foreach ($models as $model):?>
    <li><?php echo $model->getTitleLink(30);?></li>
    <?php endforeach;?>
    <div class="clear"></div>
</ul>