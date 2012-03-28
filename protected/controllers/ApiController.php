<?php
class ApiController extends Controller
{
    public function actionIndex()
    {
        $apiUrl = 'http://www.waduanzi.com/api';
        $api = new AppApi($apiUrl, $_REQUEST);
        
        $api->run();
    }
    
    public function actionJsonp()
    {
        $apiUrl = 'http://waduanzi.com/api/jsonp';
        $api = new AppApi($apiUrl, $_REQUEST);
        
        $api->run();
    }
}