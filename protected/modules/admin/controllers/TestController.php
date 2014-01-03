<?php
class TestController extends AdminController
{
    public function init()
    {
//         exit('exit');
    }

    public function actionAliyunOCS()
    {
        $connect = new Memcached;
        $connect->setOption(Memcached::OPT_COMPRESSION, false);
        $connect->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
        $connect->addServer('131ce938744011e3.m.cnhzalicm10pub001.ocs.aliyuncs.com', 11211);
        $connect->setSaslAuthData('131ce938744011e3', 'cdc_wdz_790406');
        $r = $connect->set("hello", "world", time()+30);
        var_dump($r);
        $r = $connect->get("hello");
        var_dump($r);
        $connect->quit();
exit;
        $r = app()->cache1->set('test', 'test', 30);
        var_dump($r);
        var_dump(app()->cache1);

        var_dump(app()->cache1->get('test'));
    }
    
    public function actionVideo()
    {
        $url = 'http://v.youku.com/v_show/id_XNjA5NTQxOTky_ev_1.html';
        $url = 'http://www.56.com/u58/v_OTY5NTYwNzE.html/1030_r239612568.html';
        $vk = new CDVideoKit();
        $vk->setAppKeysMap(p('video_app_keys_map'));
        $vk->setVideoUrl($url);
        echo '<script type="text/javascript" src="http://player.youku.com/jsapi"></script>';
        echo $vk->getDesktopPlayerHTML();
        
        exit;
        echo '<script type="text/javascript" src="http://player.youku.com/jsapi"></script>';
        $vid1 = 'XNjA5NTQxOTky';
        $video1 = new CDVideoKit('1f2d57f9b5ea2ce9');
        $video1->setVideoID($vid1, CDVideoKit::PLATFORM_YOUKU);
        echo $video1->getDesktopPlayerHTML();
        exit(0);
    }
}

