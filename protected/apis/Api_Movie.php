<?php
class Api_Movie extends ApiBase
{
    const LIST_MAC_COUNT = 100;
    
    public function listofcategory(/*$category_id, $count*/)
    {
        self::requiredParams(array('category_id'));
        $params = $this->filterParams(array('category_id', 'count'));
        
        $cid = (int)$params['category_id'];
        if ($cid <= 0) return array();
        
        $count = (int)$params['count'];
        $count = $count > 0 ? $count : self::LIST_MAC_COUNT;
        
        $cmd = app()->getDb()->createCommand()
            ->from(TABLE_MOVIE)
            ->limit($count)
            ->order('orderid desc, id desc')
            ->where(array('and', 'category_id = :cid', 'state = :valid'), array(':cid'=>$cid, ':valid'=>Movie::STATE_ENABLED));
        
        $rows = $cmd->queryAll();
        $rows = self::processMovieRows($rows);
        return $rows;
    }
    
    public function sets(/*$movie_id*/)
    {
        self::requiredParams(array('movie_id'));
        $params = $this->filterParams(array('movie_id'));
        
        $movieid = (int)$params['movie_id'];
        if ($movieid <= 0) return array();
        
        $cmd = app()->getDb()->createCommand()
            ->from(TABLE_MOVIE_SETS)
            ->order('id asc')
            ->where(array('and', 'movie_id = :movieid'), array(':movieid'=>$movieid));
        
        $rows = $cmd->queryAll();
        $rows = self::processSetsRows($rows);
        return $rows;
    }
    
    public function latestsets()
    {
        $cmd = app()->getDb()->createCommand()
            ->from(TABLE_MOVIE_SETS)
            ->order('id desc');
        
        $rows = $cmd->queryAll();
        $rows = self::processSetsRows($rows);
        return $rows;
    }
    
    // @todo 暂时没有实现
    public function listofspecial(/*$special_id*/)
    {
        self::requiredParams(array('special_id'));
        $params = $this->filterParams(array('special_id', 'count'));
        
        $specialID = (int)$params['special_id'];
        if ($specialID <= 0) return array();
        
        $cmd = app()->getDb()->createCommand()
            ->select('m.*')
            ->from(TABLE_SPECIAL2MOVIE . ' sm')
            ->where('sm.special_id = :specialid', array(':specialid'=>$specialID))
            ->join(TABLE_MOVIE . ' m', 'sm.movie_id = m.id and m.state = :enabled', array(':enabled'=>Movie::STATE_ENABLED))
            ->order('m.id desc');
        
        $rows = $cmd->queryAll();
        $rows = self::processMovieRows($rows);
        return $rows;
    }
    
    private static function processMovieRows($rows)
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
            
            $createTime = (int)$row['create_time'];
            if ($createTime > 0) {
                $rows[$index]['createTimeText'] = date(param('formatShortDateTime'), $createTime);
            }
            
            $updateTime = (int)$row['update_time'];
            if ($createTime > 0) {
                $rows[$index]['createTimeText'] = date(param('formatShortDateTime'), $createTime);
            }
        }
        
        return $rows;
    }
    
    private static function processSetsRows($rows)
    {
        foreach ((array)$rows as $index => $row) {
            $icon = trim($row['url']);
            if (empty($icon)) {
                $rows[$index]['icon'] = '';
            }
            
            $story = trim($row['story']);
            if (empty($story)) {
                $rows[$index]['story'] = '';
            }
            
            $createTime = (int)$row['create_time'];
            if ($createTime > 0) {
                $rows[$index]['createTimeText'] = date(param('formatShortDateTime'), $createTime);
            }
        }
        
        return $rows;
    }
}