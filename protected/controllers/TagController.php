<?php
class TagController extends Controller
{
    public function actionList()
    {
        $cmd = app()->getDb()->createCommand()
            ->order('id asc');
        $tags = DTag::model()->findAll($cmd);
        foreach ($tags as $tag)
            $postNums[] = $tag->post_nums;
        $max = max($postNums);
        $min = min($postNums);
        $half1 = (int)(($max - $min) / 3 + $min);
        $half2 = (int)(($max - $min) / 3 * 2 + $min);
        
        foreach ($tags as $tag) {
            $nums = $tag->post_nums;
            if ($nums >= $min && $nums < $half1) {
                $tag_level = 'tag-level1';
                $levels[] = $tag_level;
            }
            elseif ($nums >= $half1 && $nums < $half2) {
                $tag_level = 'tag-level2';
                $levels[] = $tag_level;
            }
            elseif ($nums >= $half2 && $nums <= $max) {
                $tag_level = 'tag-level3';
                $levels[] = $tag_level;
            }
        }
        
        $this->render('list', array('tags' => $tags, 'levels'=>$levels));
    }
    
    public function actionPosts($tag)
    {
        $tag = urldecode($tag);
        $cmd = app()->getDb()->createCommand()
            ->join('{{post2tag}} pt', 't.id = pt.post_id')
            ->join('{{tag}} tag', 'tag.id = pt.tag_id')
            ->where('tag.name = :tagname', array(':tagname' => $tag));
        $posts = DPost::model()->findAll($cmd);
        $this->render('posts', array('posts'=>$posts));
    }
}