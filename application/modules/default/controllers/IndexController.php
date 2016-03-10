<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/auth/auth/login');
        }
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {

    }

}

