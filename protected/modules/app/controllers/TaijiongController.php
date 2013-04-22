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
        $sdk = Yii::getPathOfAlias('application.libs') . DS . 'saesdk.php';
        if(!empty($_REQUEST['signed_request'])){
            require($sdk);
            $oauth = new SaeTOAuthV2(TAIJIONG_WEIBO_APP_KEY, TAIJIONG_WEIBO_APP_SECRET);
            $data = $oauth->parseSignedRequest($_REQUEST['signed_request']);
            if ($data == '-2'){
                die('签名错误!');
            } else {
                app()->session['oauth2'] = $data;
                return !empty($data['user_id']);
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
        $picfile = realpath(fbp($picurl));
        if ($picfile === false) {
            $data['errno'] = -1;
            $data['error'] = '图片不存在';
            echo json_encode($data);
            exit(0);
        }
        
        $url = 'https://upload.api.weibo.com/2/statuses/upload.json';
        $content = mb_substr($content, 0, 120, app()->charset) . '...' . ' @挖段子网#王宝强超贱表情#';
        $params = array(
            'source' => TAIJIONG_WEIBO_APP_KEY,
            'access_token' => app()->session['oauth2']['oauth_token'],
            'status' => $content,
            'pic' => '@' . $picfile,
        );
    
        $curl = new CDCurl();
        $curl->post($url, $params);
        @unlink($picfile);
        
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            if ($result['idstr'])
                $data['msg'] = $result['idstr'];
        }
        else
            $data['error'] = $curl->error();
        
        $data['errno'] = $curl->errno();
        echo json_encode($data);
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
            $color = array(255, 255, 255);
            $im->text($text1, $font, 16, array(10, 218), $color);
            $im->text($text2, $font, 16, array(10, 450), $color);
            $im->text($text3, $font, 16, array(10, 650), $color);
            $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_TOP_RIGHT, $color);
            
            $infos = CDUploadedFile::saveImage(upyunEnabled(), $im->outputRaw(), 'taijiong', '', array(), false);
            $data['errno'] = 0;
            $data['url'] = $infos['url'];
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