<?php
class IndexController extends Controller
{
    public function indexAction()
    {
       $aParameters = $this->getParameters();
       $this->renderView('index/form/contact',$aParameters);
    }
}