<?php
class FeedController extends Controller
{
    const POST_COUNT = 500;
    
    public function init()
    {
        parent::init();
        header('Content-Type:application/xml; charset=' . app()->charset);
        
//         self::log();
    }
    
    public function filters()
    {
        $duration = 600;
        return array(
            array(
                'COutputCache + index, joke, lengtu, video, funny, focus',
                'duration' => $duration,
                'varyByParam' => array('source'),
            ),
            array(
                'COutputCache + channel',
                'duration' => $duration,
                'varyByParam' => array('cid'),
            ),
        );
    }
    
    public function actionIndex($source = 'feed')
    {
        $channels = array(CHANNEL_FUNNY, CHANNEL_FOCUS);
        $mediaTypes = array(MEDIA_TYPE_TEXT, MEDIA_TYPE_IMAGE);
        echo self::channelPosts($channels, $mediaTypes, app()->name, $source, 600);
    }
    
    
    public function actionFunny($source = 'feed')
    {
        $feedname = app()->name . ' » 冷笑话';
        echo self::channelPosts(CHANNEL_FUNNY, array(MEDIA_TYPE_TEXT, MEDIA_TYPE_IMAGE), $feedname, $source, 600);
    }
    
    public function actionJoke($source = 'feed')
    {
        $feedname = app()->name . ' » 挖笑话';
        echo self::channelPosts(CHANNEL_FUNNY, MEDIA_TYPE_TEXT, '挖笑话', $source, 600);
    }
    
    public function actionLengtu($source = 'feed')
    {
        $feedname = app()->name . ' » 挖趣图';
        echo self::channelPosts(CHANNEL_FUNNY, MEDIA_TYPE_IMAGE, $feedname, $source, 600);
    }
    
    public function actionFocus($source = 'feed')
    {
        $feedname = app()->name . ' » 挖热点';
        echo self::channelPosts(CHANNEL_FOCUS, null, $feedname, $source, 600);
    }
    
    private static function channelPosts($channelID, $mediatype, $feedname, $source, $expire = 600)
    {
        $source = trim(strip_tags(strtolower($source)));
        if (!self::checkSource($source)) $source = 'feed';
        
        $cacheData = self::cacheData($channelID, $mediatype, $source);
        if ($cacheData !== false) return $cacheData;
        
        $criteria = new CDbCriteria();
        
        if (is_numeric($channelID)) {
            $channelID = (int)$channelID;
            $criteria->addColumnCondition(array('channel_id'=>$channelID));
        }
        elseif (is_array($channelID)) {
            $channelID = array_map('intval', $channelID);
            $criteria->addInCondition('channel_id', $channelID);
        }
        
        if (is_numeric($mediatype)) {
            $mediatype = (int)$mediatype;
            $criteria->addColumnCondition(array('media_type'=>$mediatype));
        }
        elseif (is_array($mediatype)) {
            $mediatype = array_map('intval', $mediatype);
            $criteria->addInCondition('media_type', $mediatype);
        }
        
        $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
        $models = self::fetchPosts($criteria);
        
        //@todo 临时给ucweb做的兼容，提一条图片段子加在文字笑话最前面
        if ($source == 'ucweb' && $mediatype == MEDIA_TYPE_TEXT && $channelID == CHANNEL_FUNNY) {
            $lengtu = self::fetchLatestLengtuRow();
            array_unshift($models, $lengtu);
        }

        //@todo 把图片url地址都替换成商务厅服务器地址
        if ($source == 'ucweb' || $source == 'qqmb')
            $models = self::replacePicUrlWithPosts($models);
        
        $xml = self::outputXml($feedname, $models, $source);
        self::cacheData($channelID, $mediatype, $source, $xml, $expire);
        return $xml;
        exit(0);
    }
    
    private static function cacheData($channelID, $mediaType, $source, $data = false, $expire = 600)
    {
        if (cache() === null) return false;
        
        if (is_array($channelID)) {
            sort($channelID, SORT_NUMERIC);
            $channelID = join('_', $channelID);
        }
        else
            $channelID = (int)$channelID;
        
        if (is_array($mediaType)) {
            sort($mediaType, SORT_NUMERIC);
            $mediaType = join('_', $mediaType);
        }
        elseif (is_numeric($mediaType))
            $mediaType = (int)$mediaType;
        else
            $mediaType = '';
            
        $cacheID = sprintf('feed_cache_%s_%s_%s', $channelID, $mediaType, $source);
        
        if ($data === false)
            $result = cache()->get($cacheID);
        else
            $result = cache()->set($cacheID, $data, $expire);
            
        return $result;
    }
    
    private static function fetchPosts(CDbCriteria $criteria)
    {
        $criteria->select = array('t.id', 't.channel_id', 't.title', 't.original_pic', 't.content', 't.create_time', 't.original_frames');
        $criteria->order = 't.create_time desc, t.id desc';
        $criteria->limit = self::POST_COUNT;
            
        $models = Post::model()->findAll($criteria);
        return $models;
    }
    
    private static function fetchLatestLengtuRow()
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('t.id', 't.channel_id', 't.title', 't.original_pic', 't.content', 't.create_time', 't.original_frames');
        $criteria->addColumnCondition(array('t.state'=> POST_STATE_ENABLED,'t.channel_id'=>CHANNEL_FUNNY, 't.media_type' => MEDIA_TYPE_IMAGE));
        $criteria->order = 't.create_time desc, t.id desc';
        $model = Post::model()->find($criteria);
        
