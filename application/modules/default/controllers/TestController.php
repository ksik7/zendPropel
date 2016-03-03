<?php

/**
 * Created by IntelliJ IDEA.
 * User: k.seliga
 * Date: 02.03.16
 * Time: 14:28
 */
class TestController extends Zend_Controller_Action
{
    public function listAction()
    {

        $book = BookQuery::create()
            ->joinWith('Autor')
            ->findOne();



        $this->view->assign('books', $book);

    }

    public function addAction()
    {

        $book = new Book();
        $book->setName('pierwsza ksiażka');
        $book->save();

        $this->redirect('/test/list');

    }

    public function indexAction()
    {
        $books = BookQuery::create()->find();
        $this->view->assign('books', $books);
    }

    public function signAction()
    {
        $form = new Application_Form_Book();

        $request = $this->getRequest();

        if($this->getRequest()->isPost()){
            if($form->isValid($request->getPost())){
                $book = new Book();

                $value = $form->getValues();


                $book->setName($value['email']);

                $book->save();

                return $this->redirect('/test');
            }
        }

        $this->view->form = $form;
    }
}