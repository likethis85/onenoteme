<?php
class RestUser extends User
{

    /**
     * Returns the static model of the specified AR class.
     * @return RestUser the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        $relations = parent::relations();
        $relations['favorites'] = array(self::MANY_MANY, 'RestPost', '{{post_favorite}}(user_id, post_id)', 'order'=>'favorites.create_time desc');
        $relations['posts'] = array(self::HAS_MANY, 'RestPost', 'user_id', 'order'=>'posts.create_time desc');
        
        return $relations;
    }
}