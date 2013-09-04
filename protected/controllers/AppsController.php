<?php
class AppsController extends Controller
{
    public function actionIndex()
    {
        $this->setPageTitle('挖段子官方应用，挖段子iPhone应用，挖段子Android应用');
        $this->setKeywords('挖段子官方应用,挖段子iPhone应用,挖段子Android应用');
        $this->setDescription('挖段子官方应用，挖段子iPhone应用，挖段子Android应用');
        $this->layout = 'blank';
        $this->render('wdz');
    }
}