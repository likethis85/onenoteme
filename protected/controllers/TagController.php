<?php
class TagController extends Controller
{
    public function actionList()
    {
        $cacheKey = 'all_tags';
        $tags = app()->getCache()->get($cacheKey);
        if ($tags === false) {
            $cmd = app()->getDb()->createCommand()
                ->order('id asc');
            $tags = DTag::model()->findAll($cmd);
            app()->getCache()->set($cacheKey, $tags, 24*60*60);
        }
        foreach ($tags as $tag)
            $postNums[] = $tag->post_nums;
        
        if ($tags) {
            $max = max($postNums);
            $min = min($postNums);
            $half1 = (int)(($max - $min) / 2 + $min);
            $half2 = (int)(($max - $min) / 4 * 3 + $min);
            
            foreach ($tags as $tag) {
                $nums = $tag->post_nums;
                if ($nums >= $min && $nums < $half1) {
                    $levels[] = 'tag-level1';
                }
                elseif ($nums >= $half1 && $nums < $half2) {
                    $levels[] = 'tag-level2';
                }
                elseif ($nums >= $half2 && $nums <= $max) {
                    $levels[] = 'tag-level3';
                }
            }
        }
        
        $this->pageTitle = '各种段子标签 - 挖段子';
        $this->setKeywords('各种段子标签');
        $this->setDescription('各种段子标签');
        
        $this->channel = 'tag';
        $this->render('list', array(
        	'tags' => (array)$tags,
        	'levels'=>$levels,
        ));
    }
    
    public function actionPosts($name)
    {
        $limit = (int)param('postCountOfPage');
        $name = urldecode($name);
        $cmd = app()->getDb()->createCommand()
            ->select('t.*')
            ->join(TABLE_POST_TAG . ' pt', 't.id = pt.post_id')
            ->join(TABLE_TAG . ' tag', 'tag.id = pt.tag_id')
            ->where('t.state != :state and tag.name = :tagname', array(':state'=>DPost::STATE_DISABLED, ':tagname' => $name))
            ->order('t.create_time desc, t.id desc');
        $pages = new CPagination(DPost::model()->count(clone $cmd));
        $pages->setPageSize($limit);
        
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->limit($limit);
        $cmd->offset($offset);
        
        $models = DPost::model()->findAll($cmd);
        
        $this->pageTitle = $name . '相关段子 - 挖段子';
        $this->setKeywords("{$name}相关段子,{$name}相关冷笑话,{$name}相关糗事,{$name}相关语录");
        $this->setDescription("与{$name}有关的相关段子、笑话、冷笑话、糗事、经典语录");
        
        $this->channel = 'tag';
        $this->render('posts', array(
        	'models' => $models,
            'pages' => $pages,
            'tagname' => $name,
        ));
    }
}