<?php
class WeixinController extends Controller
{
    public function actionIndex()
    {
        $token = 'waduanzi';
        $client = new WeixinClient($token);
        $client->run();
        exit(0);
    }
}



