<?php
//Routes
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', str_replace(DS . 'Public', '', getcwd())  . DS);
define('DOMAIN', 'https://localhost' . DS);

//Errors
define('ERRORS_PRINT', true);

//Core code
define('CORE',    ROOT  . 'Core' . DS);
define('REQUEST', CORE . 'request.php');
define('ROUTER',  CORE . 'router.php');
define('ROUTES',  CORE . 'routes.php');

//Folders
define('LOGS_PATH', ROOT . 'Logs' . DS);
define('LOGS', ROOT . 'Logs' . DS . 'logs.php');
define('LIBS_PATH', ROOT . 'Libs' . DS);
define('CONTROLLER_PATH', ROOT . 'Controller' . DS);
define('MODEL_PATH', ROOT . 'Models' . DS);
define('VIEW_PATH', getcwd() . DS . 'Views' . DS);
define('TRAIT_PATH', LIBS_PATH . 'Traits' . DS);
define('INTERFACE_PATH', LIBS_PATH . 'Interfaces' . DS);
define('ERROR_PAGES', VIEW_PATH . 'Errors' . DS);
define('ERROR404', ERROR_PAGES . "404.html");
define('ART', ERROR_PAGES . "Art.html");
