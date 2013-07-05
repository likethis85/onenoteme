<?php
class WbpostController extends AdminController
{
    public function filters()
    {
        return array(
            'ajaxOnly + weiboVerify, weiboDelete',
            'postOnly + weiboVerify, weiboDelete',
        );
    }
    
    public function actionIndex()
    {
        $pageSize = 20;
        $criteria = new CDbCriteria();
        $criteria->limit = $pageSize;
        $criteria->order = 't.id desc';
        
        $models = PostTemp::model()->findAll($criteria);
        $data = array(
            'models' => $models,
        );
        $this->render('list', $data);
    }
    
    public function actionWeiboVerify($id, $media_type, $callback)
    {
        $id = (int)$id;
        $media_type = (int)$media_type;
        $temp = PostTemp::model()->findByPk($id);
        if ($temp === null)
            $data = 1;
        else {
            try {
                $content = CDBase::convertPunctuation(nl2br(trim($_POST['weibotext'])));
                $content = empty($content) ? $temp->content : $content;
                $post = new Post();
                $post->content = $content;
                $post->channel_id = CHANNEL_FUNNY;
                $post->media_type = $media_type;
                $post->up_score = mt_rand(param('init_up_score_min'), param('init_up_score_max'));
                $post->down_score = mt_rand(param('init_down_score_min'), param('init_down_score_max'));
                $post->view_nums = mt_rand(param('init_view_nums_min'), param('init_view_nums_max'));
                $post->homeshow = CD_YES;
                $post->state = POST_STATE_DISABLED;
                if ($temp->wbaccount)
                    $vestUser = array($temp->wbaccount->user_id, $temp->wbaccount->user_name);
                else
                    $vestUser = CDBase::randomVestAuthor();
            	$post->user_id = $vestUser[0];
            	$post->user_name = $vestUser[1];
            	$post->tags = $vestUser[1];
                
                if ($media_type == MEDIA_TYPE_IMAGE && $temp->original_pic) {
                    $post->original_pic = $temp->original_pic;
                }
                
                $opts['water_position'] = CDWaterMark::POS_BOTTOM_RIGHT;
                $opts['padding_top'] = (int)$_POST['padding_top'];
                $opts['padding_bottom'] = (int)$_POST['padding_bottom'];
                $opts['water_position'] = (int)$_POST['water_position'];
                $referer = 'http://weibo.com';
                $result = $post->fetchRemoteImagesBeforeSave($referer, $opts) && $post->save();
                if ($result) {
                    $temp->delete();
                    self::saveWeiboComments($post->id, $temp->weibo_id);
                }
                $data = (int)$result;
            }
            catch (Exception $e) {
                $data = 0;
                echo $e->getMessage();
            }
        }
        CDBase::jsonp($callback, $data);
    }

    public function actionWeiboDelete($id, $callback)
    {
        $id = (int)$id;
        $result = PostTemp::model()->findByPk($id)->delete();
        CDBase::jsonp($callback, (int)$result);
    }
    
    private static function saveWeiboComments($pid, $wid)
    {
        if (empty($pid) || empty($wid))
            return false;
        
        $data = self::fetchWeiboComments($wid);
        if (empty($data)) return false;
        
        $comments = $data['comments'];
        foreach ((array)$comments as $row) {
            $text = self::filterComment($row['text']);
            if (empty($text)) continue;
            
            self::saveCommentRow($pid, $text);
        }
    }
    
    private static function filterComment($text)
    {
        if (mb_strlen($text) < 3) return false;
        
        $text = str_replace(array('互粉', '转发', '微博', '沙发', '回覆'), '', $text);
        
        $pattern = '/\[.+?\]/is';
        $text = preg_replace($pattern, '', $text);
        $pos = mb_strpos($text, '//', 0, app()->charset);
        if ($pos === 0)
            return false;
        elseif ($pos > 0) {
            $text = mb_substr($text, 0, $pos, app()->charset);
        }
        
        $pos = mb_strpos($text, '@', 0, app()->charset);
        if ($pos === 0)
            return false;
        elseif ($pos > 0) {
            $text = mb_substr($text, 0, $pos, app()->charset);
        }
        
        return trim($text);
    }
    
    private static function saveCommentRow($pid, $text)
    {
        $pid = (int)$pid;
        if (empty($pid) || empty($text)) return false;
        
        try {
            $model = new Comment();
            $model->content = $text;
            $model->post_id = $pid;
            $model->up_score = mt_rand(20, 70);
            $model->down_score = mt_rand(0, 10);
            $model->state = COMMENT_STATE_ENABLED;
            return $model->save();
        }
        catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    private static function fetchWeiboComments($wid)
    {
        $url = 'https://api.weibo.com/2/comments/show.json';
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->session['access_token'],
            'id' => $wid,
            'count' => 100,
        );
        
        $curl = new CdCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $comments = json_decode($curl->rawdata(), true);
            return $comments;
        }
        else {
            echo $curl->error();
            return false;
        }
    }
    
    private static function uploadImage($url)
    {
        return CDUploadedFile::saveImage(upyunEnabled(), $url, 'pics', 'http://www.weibo.com');
    }
}


