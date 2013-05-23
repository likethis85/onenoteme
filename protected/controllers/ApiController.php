<?php
class ApiController extends Controller
{
    public function actionIndex()
    {
        Yii::import('application.apiv1.*');
        header('Content-Type: application/json; charset=utf-8');
        AppApi::setDataFormat(AppApi::FORMAT_JSON);
        $api = new AppApi();
        $api->run();
    }
    
    public function actionJson()
    {
        Yii::import('application.apiv1.*');
        header('Content-Type: application/json; charset=utf-8');
        AppApi::setDataFormat(AppApi::FORMAT_JSON);
        $api = new AppApi();
        $api->run();
    }
    
    public function actionJson2()
    {
        Yii::import('application.apiv2.*');
        header('Content-Type: application/json; charset=utf-8');
        AppApi::setDataFormat(AppApi::FORMAT_JSON);
        $api = new AppApi();
        $api->run();
    }
    
    public function actionJson3()
    {
        Yii::import('application.apiv3.*');
        header('Content-Type: application/json; charset=utf-8');
        AppApi::setDataFormat(AppApi::FORMAT_JSON);
        $api = new AppApi();
        $api->debug();
        $api->run();
    }
}