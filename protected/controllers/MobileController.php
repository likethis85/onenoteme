<?php
class MobileController extends Controller
{
    public function init()
    {
        $this->layout = 'mobile';
    }
    
    public function actionIndex()
    {
        $limit = param('postCountOfPage');
        $where = 't.state != :state';
        $params = array(':state' => DPost::STATE_DISABLED);
        $cmd = app()->db->createCommand()
            ->order('t.create_time desc, t.id desc')
            ->limit($limit)
            ->where($where, $params);
            
        $count = DPost::model()->count($where, $params);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        $models = DPost::model()->findAll($cmd);
        
        $this->pageTitle = '挖段子 - 笑死人不尝命';
        $this->setKeywords('笑话大全,黄段子,爆笑短信,最新段子,最全的段子,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'latest';
        $this->render('index', array(
        	'models' => $models,
            'pages' => $pages,
        ));
    }
    
    public function actionTag($name)
    {
        $limit = (int)param('postCountOfPage');
        $name = urldecode($name);
        $cmd = app()->getDb()->createCommand()
            ->select('t.*')
            ->join('{{post2tag}} pt', 't.id = pt.post_id')
            ->join('{{tag}} tag', 'tag.id = pt.tag_id')
            ->where('tag.name = :tagname', array(':tagname' => $name));
        $pages = new CPagination(DPost::model()->count(clone $cmd));
        $pages->setPageSize($limit);
        
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        
        $models = DPost::model()->findAll($cmd);
        
        $this->pageTitle = $name . '相关段子 - 挖段子';
        $this->setKeywords("{$name}相关段子,{$name}相关冷笑话,{$name}相关糗事,{$name}相关语录");
        $this->setDescription("与{$name}有关的相关段子、笑话、冷笑话、糗事、经典语录");
        
        $this->channel = 'tag';
        $this->render('index', array(
        	'models' => $models,
            'pages' => $pages,
            'listTitle' => "与{$name}相关的段子。。。",
        ));
    }
    
    public function actionWeek()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1W'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        
        $this->pageTitle = '一周最热门 - 挖段子';
        $this->setKeywords('热门段子,热门经典语录,热门糗事百科,热门秘密,热门笑话,热门搞笑,笑话热门排行,冷笑话排行');
        $this->setDescription('一周内段子排行，一周内笑话排行，一周内经典语录排行 ，一周糗事排行。');
        
        $this->channel = 'hottop';
        $this->render('index', array(
        	'models' => $models,
        ));
    }
    
    public function actionChannel($id)
    {
        $id = (int)$id;
        $limit = param('postCountOfPage');
        $where = 't.state != :state and channel_id = :channelid';
        $params = array(':state' => DPost::STATE_DISABLED, ':channelid'=>$id);
        $cmd = app()->db->createCommand()
        ->order('t.create_time desc, t.id desc')
        ->limit($limit)
        ->where($where, $params);
        
        $count = DPost::model()->count($where, $params);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        $models = DPost::model()->findAll($cmd);
        
        $this->pageTitle = '挖段子 - 笑死人不尝命';
        $this->setKeywords('笑话大全,黄段子,爆笑短信,最新段子,最全的段子,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'latest';
        $this->render('index', array(
            'models' => $models,
            'pages' => $pages,
        ));
    }
}