<?php

/**
 * Created by IntelliJ IDEA.
 * User: k.seliga
 * Date: 02.03.16
 * Time: 14:28
 */
class AutorController extends Zend_Controller_Action
{

    public function listAction()
    {

        $autors = AutorQuery::create()
            ->find();


        $this->view->assign('autors', $autors);

    }

    public function addAction()
    {


        $autor = new Autor();
        $autor->setName('pierwszy aurot');


        $autor->save();

        $this->redirect('/autor/list');

    }
}