<?php

class Admin_IndexController extends Zend_Controller_Action
{

    /**
     * @See \Axiomes\Controller\Action\Helper\ResourcesInjector
     */
    public $dependencies = array(
        'doctrineodm'
    );

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $test = new Admin_Document_Test();
        $this->doctrineodm->persist($test);
        $this->doctrineodm->flush();
    }

    public function listAction(){
        $tests = $this->doctrineodm->getRepository('Admin_Document_Test')->findAll()->toArray();
        Zend_Debug::dump($tests);
        die();
    }


}

