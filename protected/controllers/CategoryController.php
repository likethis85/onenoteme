<?php
class CategoryController extends Controller
{
    public function actionIndex($cid, $page = 1, $s = POST_LIST_STYLE_LINE)
    {
        if (is_numeric($cid)) {
            $channeID = (int)$cid;
            $category = Category::model()->findByPk($channeID);
        }
        elseif (is_string($cid)) {
            $token = trim(strip_tags($cid));
            $category = Category::findByToken($token);
        }
        if (empty($category))
            throw new CHttpException(403, '该频道暂时还未开通');
    
        $this->pageTitle = $category->name;
        $this->setDescription($category->desc);
        $this->setKeywords($category->name);
    
        $this->channel = $category->id;
        $count = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('duanzi_count_page');
        $data = $this->fetchPosts($category->id, null, $count);
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'text_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    
}