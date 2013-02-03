<?php
class Api_Movie extends ApiBase
{
    const PAGE_SIZE = 100;
    
    public function latestsets()
    {
        $cmd = app()->getDb()->createCommand()
            ->from(TABLE_MOVIE_SETS)
            ->order('id desc')
            ->limit(self::PAGE_SIZE);
        
        $rows = $cmd->queryAll();
        $rows = self::processSetsRows($rows);
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