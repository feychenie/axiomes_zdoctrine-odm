<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initResourceInjector()
    {
        Zend_Controller_Action_HelperBroker::addHelper(
            new \Axiomes\Controller\Action\Helper\ResourcesInjector()
        );
    }

}

