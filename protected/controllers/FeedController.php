<?php
class FeedController extends Controller
{
    const POST_COUNT = 200;
    
    public function init()
    {
        parent::init();
        header('Content-Type:application/xml; charset=' . app()->charset);
    }
    
    public function filters()
    {
        $duration = 600;
        return array(
            array(
                'COutputCache + index, joke, lengtu, girl, video, ghost, funny',
                'duration' => $duration,
            ),
            array(
                'COutputCache + channel',
                'duration' => $duration,
                'varyByParam' => array('cid'),
            ),
        );
    }
    
    public function actionIndex()
    {
        $where = 'state = :enabled';
        $params = array(':enabled'=>POST_STATE_ENABLED);
        $cmd = app()->getDb()->createCommand()
            ->where($where, $params);
        
        $rows = self::fetchPosts1($cmd);
        self::outputXml(app()->name, $rows);
        exit(0);
    }
    
    
    public function actionFunny()
    {
        self::channel(array(CHANNEL_DUANZI, CHANNEL_LENGTU));
    }
    
    public function actionJoke()
    {
        self::channel(CHANNEL_DUANZI);
    }
    
    public function actionGhost()
    {
        self::channel(CHANNEL_GHOSTSTORY);
    }
    
    public function actionLengtu()
    {
        self::channel(CHANNEL_LENGTU);
    }
    
    public function actionGirl()
    {
        self::channel(CHANNEL_GIRL);
    }
    
    public function actionVideo()
    {
        self::channel(CHANNEL_VIDEO);
    }
    
    public function actionChannel($cid)
    {
        $cid = (int)$cid;
        self::channel($cid);
    }
    
    private static function channel($cid)
    {
        $channels = param('channels');
        
        $criteria = new CDbCriteria();
        if (is_numeric($cid)) {
            $cid = (int)$cid;
            if (!array_key_exists($cid, $channels))
                throw new CHttpException(503, '此频道暂时没有开通');
                
            $criteria->addColumnCondition(array('channel_id'=>$cid));
        }
        elseif (is_array($cid)) {
            $cid = array_map('intval', $cid);
            foreach ($cid as $id) {
                if (!array_key_exists($id, $channels))
                    throw new CHttpException(503, $id . ' 此频道暂时没有开通');
            }
            $criteria->addInCondition('channel_id', $cid);
        }
        
        $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
        $models = self::fetchPosts($criteria);
        
        $feedname = app()->name . ' » ' . $channels[$cid];
        self::outputXml($feedname, $models);
        exit(0);
    }
    
    
    
    private static function fetchPosts(CDbCriteria $criteria)
    {
        $criteria->select = array('t.id', 't.title', 't.original_pic', 't.content', 't.create_time', 't.original_frames');
        $criteria->order = array('t.create_time desc', 't.id desc');
        $criteria->limit = self::POST_COUNT;
            
        $models = Post::model()->findAll($criteria);
        return $models;
    }

    private static function outputXml($feedname, array $models)
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
        //$rss->setAttributeNS($namespaceURI ,'xmlns:itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');
        $rss->setAttributeNS($namespaceURI ,'xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
        $rss->setAttributeNS($namespaceURI ,'xmlns:wfw', 'http://wellformedweb.org/CommentAPI/');
        $rss->setAttributeNS($namespaceURI ,'xmlns:dc', $ns_dc);
        $rss->setAttributeNS($namespaceURI ,'xmlns:atom', 'http://www.w3.org/2005/Atom');
        $rss->setAttributeNS($namespaceURI ,'xmlns:sy', $ns_sy);
        $rss->setAttributeNS($namespaceURI ,'xmlns:slash', $ns_slash);
    
        $channel = new DOMElement('channel');
        $rss->appendChild($channel);
        $channel->appendChild(new DOMElement('copyright', 'Copyright (c) 2011-2013 ' . app()->name . '. All rights reserved.'));
        $channel->appendChild(new DOMElement('title', $feedname));
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
            $title = $model->getFilterTitle;
            if ($model->getImageIsAnimation()) $title .= '【动画】';
            $item->appendChild(new DOMElement('title', $title));
            $item->appendChild(new DOMElement('link', aurl('post/show', array('id'=>$model->id, 'source'=>'feed'))));
            $item->appendChild(new DOMElement('comments', aurl('comment/list', array('pid'=>$model->id))));
            $item->appendChild(new DOMElement('pubDate', date('D, d M Y H:i:s O', $model->create_time)));
            $item->appendChild(new DOMElement('comments', (int)$model->comment_nums, $ns_slash));
            if ($model->user_name)
                $item->appendChild(new DOMElement('dc:creator', $model->user_name));
    
            $summary = $dom->createElement('summary');
            $summaryText = $model->getFilterSummary(100);
            $summary->appendChild($dom->createCDATASection($summaryText));
            $item->appendChild($summary);
    
            $content = $dom->createElement('content:encoded');
            $contentText = $model->getFilterContent();
            if ($model->getMiddlePic() && $model->uploadImagesCount == 0) {
                $contentText = '<p>' . $model->getMiddleImage() . '</p>' . $contentText;
            }
            $content->appendChild($dom->createCDATASection($contentText));
            $item->appendChild($content);
        }
    
