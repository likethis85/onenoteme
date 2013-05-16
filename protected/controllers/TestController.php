<?php

class TestController extends Controller
{
    public function init1()
    {
        $this->redirect('/');
        exit(0);
    }
    
    public function actionIndex($page = 1)
    {
        exit;
    }
    
    public function actionImage()
    {
        $uploader = app()->getComponent('upyuntest');
//         $uploader = upyunUploader(true);
        $file = '/data/web/waduanzi.com/uploads/01.jpg';

        $urls = $uploader->autoFilename('.jpg');
        $file = file_get_contents($file);
        $infos = $uploader->save($file);
        var_dump($urls);
        var_dump($infos);

        exit(0);
    }

    public function actionLocal()
    {
        $domains = array(
            'wabao.me',
            'waduanzi.com',
            'waduanzi.cn',
       );
        $url = 'http://f.waduanzi.cn/wp-content/uploads/2013a/04/30/04.jpg';
        
        foreach ($domains as $domain) {
            $pos = stripos($url, $domain);
            var_dump($pos);
//             if ($pos === false)
//                 var_dump($pos);
        }
        
        exit;
        $result = CDBase::externalUrl($url, $domains);
        var_dump($result);
        exit;
        
        echo $_SERVER['HTTP_HOST'];
        exit;
        
        $fetch = new CDFileLocal(uploader(true), 'pics');
        
//         $url = 'http://i2.ieplan.com/wp-content/uploads/2013a/04/30/04.jpg';
//         $row = $fetch->fetchOne($url);
//         var_dump($row);
//         exit;
        
        $html = <<<EOD
        <p>
	<img alt="480×718" onload="AXImg(this)" src="http://i2.ieplan.com/wp-content/uploads/2013a/04/30/01.jpg" title="南宫雪琪蓝色比基尼秀酥胸美腿" width="480" height="718" data-pinit="registered"><br>
	<img alt="480×720" onload="AXImg(this)" src="http://i2.ieplan.com/wp-content/uploads/2013a/04/30/02.jpg" title="南宫雪琪蓝色比基尼秀酥胸美腿" width="480" height="720" data-pinit="registered"><br>
	<img alt="480×718" onload="AXImg(this)" src="http://i2.ieplan.com/wp-content/uploads/2013a/04/30/03.jpg" title="南宫雪琪蓝色比基尼秀酥胸美腿" width="480" height="718" data-pinit="registered"><br>
	<img alt="728×531" onload="AXImg(this)" src="http://i2.ieplan.com/wp-content/uploads/2013a/04/30/04.jpg" title="南宫雪琪蓝色比基尼秀酥胸美腿" data-pinit="registered" width="600" height="437"></p>
EOD;
        
        $rows = $fetch->fetchReplacedHtml($html);
        echo $rows;
        exit;
    }

    public function actionThumb($id)
    {
        $post = Post::model()->findByPk($id);
        $images = $post->getUploadImageSquareThumbs();
        
        var_dump($images);
        exit;
    }
    
    public function actionWater()
    {
        $file = '/data/web/waduanzi.com/uploads/01.jpg';
        $im = new CDImage();
        $im1 = $im->loadFromFile($file);
        
        $font = '/data/web/waduanzi.com/protected/fonts/msyh.ttf';
        $color = array(255, 255, 255);
        $red = array(255, 0, 0);
        $water = new CDWaterMark(CDWaterMark::TYPE_TEXT);
        $water->color($color)
            ->setText('陈东是个傻逼')
            ->position(CDWaterMark::POS_BOTTOM_LEFT)
            ->font($font)
            ->fontsize(50)
            ->apply($im1, 10)
            ->color($red)
            ->setText('傻逼22')
            ->position(CDWaterMark::POS_TOP_RIGHT)
            ->apply($im1, 10);
        
//         $im->output();
        header('content-type:image/jpeg');
        imagejpeg($im1);
    }
    
