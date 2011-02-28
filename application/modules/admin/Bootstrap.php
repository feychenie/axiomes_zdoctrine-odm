<?php
/**
 * 
 */
 
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap{

    public function initResourceLoader(){
        $resourceLoader = parent::getResourceLoader();
        $resourceLoader->addResourceTypes(
            array(
                'documents' => array(
                    'namespace' => 'Document',
                    'path' => 'domain/documents'
                ),
                'repositories' => array(
                    'namespace' => 'Repository',
                    'path' => 'domain/repositories'
                )
            )
        );
    }
}
