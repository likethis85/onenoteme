<?php

define('TAIJIONG_WEIBO_APP_KEY', '553903864');
define('TAIJIONG_WEIBO_APP_SECRET', '0db2f4928def2eaccf3447d576e8e8e7');


class TaijiongController extends AppController
{
    public function actionIndex()
    {
        $this->renderPartial('index');
    }
    
    public function actionWeibo()
    {
        $view = $this->authorized() ? 'weibo_window' : 'weibo_welcome';
        $this->renderPartial($view);
    }

    private function authorized()
    {
        if (!empty($_SESSION['oauth2']['user_id']))
            return true;
        
        $sdk = Yii::getPathOfAlias('application.libs') . DS . 'saesdk.php';
        if(!empty($_REQUEST['signed_request'])){
            require($sdk);
            $oauth = new SaeTOAuthV2(TAIJIONG_WEIBO_APP_KEY, TAIJIONG_WEIBO_APP_SECRET);
            $data = $oauth->parseSignedRequest($_REQUEST['signed_request']);
            if ($data == '-2'){
                die('签名错误!');
            } else {
                $_SESSION['oauth2'] = $data;
                return !empty($_SESSION['oauth2']['user_id']);
            }
        }
        return false;
    }
    
    public function actionPost()
    {
        $content = $_POST['content'];
        $picurl = $_POST['picurl'];
        if (empty($picurl)) return false;
    
        $picurl = str_replace(param('uploadBaseUrl'), '', $picurl);
        $picfile = fbp($picurl);
        echo $picfile;
        $url = 'https://upload.api.weibo.com/2/statuses/upload.json';
        $content = mb_substr($model->content, 0, 130, app()->charset) . '...' . ' @挖段子网';
        $data = array(
            'source' => TAIJIONG_WEIBO_APP_KEY,
            'access_token' => $_SESSION['oauth2']['oauth_token'],
            'status' => $content,
            'pic' => '@' . $picfile,
        );
    
        $curl = new CDCurl();
        $curl->post($url, $data);
        @unlink($picfile);
        $text = '0';
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            if ($result['idstr'])
                $text = $result['idstr'];
        }
        else
            $text = $curl->error();
        
        echo $text;
        exit(0);
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