        return $model;
    }

    private static function outputXml($feedname, array $models, $source)
    {
        $namespaceURI = 'http://www.w3.org/2000/xmlns/';
        $ns_rdf = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
        $ns_sy = 'http://purl.org/rss/1.0/modules/syndication/';
        $ns_dc = 'http://purl.org/dc/elements/1.1/';
        $ns_slash = 'http://purl.org/rss/1.0/modules/slash/';
    
        $dom = new DOMDocument('1.0', app()->charset);
        $rss = $dom->createElement('rss');
        $dom->appendChild($rss);
        $rss->setAttribute('version', '2.0');
        $rss->setAttributeNS($namespaceURI ,'xmlns:wfw', 'http://wellformedweb.org/CommentAPI/');
        $rss->setAttributeNS($namespaceURI ,'xmlns:dc', $ns_dc);
        $rss->setAttributeNS($namespaceURI ,'xmlns:atom', 'http://www.w3.org/2005/Atom');
        $rss->setAttributeNS($namespaceURI ,'xmlns:sy', $ns_sy);
        $rss->setAttributeNS($namespaceURI ,'xmlns:slash', $ns_slash);
    
        $channel = new DOMElement('channel');
        $rss->appendChild($channel);
        $channel->appendChild(new DOMElement('copyright', 'Copyright (c) 2011-2013 ' . app()->name . '. All rights reserved.'));
        $channel->appendChild(new DOMElement('title', utf8ForXml($feedname)));
        $channel->appendChild(new DOMElement('link', app()->homeUrl));
        $channel->appendChild(new DOMElement('description', param('shortdesc')));
        $channel->appendChild(new DOMElement('lastBuildDate', date('D, d M Y H:i:s O', $_SERVER['REQUEST_TIME'])));
        $channel->appendChild(new DOMElement('language', app()->language));
        $channel->appendChild(new DOMElement('sy:updatePeriod', 'hourly', $ns_sy));
        $channel->appendChild(new DOMElement('sy:updateFrequency', '1', $ns_sy));
        $channel->appendChild(new DOMElement('generator', 'http://www.waduanzi.com/?v=' . CDBase::VERSION));
    
        foreach ((array)$models as $model) {
            $item = $dom->createElement('item');
            $channel->appendChild($item);
            $title = $model->getFilterTitle();
            if ($model->getImageIsAnimation()) $title .= '【动画】';
            $item->appendChild(new DOMElement('title', utf8ForXml($title)));
            $posturl = aurl('post/show', array('id'=>$model->id, 'source'=>$source));
//             $commentUrl = aurl('comment/list', array('pid'=>$model->id, 'source'=>$source));
            $commentUrl = aurl('post/show', array('id'=>$model->id, 'source'=>$source), '', 'comments');
            $item->appendChild(new DOMElement('link', htmlentities($posturl)));
            $item->appendChild(new DOMElement('comments', htmlentities($commentUrl)));
            $item->appendChild(new DOMElement('pubDate', date('D, d M Y H:i:s O', $model->create_time)));
            $item->appendChild(new DOMElement('comments', (int)$model->comment_nums, $ns_slash));
            if ($model->user_name)
                $item->appendChild(new DOMElement('dc:creator', utf8ForXml($model->user_name)));
    
            $desc = $dom->createElement('description');
            $descText = $model->getFilterContent();
            $desc->appendChild($dom->createCDATASection(utf8ForXml($descText)));
            $item->appendChild($desc);
        }
    
        return $dom->saveXML();
    }
    
    private static function sources()
    {
        return array(
            'sohunews',
            'zaker',
            'yuedu163',
            'qqmb',
            'xianguo',
            'wumii',
            'ucweb',
        );
    }
    
    private static function checkSource($source)
    {
        return in_array($source, self::sources());
    }
    
    private static function log()
    {
        $file = app()->getRuntimePath() . DS . 'feed.log';
        $log = date('Y-m-d H:i:s') . ' - ' . request()->getUserHostAddress() . ' - ' . request()->getUserAgent() . ' - ' . request()->getUrl() . "\n";
        $handle = fopen($file, 'a+');
        if (flock($handle, LOCK_EX)) {
            fwrite($handle, $log);
            flock($handle, LOCK_UN);
        }
        fclose($handle);
    }
    
    private static function replacePicUrlWithPosts(array $posts)
    {
        if ($posts) {
            foreach ($posts as $index => $post)
                $posts[$index] = self::replacePicUrlWithPost($post);
        }
        
        return $posts;
    }
    
    private static function replacePicUrlWithPost(Post $post)
    {
        $post->original_pic = str_replace('http://f0.wabao.me', 'http://unicom.f0.wabao.me', $post->original_pic);
        $search = array('http://f0.wabao.me', 'http://f1.wabao.me', 'http://f2.wabao.me', 'http://f3.wabao.me', 'http://f4.wabao.me');
        $replace = array('http://unicom.f0.wabao.me', 'http://unicom.f1.wabao.me', 'http://unicom.f2.wabao.me', 'http://unicom.f3.wabao.me', 'http://unicom.f4.wabao.me');
        $post->content = str_replace($search, $replace, $post->content);
        return $post;
    }
}

function utf8ForXml($string)
{
    return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
}