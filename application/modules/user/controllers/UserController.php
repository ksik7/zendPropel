<?php

class UserController extends Zend_Controller_Action
{
// ...

    public function preDispatch()
    {
// ...

        $this->view->render('user/_sidebar.phtml');

// ...
    }

// ...
}