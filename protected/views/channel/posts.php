<div class="fl cd-container">
    <?php $this->renderPartial('/post/list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fr cd-sidebar">
	<div class="cdc-block">
		<script type="text/javascript"><!--
            google_ad_client = "ca-pub-0852804202998726";
            /* waduanzi_300x250 */
            google_ad_slot = "7715744702";
            google_ad_width = 300;
            google_ad_height = 250;
            //-->
            </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
	<div class="cdc-block">
		<script type="text/javascript" src="http://union.163.com/gs2/union/adjs/6156606/0/1?w=336&h=280"></script>
	</div>
</div>
<div class="clear"></div>