<?php

class Ks_LibraryAcl_LibraryAcl extends Zend_Acl{

    public function __construct()
    {
        $this->addResource(new Zend_Acl_Resource('default-index'));
        $this->addResource(new Zend_Acl_Resource('default-test'));

        $this->addRole(new Zend_Acl_Role('user'));
        $this->addRole(new Zend_Acl_Role('admin'), 'user');


        $this->allow('user', 'default-index', array('index'));
        $this->allow('admin', 'default-test');

    }

}