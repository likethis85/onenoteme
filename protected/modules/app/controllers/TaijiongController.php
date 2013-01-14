<?php

defined('ACCESS_KEY') || define('ACCESS_KEY', 'kn215onkjl');
defined('SECRET_KEY') || define('SECRET_KEY', '4zx0ki4kmjzmy2wlh1340zyy5533yxy30kly2k1y');


class TaijiongController extends AppController
{
    public function actionIndex()
    {
        $this->renderPartial('taijiong');
    }
    
    public function actionWeiboWelcome()
    {
        $this->renderPartial('weibo_welcome');
    }
    
    public function actionWeiboWindow()
    {
        $this->renderPartial('weibo_window');
    }
    
    public function actionMakepic()
    {
        $data = array();
        if (strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
            $data['errno'] = -1;
            $data['error'] = '非法请求';
            echo json_encode($data);
            exit(0);
        }
        
        $text1 = strip_tags($_POST['text1']);
        $text2 = strip_tags($_POST['text2']);
        $text3 = strip_tags($_POST['text3']);
        
        if (empty($text1) || empty($text2) || empty($text3)) {
            $data['errno'] = -2;
            $data['error'] = '三句台词都必须填写';
            echo json_encode($data);
            exit(0);
        }
        
        try {
            $picfile = sbp('images/originalpic.jpg');
            $im = new CDImage();
            $im->load($picfile);
            
            $font = Yii::getPathOfAlias('application.fonts') . DS . 'msyhb.ttf';
            $im->text($text1, $font, 16, array(10, 218), array(255, 255, 255));
            $im->text($text2, $font, 16, array(10, 450), array(255, 255, 255));
            $im->text($text3, $font, 16, array(10, 690), array(255, 255, 255));
            
            $path = CDBase::makeUploadPath('taijiong');
            $file = CDBase::makeUploadFileName();
            $filename = $path['path'] . $file;
            $im->saveAsJpeg($filename);
            
            $data['errno'] = 0;
            $data['url'] = fbu($path['url'] . $im->filename());
            echo json_encode($data);
            exit(0);
        }
        catch (Exception $e) {
            $data['errno'] = -3;
            $data['error'] = '图片保存出错';
            echo json_encode($data);
            exit(0);
        }
    }
}