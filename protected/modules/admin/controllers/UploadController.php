<?php
class UploadController extends AdminController
{
    public function actionList()
    {
        $criteria = new CDbCriteria();
        $data = AdminUpload::fetchList($criteria, true, true);
        
        $this->adminTitle = '上传文件列表';
        $this->render('list', $data);
    }
    
    
    public function actionSearch()
    {
        $form = new UploadSearchForm();
        
        if (isset($_GET['UploadSearchForm'])) {
            $form->attributes = $_GET['UploadSearchForm'];
            if ($form->validate())
                $data = $form->search();
            user()->setFlash('table_caption', '文件搜索结果');
        }
        
        $this->adminTitle = '搜索结果';
        $fileTypes = AdminUpload::typeLabels();
        $this->render('search', array(
            'form' => $form,
            'data' => $data,
            'fileTypes' => $fileTypes,
        ));
    }
}