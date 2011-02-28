<?php
defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(dirname(__FILE__))));

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(BASE_PATH.'/application'));

define('APPLICATION_ENV', 'testing');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BASE_PATH . '/library'),get_include_path(),
)));

require_once "Zend/Loader/Autoloader.php";
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace(
    array(
        'Doctrine',
        'Symfony',
        'Axiomes'
    )
);