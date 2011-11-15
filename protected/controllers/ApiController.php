<?php
class ApiController extends Controller
{
    public function actionIndex()
    {
        $apiUrl = 'http://www.waduanzi.com/api';
        $api = new AppApi($apiUrl, $_REQUEST);
        
        $api->run();
    }
}