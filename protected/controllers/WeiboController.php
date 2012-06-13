<?php
class WeiboController extends Controller
{
    const DEFAULT_PASSWORD = '123321';
    private static $_accessToken = '';
    private static $_userID = 0;
    
    public function actionSinat()
    {
        $callback = aurl('weibo/sinacb');
        $url = sprintf('https://api.weibo.com/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s', WEIBO_APP_KEY, $callback);
        $this->redirect($url);
        exit(0);
    }
    
    public function actionSinacb($code)
    {
        $code = strip_tags(trim($code));
        $callback = aurl('weibo/sinacb');
        $url = sprintf('https://api.weibo.com/oauth2/access_token?grant_type=authorization_code&redirect_uri=%s&code=%s', $callback, $code);
        $curl = new CdCurl();
        $curl->basic_auth(WEIBO_APP_KEY, WEIBO_APP_SECRET);
        $curl->post($url);
        if ($curl->errno() != 0)
            throw new CHttpException(503, '获取access_token出错');
        else {
            $data = json_decode($curl->rawdata(), true);
            if (empty($data))
                throw new CException('获取access_token错误');
            
            self::$_accessToken = $access_token = $data['access_token'];
            self::$_userID = $data['uid'];
            $profile = self::fetchWeiboUserInfo(self::$_userID);
            
            $user = self::checkWeiboUserExist(self::$_userID);
            if ($user === null)
                $user = self::saveWeiboUserProfile($profile);
            
            if ($user !== false) {
                $identity = new UserIdentity($user->username, $user->password);
                if ($identity->authenticate(true)) {
                    app()->session['access_token'] = self::$_accessToken;
                    app()->session['sns_userid'] = self::$_userID;
                    user()->login($identity, param('autoLoginDuration'));
                    $this->redirect(url('site/index'));
                }
            }
            else
                throw new CException('保存用户profile出错');
        }
    }
    
    private static function fetchWeiboUserInfo($uid)
    {
        $url = 'https://api.weibo.com/2/users/show.json';
        $data = array('source' => WEIBO_APP_KEY, 'access_token' => self::$_accessToken, 'uid' => $uid);
        
        $curl = new CdCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $userinfo = json_decode($curl->rawdata(), true);
            return $userinfo;
        }
        else
            throw new CHttpException(503, '获取用户信息出错');
    }
    
    private static function checkWeiboUserExist($uid)
    {
        $uid = (int)$uid;
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('weibo_uid' => $uid));
        $profile = UserProfile::model()->find($criteria);
        
        return ($profile === null) ? null : $profile->user;
    }
    

    private static function saveWeiboUserProfile($profile)
    {
        if (empty($profile)) return false;
        
        $user = User::model()->findByAttributes(array('username'=>$profile['screen_name']));
        if ($user === null) {
            $user = new User();
            $user->username = $user->screen_name = $profile['screen_name'];
            $user->password = self::DEFAULT_PASSWORD;
            $user->state = User::STATE_ENABLED;
        
            if (!$user->save()) return false;
        }
    
        $userProfile = UserProfile::model()->findByAttributes(array('user_id' => $user->id));
        if ($userProfile === null) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
            $userProfile->weibo_uid = $profile['id'];
            $userProfile->province = $profile['province'];
            $userProfile->city = $profile['city'];
            $userProfile->location = $profile['location'];
            $userProfile->gender = $profile['gender'];
            $userProfile->description = $profile['description'];
            $userProfile->website = $profile['url'];
            $userProfile->image_url = $profile['profile_image_url'];
            $userProfile->avatar_large = $profile['avatar_large'];
        
            if ($userProfile->save())
                return $user;
            else
                return false;
        }
        else
            return true;
    }
    
    
    
    public function actionQqt()
    {
        $callback = aurl('weibo/qqcb');
        $url = sprintf('https://open.t.qq.com/cgi-bin/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s', QQT_APP_KEY, $callback);
        $this->redirect($url);
        exit(0);
    }
    
    public function actionQqcb($code, $openid)
    {
        $code = strip_tags(trim($code));
        self::$_userID = strip_tags(trim($openid));
        $callback = aurl('weibo/qqcb');
        $url = sprintf('https://open.t.qq.com/cgi-bin/oauth2/access_token?client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s&code=%s', QQT_APP_KEY, QQT_APP_SECRET, $callback, $code);
        $curl = new CdCurl();
        $curl->post($url);
        if ($curl->errno() != 0)
            throw new CHttpException(503, '获取token出错');
        else {
            $returnString = $curl->rawdata();
            if (empty($returnString))
                throw new CException('获取access_token错误');
            
            /*
             * $access_token
             * $expires_in
             * $refresh_token
             */
            parse_str($returnString);
            
            self::$_accessToken = $access_token;
            $profile = self::fetchQqtUserInfo(self::$_userID);
            $user = self::checkQqtUserExist(self::$_userID);
            if ($user === null)
                $user = self::saveQqtUserProfile($profile);
            
            if ($user !== false) {
                $identity = new UserIdentity($user->username, $user->password);
                if ($identity->authenticate(true)) {
                    app()->session['access_token'] = self::$_accessToken;
                    app()->session['sns_userid'] = self::$_userID;
                    user()->login($identity, param('autoLoginDuration'));
                    $this->redirect(url('site/index'));
                }
            }
            else {
                var_dump($user);exit;
                throw new CException('保存用户profile出错');
            }
        }
    }
    
    private static function fetchQqtUserInfo($uid)
    {
        $url = 'https://open.t.qq.com/api/user/info';
        $data = array(
            'oauth_consumer_key' => QQT_APP_KEY,
            'access_token' => self::$_accessToken,
            'openid' => self::$_userID,
            'clientip' => request()->getUserHostAddress(),
            'oauth_version' => '2.a',
            'scope' => 'all',
            'format' => 'json',
        );
        
        $curl = new CdCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $data = json_decode($curl->rawdata(), true);
            if ($data['ret'] == 0)
                return $data['data'];
            else
                throw new CException('获取用户信息错误：' . $data['errcode'] . ', ' . $data['message']);
        }
        else
            throw new CException(503, '获取用户信息出错');
    }
    
    private static function checkQqtUserExist($uid)
    {
        $uid = (int)$uid;
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('qqt_uid' => $uid));
        $profile = UserProfile::model()->find($criteria);
        
        return ($profile === null) ? null : $profile->user;
    }
    


    private static function saveQqtUserProfile($profile)
    {
        if (empty($profile)) return false;
        
        $user = User::model()->findByAttributes(array('username'=>$profile['email']));
        if ($user === null) {
            $user = new User();
            $user->username = $profile['email'];
            $user->screen_name = $profile['name'];
            $user->password = self::DEFAULT_PASSWORD;
            $user->state = User::STATE_ENABLED;
        
            if (!$user->save()) return false;
        }
        
        $userProfile = UserProfile::model()->findByAttributes(array('user_id' => $user->id));
        if ($userProfile === null) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
            $userProfile->qqt_uid = $profile['openid'];
            $userProfile->province = $profile['province_code'];
            $userProfile->city = $profile['city_code'];
            $userProfile->location = $profile['location'];
            $userProfile->gender = $profile['sex'];
            $userProfile->description = $profile['introduction'];
            $userProfile->website = $profile['homepage'];
            $userProfile->image_url = $profile['head'] . '/50';
            $userProfile->avatar_large = $profile['head'] . '/160';
        
            if ($userProfile->save()){
                var_dump($userProfile);exit;}//return $user;
            else
                return false;
        }
        else
            return true;
    }
}


