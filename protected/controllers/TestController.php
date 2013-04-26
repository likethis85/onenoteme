<?php
class TestController extends Controller
{
    public function init1()
    {
        $this->redirect('/');
        exit(0);
    }
    
    public function actionIndex()
    {
        exit;
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
}