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
        $api->run();
    }
    
    public function actionJsonp3()
    {
        Yii::import('application.apiv3.*');
        header('Content-Type: application/javascript; charset=utf-8');
        AppApi::setDataFormat(AppApi::FORMAT_JSONP);
        $api = new AppApi();
        $api->run();
    }
    
    public function actionXml3()
    {
        Yii::import('application.apiv3.*');
        header('Content-Type: application/xml; charset=utf-8');
        AppApi::setDataFormat(AppApi::FORMAT_XML);
        $api = new AppApi();
        $api->run();
    }
}