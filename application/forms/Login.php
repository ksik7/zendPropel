<?php

class Application_Form_Login extends Zend_Form
{

    public $elementDecorators = array(
        'ViewHelper',
        array('Label', array('tag' => 'span', 'placement' => 'prepend')),
        array('Description', array('tag' => 'div', 'placement' => 'append', 'class' => 'eDesc')),
        array('HtmlTag', array('tag' => 'div', 'class' => 'userElement'))
    );

    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName('login');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Login:');
        $username->setRequired(true);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:');
        $password->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Login');

        $this->addElements(array($username, $password, $submit));
        $this->setMethod('post');
        $this->setAction('/auth/auth/login');
    }
}