<ul class="clearfix best-image">
    <?php foreach ($models as $model):?>
    <li>
        <?php echo $model->getFixThumbLink($this->linkTarget, 0, $this->trace);?>
        <?php echo $model->getTitleLink(18, $this->linkTarget, $this->trace);?>
    </li>
    <?php endforeach;?>
    <div class="clear"></div>
</ul>