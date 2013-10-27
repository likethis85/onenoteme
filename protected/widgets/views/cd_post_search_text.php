<ul class="clearfix best-text">
    <?php foreach ($models as $model):?>
    <li><?php echo $model->getTitleLink(30, $this->linkTarget, $this->trace);?></li>
    <?php endforeach;?>
    <div class="clear"></div>
</ul>