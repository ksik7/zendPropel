<?php

/**
 * Created by IntelliJ IDEA.
 * User: k.seliga
 * Date: 02.03.16
 * Time: 14:28
 */
class TestController extends Zend_Controller_Action
{

    public function init()
    {
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('list', 'json')
            ->initContext();
    }

    public function listAction()
    {

        $book= BookQuery::create()->find()->toArray();

        $paginator = Zend_Paginator::factory($book);
        $paginator->setItemCountPerPage(2)
            ->setCurrentPageNumber('page', 1);

        $count = $paginator->getCurrentItemCount();
        $bookJson = $paginator;
        $this->view->assign('booksJson', $bookJson);
        if(!$this->_request->isXmlHttpRequest()) {
            $this->view->assign('paginator', $paginator);
        }
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