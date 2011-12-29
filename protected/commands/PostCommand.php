<?php
class PostCommand extends CConsoleCommand
{
    public function actionUpdateStateEnable($count)
    {
        $ids = app()->getDb()->createCommand()
            ->from('{{post}}')
            ->order('id asc')
            ->limit($count)
            ->where('state = :disable_state', array(':disable_state'=>Post::STATE_DISABLED))
            ->queryColumn();
        
        $nums = app()->getDb()->createCommand()
            ->update('{{post}}',
                array('state'=>Post::STATE_ENABLED, 'create_time'=>(int)$_SERVER['REQUEST_TIME']),
                array('in', 'id', $ids)
            );
        
        printf("update %d rows\n", $nums);
    }
}