<?php
class BetaCategoryNavbar extends CWidget
{
    public function init()
    {
        
    }
    
    public function run()
    {
        $models = $this->fetchCategories();
        if (empty($models)) return ;
        
        $this->render('category_navbar', array(
            'models' => $models,
        ));
    }
    

    private function fetchCategories()
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'name');
        $criteria->order = 'orderid desc, id desc';
        $models = MobileCategory::model()->findAll($criteria);
        
        return $models;
    }
}