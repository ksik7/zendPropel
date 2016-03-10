<?php

class Ks_Form_Base_Form extends Zend_Form
{

    // text, password, select, checkbox and radio elements by default
    public $elementDecorators = array(
        'ViewHelper',
        'Errors',
        array(
            'Description',
            array(
                'tag' => 'p',
                'class' => 'description'
            ),
            array(
                'class' => 'form-div'
            ),
            array(
                'class' => 'description',
                'requiredSuffix' => '*'
            )
        ),
    );

    //file elements
    public $fileDecorators = array(
        'File',
        'Errors',
        array(
            'tag' => 'p',
            'class' => 'description'
        ),
        array(
            'HtmlTag',
            array(
                'class' => 'form-div'
            )),
        array(
            'Label',
            array(
                'class' => 'form-label',
                'requiredSuffix' => '*'
            ))
    );

    //none wrapper
    public $elementDecoratorsNoTag = array();


    //buttons
    public $buttonDecorators = array(
        'ViewHelper',
        array(
            'HtmlTag',
            array(
                'tag' => 'div',
                'class' => 'form-button'
            ))
    );


    public function __construct()
    {
        foreach($this->elementDecorators as $decorator) {
            if (is_array($decorator) && $decorator[0] == 'HtmlTag') {
                continue;
            }
            $this->elementDecoratorsNoTag[] = $decorator;
        }

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div', 'class' => 'form')),
            'Form'));

        // set the default decorators to our element decorators, any elements added to the form
        // will use these decorators
        $this->setElementDecorators($this->elementDecorators);

        parent::__construct();
        // parent::__construct must be called last because it calls $form->init()
        // and anything after it is not executed
    }

}