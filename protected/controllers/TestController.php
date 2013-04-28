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
        $count = 2000;
        $page = (int)$page;
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $count;
        $cmd = app()->getDb()->createCommand()
            ->from(TABLE_POST)
            ->select(array('id', 'original_pic', 'create_time', 'user_id', 'create_ip', 'title', 'original_width', 'original_height', 'original_frames'))
            ->where('channel_id = :channelID', array(':channelID'=>CHANNEL_LENGTU))
            ->order(array('id asc'))
            ->limit($count)
            ->offset($offset);
        
        $rows = $cmd->queryAll();
        
        foreach ($rows as $row) {
            $upload = new Upload();
            $upload->post_id = $row['id'];
            $upload->file_type = Upload::TYPE_IMAGE;
            $upload->url = $row['original_pic'];
            $upload->create_time = $row['create_time'];
            $upload->create_ip = $row['create_ip'];
            $upload->width = $row['original_width'];
            $upload->height = $row['original_height'];
            $upload->frames = $row['original_frames'];
            $upload->desc = $row['title'];
            $upload->user_id = $row['user_id'];
            
            $result = $upload->save();
            if ($result)
                echo $row['id'] . ' OK<br />';
            else {
                echo $row['id'] . ' ERROR<br />';
                break;
            }
        }
        echo count($rows);
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