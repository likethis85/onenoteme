<?php
/**
 *
 * @author chendong
 *
 * @property RestUser $user
 * @property array $comments
 * @property RestPostVideo $video
 */
class RestPost extends Post
{
    /**
     * Returns the static model of the specified AR class.
     * @return RestPost the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array_merge(parent::relations(), array(
            'user' => array(self::BELONGS_TO, 'RestUser', 'user_id',
    		        'select' => array('id', 'username', 'screen_name', 'create_time', 'create_ip', 'state', 'token', 'token_time', 'source')),
            'comments' => array(self::HAS_MANY, 'RestComment', 'post_id', 'limit'=>10),
            'video' => array(self::HAS_ONE, 'RestPostVideo', 'post_id'),
        ));
    }
    
    public function getApiCreateTime()
    {
        $format = 'n月j日 H:i';
        return parent::getCreateTime($format);
    }
    
    public function getApiTitle()
    {
        return trim(strip_tags($this->title));
    }
    
    public function getApiContent()
    {
        $content = strip_tags($this->content);
        $content = str_replace(array('&nbsp;', '　', ' ', "\t", '    '), '', $content);
        $lines = explode("\n", $content);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines);
        $content = join("\n", $lines);
        return $content;
    }
    
    public function getApiContentHtml()
    {
        $html = '<!doctype html><html><head><meta charset="utf-8" /><title>' . $this->title . '</title></head><body>';
        $html .= '<h1>' . $this->title . '</h1>';
        $html .= sprintf('<div class="timeauthor">%s&nbsp;&nbsp;%s</div>', $this->getCreateTime(p('formatShortDateTime')), $this->getAuthorName());
        
        $content = strip_tags($this->content, '<p><b><strong><span><img><br>');
        $matches = array();
        $pattern = '/<img.*?src="?(.+?)["\s]{1}?.*?>/is';
        $result = preg_match_all($pattern, $content, $matches);
        if ((int)$result > 0) {
            foreach ($matches[1] as $url) {
                $imgs[] = '<img src="' . $url . '" width="300" height="200" />';
            }
            $content = str_replace($matches[0], $imgs, $content);
        }
        
        $html .= '<div class="container">' . $content . '</div>';
        $html .= '</body></html>';
        
        return $html;
    }
}