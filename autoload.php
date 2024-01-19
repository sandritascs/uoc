<?php

require __DIR__ . '/vendor/autoload.php';

include_once dirname(__FILE__) . "/lib/phpqrcode/qrlib.php";

/**
 * Libraries and classes
 *
 * @param string $class_name
 */
function autoload($class_name) {
    // ----- Clase conexión BD
    if ($class_name == 'dbConnector') {
        include_once dirname(__FILE__) . '/config/dbConnector.php';
    } elseif ($class_name == 'newSmarty') {
        include_once dirname(__FILE__) . '/config/newSmarty.php';
    } 
    
}

spl_autoload_register('autoload');

