<?php
class ApiController extends Controller
{
    public function actionIndex()
    {
        $apiUrl = 'http://onenote.me/api/';
        $api = new AppApi($apiUrl, $_REQUEST);
        
        $api->run();
    }
}