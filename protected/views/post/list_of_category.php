<div class="fl cd-container">
    <ul class="subnav-links category-links">
        <?php foreach ($categories as $category):?>
    	<li><?php echo $category->postLink;?></li>
        <?php endforeach;?>
    </ul>
    <?php $this->renderPartial('list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fr cd-sidebar">
	<div class="cdc-block">
		<script type="text/javascript">
		<!--
    		google_ad_client = "ca-pub-9725980429199769";
    		/* onenote_300x250 */
            google_ad_slot = "0475885806";
            google_ad_width = 300;
            google_ad_height = 250;
        //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>