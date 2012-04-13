<?php
class Api_Mcategory extends ApiBase
{
    public function getlist()
    {
        $rows = app()->getDb()->createCommand()
            ->from(TABLE_NAME_MOVIE_CATEGORY)
            ->order('orderid desc, id asc')
            ->queryAll();
        
        $rows = self::processRows($rows);
        return $rows;
    }
    
    public function update_view_nums(/*$category_id*/)
    {
        self::requirePost();
        self::requiredParams(array('category_id'));
        $params = $this->filterParams(array('category_id'));
        $cid = (int)$params['category_id'];
        
        $counters = array('view_nums' => 1);
        $result = MovieCategory::model()->updateCounters($counters, 'id = :cid', array(':cid'=>$cid));
        return $result;
    }
    
    private static function processRows($rows)
    {
        foreach ((array)$rows as $index => $row) {
            $icon = trim($row['icon']);
            if (!empty($icon)) {
                $pos = strpos($icon, 'http://');
                if ($pos === false)
                    $iconUrl = fbu($icon);
                elseif ($pos === 0)
                    $iconUrl = $icon;
                else
                    $iconUrl = '';
                $rows[$index]['icon'] = $iconUrl;
            }
        }
        
        return $rows;
    }
}