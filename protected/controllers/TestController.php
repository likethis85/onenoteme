<?php
class TestController extends Controller
{
    public function actionIndex()
    {
        $uploader = app()->getComponent('upyunimg');
        $file = 'http://f0.wabao.me/pics/2013/03/22/bmiddle_20130322195153_514c45d999e23.jpeg';
        $data = file_get_contents($file);
        $extension = CDImage::getImageExtName($data);
        $uploader->autoFilename('pics', $extension, 'bmiddle');
        $result = $uploader->upload($data);
        
        print_r($result);
        echo 'http://ff.waduanzi.com' . $uploader->filename;
        exit;
        
    }
}