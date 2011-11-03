<div class="fl cd-container">
	<div class="post-detail">
		<h1><?php echo $post->title;?></h1>
		
		<div class="content" id="content"><?php echo $post->content;?></div>
		<ul class="item-toolbar">
			<li class="fr"><?php echo $post->comment_nums;?>条评论</li>
        	<li class="upscore fl" pid="<?php echo $post->id;?>"><?php echo $post->up_score;?></li>
        	<li class="downscore fl" pid="<?php echo $post->id;?>"><?php echo $post->down_score;?></li>
        	<li class="fl cgray">分享到：</li>
        	<li class="fl snsshare">
        	<script type="text/javascript">
        		(function(){
        			var _w = 16 , _h = 16;
        			var param = {
        			    url:location.href,
        			    type:'3',
        			    count:'',
        			    appkey:'3658366445',
        			    title: $('#content').html(),
        			    pic:'',
        			    ralateUid:'1639121454',
        			    rnd:new Date().valueOf()
        			}
        			var temp = [];
        			for( var p in param ){
        				temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
        			}
        			document.write('<iframe allowTransparency="true" frameborder="0" scrolling="no" src="http://hits.sinajs.cn/A1/weiboshare.html?' + temp.join('&') + '" width="'+ _w+'" height="'+_h+'"></iframe>')
        		})();
        	</script>
        	</li>
        	<li class="fl snsshare"><a href="javascript:void(0)" onclick="postToWb($('#content').html());return false;" style="height:16px;font-size:12px;line-height:16px;"><img src="http://v.t.qq.com/share/images/s/weiboicon16.png" border="0" alt="" /></a></script></li>
        	<li class="fl snsshare">
        	<script type="text/javascript">
        		(function(){
        			var p = {
    					url:location.href,
    					desc:'哈哈，太搞笑了',
    					summary:$('#content').html(),
    					title:'哈哈，太搞笑了',
    					site:'挖段子',
    					pics:''
    				};
    				var s = [];
    				for(var i in p){
    					s.push(i + '=' + encodeURIComponent(p[i]||''));
    				}
    				document.write(['<a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?',s.join('&'),'" target="_blank" title="分享到QQ空间"><img src="http://qzonestyle.gtimg.cn/ac/qzone_v5/app/app_share/qz_logo.png" alt="分享到QQ空间" /></a>'].join(''));
        		})();
        	</script>
        	</li>
        	<div class="clear"></div>
        </ul>
	</div>
	<div class="comment-list">
		<ul>
	    <?php foreach ($comments as $c):?>
			<li>
				<span><img src="http://www.qiushibaike.com/system/avatars/289248/thumb/20111009173804159.jpg" /></span>
			    <span><?php echo CHtml::link('买根苦瓜吧', '#', array('target'=>'_blank'));?></span>
			    <span class="comment-content"><?php echo $c->content;?></span>
			    <div class="clear"></div>
			</li>
	    <?php endforeach;?>
		</ul>
	</div>
</div>

<div class="fr cd-sidebar">
	<div class="cdc-block">
		<script type="text/javascript">
		<!--
            google_ad_client = "ca-pub-6304134167250488";
			/* onenote_300x250 */
        	google_ad_slot = "4559886257";
            google_ad_width = 300;
            google_ad_height = 250;
        //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>

<span id="jqvar" scoreurl="<?php echo aurl('post/score');?>" class="hide"></span>

<?php cs()->registerScriptFile(sbu('scripts/snsshare.js'), CClientScript::POS_HEAD);?>
