<?php

class Ks_Plugin_AccessPlugin extends Zend_Controller_Plugin_Abstract
{

    private $_auth;
    private $_acl;

    public function __construct(Zend_Acl $acl, Zend_Auth $auth)
    {
        $this->_acl = $acl;
        $this->_auth = $auth;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $resource = $module . '-' . $controller;

        $identity = $this->_auth->getStorage()->read();

        if ($identity != null) {

            $role = $identity->getRole();

        }

        if ($this->_acl->has($resource)) {

            if (!$this->_acl->isAllowed($role, $resource, $action)) {

                $request->setModuleName('auth')
                    ->setControllerName('auth')
                    ->setActionName('login');

            }
        }
    }

}