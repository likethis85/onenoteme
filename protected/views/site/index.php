<div class="fleft cd-container">
<?php $this->renderPartial('/post/line_list', array('models' => $models, 'pages' => $pages));?>
</div>
<div class="fright cd-sidebar">
    <!-- 首页侧边栏广告位1 开始 -->
    <?php $this->widget('CDAdvert', array('solt'=>'home_sidebar_first'));?>
    <!-- 首页侧边栏广告位1 结束 -->
    <div class="panel panel10 bottom15px">
        <iframe width="270" height="250" frameborder="0" scrolling="no" src="http://app.wumii.com/ext/widget/hot?prefix=http%3A%2F%2Fwww.waduanzi.com%2F&num=10&t=1"></iframe>
    </div>
    <div class="cdc-block cd-border app-list">
        <a href="http://itunes.apple.com/cn/app//id486268988?mt=8" target="_blank" title="挖段子iPhone应用, 最新版本 2.2.2"><img src="<?php echo sbu('images/app_ios.png');?>" alt="挖段子iPhone应用" /></a>
        <a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank" title="挖段子Andoird应用 最新版本 1.1.0"><img src="<?php echo sbu('images/app_android.png');?>" alt="挖段子Andoird应用" /></a>
        <a href="<?php echo CDBaseUrl::mobileHomeUrl();?>" target="_blank" title="挖段子手机版网站"><img src="<?php echo sbu('images/mobile_site.png');?>" alt="挖段子手机版网站" /></a>
    </div>
    <!--
    <div class="cdc-block cd-border cd-qrcode">
        <h4>微信扫描下图订阅挖段子，每日精品段子推送</h4>
        <img src="<?php echo sbu('images/qrcode_wx.jpg');?>" class="weixin-qrcode" alt="挖段子微信二维码"  title="用微信扫描二维码添加挖段子公众号" />
    </div>
    -->
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
                <a class="clearfix" target="_blank" href="http://t.qq.com/waduanzi" title="腾讯微博">
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
    <!-- 首页侧边栏广告位2 开始 -->
    <?php $this->widget('CDAdvert', array('solt'=>'home_sidebar_second'));?>
    <!-- 首页侧边栏广告位2 结束 -->
    <!-- 最新笑话 开始 -->
    <?php $this->widget('CDPostSearch', array('title'=>'最新内涵图', 'channel'=>CHANNEL_FUNNY, 'mediaType'=>MEDIA_TYPE_IMAGE));?>
    <!-- 最新笑话 结束 -->
</div>
<div class="clear"></div>

<?php $this->widget('CDLinks', array('ishome'=>1, 'count'=>40));?>


