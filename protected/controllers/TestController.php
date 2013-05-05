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

    public function actionPpt()
    {
        $PHPPowerPointLib = Yii::getPathOfAlias('application.libs.PHPPowerPoint');
        set_include_path(get_include_path() . PATH_SEPARATOR . $PHPPowerPointLib);
        Yii::registerAutoloader(array($this, 'PHPPowerPointAutoload'), true);
        
        $ppt = new PHPPowerPoint();
        $ppt->getProperties()->setCreator("挖段子网 - http://www.waduanzi.com");
        $ppt->getProperties()->setLastModifiedBy("挖段子网");
        $ppt->getProperties()->setTitle("挖段子网20130505冷笑话精选");
        $ppt->getProperties()->setSubject("挖段子网20130505冷笑话精选");
        $ppt->getProperties()->setDescription("挖段子网每日冷笑话精选，http://www.waduanzi.com");
        $ppt->getProperties()->setKeywords("挖段子网 冷笑话 笑话 挖笑话 笑话精选 幽默搞笑");
        $ppt->getProperties()->setCategory("笑话 幽默 搞笑 内涵 邪恶 重口味");
        
        $currentSlide = $ppt->getActiveSlide();
        
        $shape = $currentSlide->createDrawingShape();
        $shape->setName('挖段子网LOGO');
        $shape->setDescription('挖段子网LOGO');
        $shape->setPath(sbp('images/logo_132.jpg'));
        $shape->setHeight(48);
        $shape->setOffsetX(10);
        $shape->setOffsetY(10);
        //$shape->setRotation(25);
        $shape->getShadow()->setVisible(true);
        $shape->getShadow()->setDirection(45);
        $shape->getShadow()->setDistance(10);
        
        // Create a shape (text)
        echo date('H:i:s') . " Create a shape (rich text)\n";
        $shape = $currentSlide->createRichTextShape();
        $shape->setHeight(720);
        $shape->setWidth(960);
        $shape->setOffsetX(0);
        $shape->setOffsetY(250);
        $shape->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);
        $shape->getAlignment()->setVertical(PHPPowerPoint_Style_Alignment::VERTICAL_CENTER);
        $textRun = $shape->createTextRun("挖段子网冷笑话精选\n\n第20130505期");
        $textRun->getFont()->setBold(true);
        $textRun->getFont()->setSize(50);
        $textRun->getFont()->setColor(new PHPPowerPoint_Style_Color('FFC00000'));
        
        $urlShape = $currentSlide->createRichTextShape();
        $urlShape->setHeight(40);
        $urlShape->setWidth(800);
        $urlShape->setOffsetX(80);
        $urlShape->setOffsetY(680);
        $urlShape->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);
        $urlShape->getAlignment()->setVertical(PHPPowerPoint_Style_Alignment::VERTICAL_CENTER);
        $textRun = $urlShape->createTextRun('挖段子网 - http://www.waduanzi.com');
        $textRun->getFont()->setSize(16);
        $textRun->getFont()->setColor(new PHPPowerPoint_Style_Color(PHPPowerPoint_Style_Color::COLOR_BLACK));
        
        $criteria = new CDbCriteria();
        $criteria->order = 't.create_time asc';
        $criteria->addColumnCondition(array('t.channel_id' => CHANNEL_DUANZI, 't.state' => POST_STATE_ENABLED));
        $start = mktime(0, 0, 0, 5, 5, 2013);
        $end = mktime(0, 0, 0, 5, 6, 2013);
        $criteria->addBetweenCondition('create_time', $start, $end);
        $models = Post::model()->findAll($criteria);
        echo '<hr />' . count($models) . '<br />';
//         exit;
        
        foreach ($models as $model) {
            $newSide = $ppt->createSlide();
            
            $shape = $newSide->createDrawingShape();
            $shape->setName('挖段子网LOGO');
            $shape->setDescription('挖段子网LOGO');
            $shape->setPath(sbp('images/logo_132.jpg'));
            $shape->setHeight(48);
            $shape->setOffsetX(10);
            $shape->setOffsetY(10);
            $shape->getShadow()->setVisible(true);
            $shape->getShadow()->setDirection(45);
            $shape->getShadow()->setDistance(10);
            
            $subject = $newSide->createRichTextShape();
            $subject->setHeight(80);
            $subject->setWidth(800);
            $subject->setOffsetX(80);
            $subject->setOffsetY(30);
            $subject->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);
            $subject->getAlignment()->setVertical(PHPPowerPoint_Style_Alignment::VERTICAL_CENTER);
            $title = strip_tags($model->title);
            $textRun = $subject->createTextRun($title);
            $textRun->getFont()->setBold(true);
            $textRun->getFont()->setSize(26);
            $textRun->getFont()->setColor(new PHPPowerPoint_Style_Color('FFC00000'));
            
            $urlShape = $newSide->createRichTextShape();
            $urlShape->setHeight(40);
            $urlShape->setWidth(800);
            $urlShape->setOffsetX(80);
            $urlShape->setOffsetY(680);
            $urlShape->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);
            $urlShape->getAlignment()->setVertical(PHPPowerPoint_Style_Alignment::VERTICAL_CENTER);
            $textRun = $urlShape->createTextRun('挖段子网 - http://www.waduanzi.com');
            $textRun->getFont()->setSize(16);
            $textRun->getFont()->setColor(new PHPPowerPoint_Style_Color(PHPPowerPoint_Style_Color::COLOR_BLACK));
            
            $shape = $newSide->createRichTextShape();
            $shape->setHeight(500);
            $shape->setWidth(800);
            $shape->setOffsetX(80);
            $shape->setOffsetY(120);
            $shape->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT);
            $shape->getAlignment()->setVertical(PHPPowerPoint_Style_Alignment::VERTICAL_CENTER);
            $content = strip_tags($model->content, '<br>');
            $content = str_replace('<br>', "\n", $content);
            $content = str_replace("\n\n", "\r\n", $content);
            $textRun = $shape->createTextRun($content);
            $textRun->getFont()->setBold(true);
            $textRun->getFont()->setSize(24);
            $textRun->getFont()->setColor(new PHPPowerPoint_Style_Color('FF800000'));
        }
        
        
        // Save PowerPoint 2007 file
        echo date('H:i:s') . " Write to PowerPoint2007 format\n";
        $objWriter = PHPPowerPoint_IOFactory::createWriter($ppt, 'PowerPoint2007');
        $objWriter->save(app()->getRuntimePath() . DS . 'test.pptx');
    }
    
    public function PHPPowerPointAutoload($className)
    {
        require(str_replace('_', DS, $className) . ".php");
    }
}




