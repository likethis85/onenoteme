<?php
class WeiboController extends Controller
{
    const APP_KEY = '2981913360';
    const APP_SECRETE = 'f06fd0b530f3d9daa56db67e5e8610e1';
    
    private static $accessToken = '';
    private static $uid = 0;
    
    public function actionAuthorize()
    {
        $callback = aurl('weibo/callback');
        $url = sprintf('https://api.weibo.com/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s', self::APP_KEY, $callback);
        $this->redirect($url);
        exit(0);
    }
    
    public function actionCallback($code)
    {
        $code = strip_tags(trim($code));
        $redirectUrl = aurl('weibo/test');
        $callback = aurl('weibo/callback');
        $url = sprintf('https://api.weibo.com/oauth2/access_token?grant_type=authorization_code&redirect_uri=%s&code=%s', $callback, $code);
        $curl = new CdCurl();
        $curl->basic_auth(self::APP_KEY, self::APP_SECRETE);
        $curl->post($url);
        if ($curl->errno() != 0)
            throw new CHttpException(503, '获取token出错');
        else {
            $data = json_decode($curl->rawdata(), true);
            self::$accessToken = $access_token = $data['access_token'];
            $uid = $data['uid'];
            $profile = self::fetchUserInfo($uid);
            
            $user = self::saveUserProfile($profile);
            if ($user !== false) {
                $identity = new UserIdentity($user->username, $user->password);
                if ($identity->authenticate(true)) {
                    user()->login($identity, param('autoLoginDuration'));
                    $this->redirect(url('site/index'));
                }
            }
            else
                throw new CException('保存用户资料出错');
        }
    }
    
    private static function fetchUserInfo($uid)
    {
        $url = 'https://api.weibo.com/2/users/show.json';
        $data = array('source' => self::APP_KEY, 'access_token' => self::$accessToken, 'uid' => $uid);
        
        $curl = new CdCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $userinfo = json_decode($curl->rawdata(), true);
            return $userinfo;
        }
        else
            throw new CHttpException(503, '获取用户信息出错');
    }
    
    private static function saveUserProfile($profile)
    {
        if (empty($profile)) return false;
        
        $user = new User();
        $user->username = $user->screen_name = $profile['screen_name'];
        $user->password = '123321';
        
        if (!$user->save()) return false;
        
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
        
        if ($userProfile->save()) {
            return $user;
        }
        else
            return false;
    }
}