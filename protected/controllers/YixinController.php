<?php
class YixinController extends Controller
{
    public function actionIndex()
    {
        $token = 'waduanzi';
        $yixin = new YixinClient($token);
        $yixin->run();
        exit(0);
    }
}



