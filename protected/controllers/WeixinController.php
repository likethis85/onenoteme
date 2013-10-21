<?php
class WeixinController extends Controller
{
    public function actionIndex()
    {
        $token = 'waduanzi';
        $weixin = new WeixinClient($token);
        $weixin->run();
        exit(0);
    }
}



