<?php
// this will autoload the class that we need in our code
spl_autoload_register(function ($class) {
    $dirname = dirname(__FILE__);

    // we are assuming that it is in the same directory as common.php
    // otherwise we have to do
    // $path = 'path/to/' . $class . ".php"
    // require_once "$class.php";
    $pathClasses = $dirname . '/../class/' . $class . '.php';
//    $pathConnectionManager = $dirname . "/" . $class . '.php';
//    $pathValidationManager = $dirname . "/../validate/" . $class . '.php';

    if (file_exists($pathClasses)) {
        require_once $pathClasses;
//    } elseif (file_exists($pathConnectionManager)) {
//        require_once $pathConnectionManager;
//    } elseif (file_exists($pathValidationManager)) {
//        require_once $pathValidationManager;
    }

});

// session related stuff

session_start();