        echo $dom->saveXML();
    }
    
    
    private static function channel1($cid)
    {
        $channels = param('channels');
        if (is_numeric($cid)) {
            $cid = (int)$cid;
            if (!array_key_exists($cid, $channels))
                throw new CHttpException(503, '此频道暂时没有开通');

            $where = array('and', 'channel_id = :channelID', 'state = :enabled');
            $params = array(':channelID'=>$cid, ':enabled'=>POST_STATE_ENABLED);
        }
        elseif (is_array($cid)) {
            $cid = array_map('intval', $cid);
            foreach ($cid as $id) {
                if (!array_key_exists($id, $channels))
                    throw new CHttpException(503, $id . ' 此频道暂时没有开通');
            }
            $where = array('and', array('in', 'channel_id', $cid), 'state = :enabled');
            $params = array(':enabled'=>POST_STATE_ENABLED);
        }
        
        $cmd = app()->getDb()->createCommand()
            ->where($where, $params);
        
        $rows = self::fetchPosts($cmd);
        
        $feedname = app()->name . ' » ' . $channels[$cid];
        self::outputXml($feedname, $rows);
        exit(0);
    }
    
    private static function fetchPosts1(CDbCommand $cmd)
    {
        $cmd->from(TABLE_POST)
            ->select(array('id', 'title', 'original_pic', 'content', 'create_time', 'original_frames'))
            ->order(array('create_time desc', 'id desc'))
            ->limit(self::POST_COUNT);
            
        $rows = $cmd->queryAll();
        return $rows;
    }

    private static function outputXml1($feedname, array $rows)
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
        //$rss->setAttributeNS($namespaceURI ,'xmlns:itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');
        $rss->setAttributeNS($namespaceURI ,'xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
        $rss->setAttributeNS($namespaceURI ,'xmlns:wfw', 'http://wellformedweb.org/CommentAPI/');
        $rss->setAttributeNS($namespaceURI ,'xmlns:dc', $ns_dc);
        $rss->setAttributeNS($namespaceURI ,'xmlns:atom', 'http://www.w3.org/2005/Atom');
        $rss->setAttributeNS($namespaceURI ,'xmlns:sy', $ns_sy);
        $rss->setAttributeNS($namespaceURI ,'xmlns:slash', $ns_slash);
    
        $channel = new DOMElement('channel');
        $rss->appendChild($channel);
        $channel->appendChild(new DOMElement('copyright', 'Copyright (c) 2011-2013 ' . app()->name . '. All rights reserved.'));
        $channel->appendChild(new DOMElement('title', $feedname));
        $channel->appendChild(new DOMElement('link', app()->homeUrl));
        $channel->appendChild(new DOMElement('description', param('shortdesc')));
        $channel->appendChild(new DOMElement('lastBuildDate', date('D, d M Y H:i:s O', $_SERVER['REQUEST_TIME'])));
        $channel->appendChild(new DOMElement('language', app()->language));
        $channel->appendChild(new DOMElement('sy:updatePeriod', 'hourly', $ns_sy));
        $channel->appendChild(new DOMElement('sy:updateFrequency', '1', $ns_sy));
        $channel->appendChild(new DOMElement('generator', 'http://www.waduanzi.com/?v=' . CDBase::VERSION));
    
        foreach ((array)$rows as $row) {
            $item = $dom->createElement('item');
            $channel->appendChild($item);
            $title = $row['title'];
            if ($row['original_frames'] > 1) $title .= '【动画】';
            $item->appendChild(new DOMElement('title', $title));
            $item->appendChild(new DOMElement('link', aurl('post/show', array('id'=>$row['id'], 'source'=>'feed'))));
            $item->appendChild(new DOMElement('comments', aurl('comment/list', array('pid'=>$row['id']))));
            $item->appendChild(new DOMElement('pubDate', date('D, d M Y H:i:s O', $row['create_time'])));
            $item->appendChild(new DOMElement('comments', (int)$row['comment_nums'], $ns_slash));
            if ($row['user_name'])
                $item->appendChild(new DOMElement('dc:creator', $row['user_name']));
    
            $summary = $dom->createElement('summary');
            $summaryText = mb_substr(strip_tags($row['content']), 0, 100, app()->charset);
            $summary->appendChild($dom->createCDATASection($summaryText));
            $item->appendChild($summary);
    
            $content = $dom->createElement('content:encoded');
            $contentText = strip_tags($row['content'], param('content_html_tags'));
            if ($row['original_pic']) {
                $thumb = new CDImageThumb($row['original_pic']);
                $contentText .= sprintf('<p><img src="%s" title="%s" alt="%s" border="0"></p>', $thumb->middleImageUrl(), $row['title'], $row['title']);
                unset($thumb);
            }
            $content->appendChild($dom->createCDATASection($contentText));
            $item->appendChild($content);
        }
    
        echo $dom->saveXML();
    }
}

