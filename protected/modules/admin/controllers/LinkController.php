<?php
class LinkController extends AdminController
{
    public function filters()
    {
        return array(
            'postOnly + updateOrderID',
        );
    }
    

	public function actionCreate($id = 0)
	{
	    $id = (int)$id;
	    
	    if ($id > 0) {
	        $model = AdminLink::model()->findByPk($id);
	        $this->adminTitle = '编辑链接';
	    }
	    else {
	        $model = new AdminLink();
	        $this->adminTitle = '添加链接';
	    }
	    
	    if (request()->getIsPostRequest() && isset($_POST['AdminLink'])) {
	        $model->attributes = $_POST['AdminLink'];
	        if ($model->save()) {
	            self::clearLinksCache();
	            user()->setFlash('save_link_result', $model->name . '&nbsp;链接添加成功');
	            $this->redirect(request()->getUrl());
	        }
	    }
	    
	    $this->render('create', array(
	        'model' => $model,
	    ));
	}
	

	public function actionUpdateOrderID()
	{
	    try {
	        $rows = (array)$_POST['itemid'];
	        foreach ($rows as $id => $orderid) {
	            AdminLink::model()->updateByPk((int)$id, array('orderid'=>(int)$orderid));
	        }
	        self::clearLinksCache();
	        user()->setFlash('order_id_save_result_success', '链接排序ID保存成功');
	    }
	    catch (Exception $e) {
	        user()->setFlash('order_id_save_result_error', '链接排序ID保存失败：' . $e->getMessage());
	    }
	    request()->redirect(url('admin/Link/list'));
	}
	
	public function actionList()
	{
	    $criteria = new CDbCriteria();
	    $criteria->limit = param('adminLinkCountOfPage');
	    
	    $sort = new CSort('Link');
	    $sort->defaultOrder = 'orderid asc, id asc';
	    $sort->applyOrder($criteria);
	    
	    $pages = new CPagination(AdminLink::model()->count($criteria));
	    $pages->pageSize = $criteria->limit;
	    $pages->applyLimit($criteria);
	    
	    $models = AdminLink::model()->findAll($criteria);
	    
	    $data = array(
	        'models' => $models,
	        'sort' => $sort,
	        'pages' => $pages,
	    );
	    
	    $this->render('list', $data);
	}
	
	private static function clearLinksCache()
	{
	    $redis = cache('redis');
	    if ($redis) {
	        $result = $redis->delete('cache_friend_links');
	        return $result;
	    }
	    return true;
	}
}

