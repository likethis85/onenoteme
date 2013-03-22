<?php
class TestController extends Controller
{
    public function actionIndex()
    {
        $uploader = app()->getComponent('upyunimg');
        $file = 'http://pic.pp3.cn/uploads/allimg/111125/16030T308-2.jpg';
        $data = file_get_contents($file);
        $extension = CDImage::getImageExtName($data);
        $uploader->autoFilename('pics', $extension, 'bmiddle');
        $result = $uploader->upload($data);
        
        print_r($result);
        echo 'http://ff.waduanzi.com' . $uploader->filename;
        exit;
        
    }
}