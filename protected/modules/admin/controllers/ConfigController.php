<?php
class ConfigController extends AdminController
{
    public function actionView($categoryid)
    {
        $categoryid = (int)$categoryid;
        $cmd = app()->getDb()->createCommand()
            ->from(AdminConfig::model()->tableName())
            ->order('id asc')
            ->where('category_id = :categoryid', array(':categoryid' => $categoryid));
        $rows = $cmd->queryAll();
        
        $labels = AdminConfig::categoryLabels();
        $this->adminTitle = '查看配置参数&nbsp;-&nbsp;' . $labels[$categoryid];
        $this->render('list', array(
            'models'=>$rows,
            'categoryid' => $categoryid,
        ));
    }
    
    public function actionEdit($categoryid)
    {
        $categoryid = (int)$categoryid;
        
        if (request()->getIsPostRequest() && isset($_POST['AdminConfig'])) {
            $params = $_POST['AdminConfig'];
            $result = self::saveConfigParams($params);
            if ($result === true) {
                user()->setFlash('save_config_success', '参数保存成功');
                self::afterSaveConfig();
            }
            else
                $errorNames = $result;
        }
        
        $cmd = app()->getDb()->createCommand()
            ->from(AdminConfig::model()->tableName())
            ->order('id asc')
            ->where('category_id = :categoryid', array(':categoryid' => $categoryid));
        $rows = $cmd->queryAll();
        
        $labels = AdminConfig::categoryLabels();
        $this->adminTitle = '查看配置参数&nbsp;-&nbsp;' . $labels[$categoryid];
        $this->render('edit', array(
            'models'=>$rows,
            'categoryid' => $categoryid,
            'errorNames' => $errorNames,
        ));
    }
    
    public static function saveConfigParams(array $params)
    {
        $names = array();
        foreach ($params as $name => $value) {
            try {
                $result = app()->getDb()->createCommand()
                    ->update(TABLE_CONFIG, array('config_value'=>$value), 'config_name = :configname', array(':configname'=>$name));
            }
            catch (Exception $e) {
                array_push($names, $name);
            }
        }
        return empty($names) ? true : $names;
    }
    
    public function afterSaveConfig()
    {
        AdminConfig::flushAllConfig();
    }
    
    public function actionCreate($id = 0)
    {
        $id = (int)$id;
        $model = ($id > 0) ? AdminConfig::model()->findByPk($id) : new AdminConfig();
        
        if (request()->getIsPostRequest() && isset($_POST['AdminConfig'])) {
            $model->attributes = $_POST['AdminConfig'];
            if ($model->save()) {
                $this->afterSaveConfig();
                user()->setFlash('save_config_success', '参数保存成功');
                request()->redirect(url('admin/config/view', array('categoryid'=>$model->category_id)));
            }
        }
        
        $this->adminTitle = '新建自定义参数';
        $this->render('create', array(
            'model' => $model,
        ));
    }

}

