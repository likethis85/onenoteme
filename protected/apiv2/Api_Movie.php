<?php
class Api_Movie extends ApiBase
{
    const PAGE_SIZE = 100;
    
    public function latestsets()
    {
        $cmd = app()->getDb()->createCommand()
            ->select(array('title', 'content', 'extra02', 'extra03', 'create_time'))
            ->from(TABLE_POST)
            ->where('channel_id = :videoChannelID and media_type = :videotype', array(':videoChannelID'=>CHANNEL_FUNNY, ':videotype'=>MEDIA_TYPE_VIDEO))
            ->order(array('create_time desc', 'id desc'))
            ->limit(self::PAGE_SIZE);
        
        $rows = $cmd->queryAll();
        $rows = self::processSetsRows($rows);
        return $rows;
    }
    
    private static function processSetsRows($rows)
    {
        foreach ((array)$rows as $index => $row) {
            $rows[$index]['icon'] = '';
            
            $rows[$index]['name'] = $row['title'];
            $rows[$index]['url'] = $row['extra03'];
            
            $story = trim($row['content']);
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
