<?php
class TestController extends Controller
{
    public function actionIndex()
    {
        
        echo fbu() . '<br />';
        echo fbu('a/b/c.jpg') . '<br />';
        echo fbp('a/c/b.png') . '<hr />';
        
        echo upyunbu() . '<br />';
        echo upyunbu('a/b/c.jpg') . '<br />';
        echo upyunbu(null, false) . '<br />';
        echo upyunbu('a/b/c.jpg', false) . '<hr />';
        
        echo localbu() . '<br />';
        echo localbu('a/b/c.jpg') . '<hr />';
        exit;
        $this->redirect('/');
        
        $url = 'http://pic.pp3.cn/uploads/allimg/111125/16030T308-2.jpg';
        $images = CDUploadFile::saveRemoteImages($url, IMAGE_THUMBNAIL_WIDTH, IMAGE_THUMBNAIL_HEIGHT);
        
        var_dump($images);
        
        exit;
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