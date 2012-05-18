<?php
class TagController extends Controller
{
    public function actionList()
    {
        $cacheKey = 'all_tags';
        $tags = app()->getCache()->get($cacheKey);
        if ($tags === false) {
            $tags = Tag::model()->findAll();
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
        $duration = 120;
        $name = urldecode($name);
        
        $tagID = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_TAG)
            ->where('name = :tagname', array(':tagname' => $name))
            ->queryScalar();
        
        if ($tagID === false)
            throw new CHttpException(403, "当前还没有与{$name}标签有关的段子");
        
        $count = app()->getDb()->cache($duration)->createCommand()
        ->select('count(*)')
        ->from(TABLE_POST_TAG)
        ->where('tag_id = :tagid', array(':tagid' => $tagID))
        ->queryScalar();
        
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $offset = ($pages->currentPage - 1) * $limit;
        $limit = (int)param('postCountOfPage');
        $postIDs = app()->getDb()->createCommand()
            ->select('post_id')
            ->from(TABLE_POST_TAG)
            ->where('tag_id = :tagid', array(':tagid' => $tagID))
            ->order('post_id desc')
            ->limit($limit)
            ->offset($offset)
            ->queryColumn();
        
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $postIDs);

        $models = Post::model()->findAll($criteria);
        
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