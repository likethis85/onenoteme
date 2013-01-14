<?php
class ToolController extends Controller
{
    public function actionTaijiong()
    {
        $this->redirect(aurl('app/taijiong'), true, 301);
    }
}