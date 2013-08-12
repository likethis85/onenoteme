<?php
class AppsController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'blank';
        $this->render('wdz');
    }
}