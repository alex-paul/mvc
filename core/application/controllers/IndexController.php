<?php
class IndexController extends Controller
{
    public function init()
    {
        $this->loadExtension('form');
    }
    public function indexAction()
    {
       $aParameters = $this->getParameters();
       $this->renderView('index/form/contact',$aParameters);
    }

    public function formAction()
    {
        $this->loadModel('RegisterForm');
        $oForm = new RegisterForm('post', 'part1/part2/part3');

        $this->renderView('index/form/contact', array(
           'form' => $oForm
        ));
    }
}