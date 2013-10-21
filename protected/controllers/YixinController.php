<?php
class YixinController extends Controller
{
    public function actionIndex()
    {
        $token = 'waduanzi';
        $client = new YixinClient($token);
        $client->run();
        exit(0);
    }
}



