<div class="panel panel15 links-list">
    <h3 class="link-title"><?php echo $this->title;?>&nbsp;&nbsp;[<a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=UjE2MTE6NzwSJDsifCMjfDE9Pw" target="_blank">申请友情链接</a>]</h3>
	<ul class="links">
	    <?php foreach($models as $model):?>
	    <li><?php echo $model->getNameLink();?></li>
	    <?php endforeach;?>
		<div class="clear"></div>
	</ul>
</div>