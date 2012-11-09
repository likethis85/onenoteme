<?php
class WeixinController extends Controller
{
    public function actionIndex()
    {
        $token = 'waduanzi.com';
        $weixin = new WdzWeixin($token);
        echo $weixin->outputText('xx', 'bb', 'sdaf');exit;
        $weixin->run();
    }
}



