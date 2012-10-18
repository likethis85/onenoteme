<div class="panel panel15 bottom10px apps-list">
        <a href="http://itunes.apple.com/cn/app//id486268988?mt=8" target="_blank">下载iPhone应用2.2.1版</a>
        <a href="http://s.waduanzi.com/android/waduanzi.apk" target="_blank">下载Android应用1.1.0版</a>
        <a href="http://www.weibo.com/cdcchen" target="_blank">@新浪微博</a>
        <a href="http://t.qq.com/cdcchen" target="_blank">@腾讯微博</a>
        <span>QQ群：49401589</span>
</div>

<div class="fleft cd-container">
<!-- 快速发表段子 start -->
<?php $this->renderPartial('/post/quick_create');?>
<!-- 快速发表段子 end -->

<?php $this->renderPartial('/post/grid_list', array('models' => $models, 'pages' => $pages));?>
</div>
<div class="fright cd-sidebar">
    <div class="cdc-block cd-border cd-qrcode">
        <h4>微信扫描下图订阅挖段子，每日精品段子推送</h4>
        <img src="<?php echo sbu('images/qrcode_wx.jpg');?>" class="weixin-qrcode" alt="挖段子微信二维码"  title="用微信扫描二维码添加挖段子公众号" />
    </div>
    <div class="panel panel10 bottom15px">
        <ul class="social-widget-list">
            <li class="social-widget-item first">
                <a class="clearfix" target="_blank" href="http://e.weibo.com/cdcchen" title="新浪微博">
                    <span class="social-widget-img"> <img src="<?php echo sbu('images/weibo_logo.jpg');?>" alt=""></span>
                    <span class="social-widget-right clearfix">
                        <span class="social-widget-title">新浪微博</span>
                        <span class="social-widget-content">超过 10,000 位朋友在关注 @挖段子网<span class="clear"></span></span>
                    </span>
                </a>
            </li>
            <li class="social-widget-item">
                <a class="clearfix" target="_blank" href="http://t.qq.com/cdcchen" title="腾讯微博">
                    <span class="social-widget-img"> <img src="<?php echo sbu('images/qqt_logo.jpg');?>" alt=""></span>
                    <span class="social-widget-right clearfix">
                        <span class="social-widget-title">腾讯微博</span>
                        <span class="social-widget-content">超过 1500 位朋友在关注 @挖段子网<span class="clear"></span></span>
                    </span>
                </a>
            </li>
            <li class="social-widget-item last">
                <a class="clearfix" target="_blank" href="<?php echo aurl('feed');?>" title="全文 RSS">
                    <span class="social-widget-img"> <img src="<?php echo sbu('images/rss_logo.png');?>" alt=""></span>
                    <span class="social-widget-right clearfix">
                        <span class="social-widget-title">全文 RSS</span>
                        <span class="social-widget-content">超过 20,000 人在订阅挖段子全文 RSS<span class="clear"></span></span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
    <div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <!-- 首页侧边栏广告位1 开始 -->
    <div class="cdc-block cd-border">
        <script type="text/javascript">
        alimama_pid="mm_12551250_2904829_10392377";
        alimama_width=300;
        alimama_height=250;
        </script>
        <script src="http://a.alimama.cn/inf.js" type="text/javascript"></script>
    </div>
    <!-- 首页侧边栏广告位1 结束 -->
</div>
<div class="clear"></div>

<div class="panel panel15 links-list">
    <h3 class="link-title">友情链接&nbsp;&nbsp;[<a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=UjE2MTE6NzwSJDsifCMjfDE9Pw" target="_blank">申请友情链接</a>]</h3>
	<ul class="links">
    	<li><a href="http://www.tao123.com" target="_blank">淘网址</a></li>
		<li><a href="http://lengxiaohua.net/" target="_blank">冷笑话</a></li>
		<li><a href="http://www.xiaohuayoumo.com/" target="_blank">幽默笑话百分百</a></li>
		<li><a href="http://www.yiqixiaoxiao.com/" target="_blank">一起笑笑</a></li>
		<li><a href="http://www.funnyba.com/" target="_blank">有趣吧</a></li>
		<li><a href="http://ipdaohang.com/" target="_blank">iPhone网址导航</a></li>
		<li><a href="http://lian86.com/" target="_blank">☆中国图片链</a></li>
		<li><a href="http://www.jiongnews.com/" target="_blank">囧闻联播</a></li>
		<li><a href="http://bbs.xiaojiulou.com/" target="_blank">笑话视频</a></li>
		<li><a href="http://www.17xla.com/" target="_blank">幽默大全</a></li>
		<li><a href="http://www.jianjiande.com/" title="贱，是一种态度。" target="_blank">贱贱的</a></li>
		<li><a href="http://www.92xiaohua.com/" target="_blank">经典笑话</a></li>
		<li><a href="http://www.maoza.com/" target="_blank">奇闻趣事</a></li>
		<li><a href="http://www.daomei.net.cn/" target="_blank">倒霉网</a></li>
		<li><a href="http://www.lvse.com/site/waduanzi-com-6433.html" target="_blank">绿色网址大全</a></li>
		<li><a href="http://www.benbenla.com" target="_blank">笔记本网址导航</a></li>
		<li><a href="http://www.walxh.com" target="_blank">我爱冷笑话</a></li>
		<li><a href="http://aiguipian.com/" target="_blank">爱鬼片电影网</a></li>
		<li><a href="http://hao.360.cn/" target="_blank">360安全网址导航</a></li>
		<li><a href="http://yasuo.360.cn" target="_blank">360压缩软件</a></li>
		<li><a href="http://www.aifuns.com/" target="_blank">爱娱乐AiFuns</a></li>
		<li><a href="http://www.wadongxi.com/" target="_blank">挖东西</a></li>
		<li><a href="http://www.maoza.com/" target="_blank">奇闻网</a></li>
		<li><a href="http://www.jianjiande.com/" target="_blank">贱贱的</a></li>
		<li><a href="http://www.xcoodir.com/" target="_blank" title="爱酷目录，免费收录各类优秀网站">爱酷目录</a></li>
		<li><a href="http://juetuzhi.net/" target="_blank">掘图志</a></li>
        <li><a href="http://xiaohua.zol.com.cn/" target="_blank">ZOL笑话</a></li>
        <li><a href="http://www.pengfu.com" target="_blank">捧腹网</a></li>
        <li><a href="http://www.jokeji.cn/" target="_blank">笑话集</a></li>
        <li><a href="http://www.kl688.com/" target="_blank">快乐麻花</a></li>
        <li><a href="http://www.chinabug.net/" target="_blank">穿帮网</a></li>
        <li><a href="http://www.laifu.org/" target="_blank">来福岛爆笑娱乐网</a></li>
        <li><a href="http://www.fanjian.net/" target="_blank"> 犯贱志</a></li>
		<div class="clear"></div>
	</ul>
</div>