    public function actionWater1()
    {
        $file = '/data/web/waduanzi.com/uploads/01.jpg';
        $mark = sbp('images/logo_132.jpg');
        $im = new CDImage();
        $im->load($file);
//         $im1 = $im->loadFromFile($file);
        
        $font = '/data/web/waduanzi.com/protected/fonts/msyh.ttf';
        $color = array(255, 255, 255);
        $red = array(255, 0, 0);
        $water = new CDWaterMark(CDWaterMark::TYPE_IMAGE);
        $water->position(CDWaterMark::POS_CENTER_MIDDLE)
            ->setImage($mark)
            ->apply($im, 10, 50)
            ->position(array(50, 50))
            ->apply($im, 10, 50)
            ->type(CDWaterMark::TYPE_TEXT)
            ->color($color)
            ->setText('陈东是个傻逼')
            ->position(CDWaterMark::POS_BOTTOM_LEFT)
            ->font($font)
            ->fontsize(50)
            ->apply($im, 10, 50);

        $im->output();
//         header('content-type:image/jpeg');
//         imagejpeg($im1);
    }
    
    public function actionText()
    {
        $file = '/data/web/waduanzi.com/uploads/01.jpg';
        $im = new CDImage();
        $im->load($file);
        $fontfile = '/data/web/waduanzi.com/protected/fonts/Hiragino_Sans_GB_W6.otf';;
        $fontfile1 = '/data/web/waduanzi.com/protected/fonts/arial.ttf';
        
        $im->textborder('挖段子网', $fontfile, 22, CDImage::MERGE_BOTTOM_LEFT, '#F0F0F0', array(0x33, 0x33, 0x33));
        $im->textborder('waduanzi.com', $fontfile, 12, CDImage::MERGE_TOP_RIGHT, '#F0F0F0', array(0x33, 0x33, 0x33));
//         $im->setCurrentRawData();
        
//         $im->textouter('挖段子网', $fontfile, 24, CDImage::MERGE_BOTTOM_LEFT ,'#FFFFFF', '#333333', 10, 10, 0);
//         $im->textouter('waduanzi.com', $fontfile1, 14, CDImage::MERGE_BOTTOM_RIGHT ,'#FFFFFF', '#333333', 10, 10, 0);
        $im->output();
    }
    
    public function actionText2()
    {
        $file = '/data/web/waduanzi.com/uploads/01.jpg';
        $im = new CDImage();
        $im->load($file);
        $fontfile = '/data/web/waduanzi.com/protected/fonts/Hiragino_Sans_GB_W6.otf';;
        
        $water = new CDWaterMark(CDWaterMark::TYPE_TEXT);
        $water->position(CDWaterMark::POS_BOTTOM_LEFT)
            ->setText('挖段子网')
            ->color('#F0F0F0')
            ->font($fontfile)
            ->fontsize(22)
            ->borderColor('#333333')
            ->applyText($im, 5)
            ->setText('waduanzi.com')
            ->position(CDWaterMark::POS_BOTTOM_RIGHT)
            ->fontsize(12)
            ->applyText($im, 5);
        
        $im->output();
    }
    
    public function actionVerify()
    {
        $user = User::model()->findByPk(1);
        $result = $user->sendVerifyEmail();
        var_dump($result);
    }
    
    public function actionMailer()
    {
        $mailer = app()->getComponent('mailer');
        $mailer->message()
            ->addRecipient('80171597@qq.com')
            ->setReplyTo('89753425@163.com')
            ->setFromName('挖段子网')
            ->setFromAddress('cdcchen@163.com')
            ->setSubject('挖段子网注册确认111444444')
            ->setBody("<strong>SendCloud PHP SDK 测试正4444文111，请参考</strong> <a href='http://sendcloud.sohu.com'>SendCloud</a>");
        var_dump($mailer);
        $mailer->send();
//         var_dump($mailer);
    }
    
    public function actionUrl()
    {
        echo abu('http://a.com');
    }
}




