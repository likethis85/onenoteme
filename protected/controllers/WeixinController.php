<?php
class WeixinController extends Controller
{
    public function actionIndex($echostr)
    {
        echo $echostr;
        exit(0);
    }
}