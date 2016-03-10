<?php
class Auth_AuthController extends Zend_Controller_Action
{
    public function init()
    {
    }
    public function indexAction()
    {
    }
    public function loginAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/');
        }
        $request = $this->getRequest();
        $form = new Application_Form_Login();
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $adapter = new Ks_Auth_Adapter_Db($form->getValue('username'), $form->getValue('password'), 'User');
                $auth = Zend_Auth::getInstance();
                $result = $adapter->authenticate();
                if ($result->isValid()) {
                    echo 'valid';
                    $authStorage = $auth->getStorage();
                    $authStorage->write($adapter->user->getId());
                    $this->redirect('/');
                } else {
                    $this->view->errorMessage = "Username or password is not correct.";
                }
            }
        }
        $this->view->form = $form;
    }
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->redirect('/');
    }
}