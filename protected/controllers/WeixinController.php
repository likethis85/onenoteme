<?php
class WeixinController extends Controller
{
    public function actionIndex()
    {
        $token = 'waduanzi';
        $weixin = new WdzWeixin($token);
        $weixin->run();
        exit(0);
    }
}



