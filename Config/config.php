<?php
//Routes
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', str_replace(DS . 'Public', '', getcwd()) . DS);
define('DOMAIN', 'http://localhost/ModernMVC/Public/');

//Errors
define('ERRORS_PRINT', true);

//Core code
define('CORE',    ROOT  . 'Core' . DS);
define('REQUEST', CORE . 'request.php');
define('ROUTER',  CORE . 'router.php');
define('ROUTES',  CORE . 'routes.php');

//Database Config
define('DB_CONFIG', array(
    'host'  => 'localhost',
    'user'  => 'root',
    'pass'  => '',
    'db'    => 'project',
    'drive' => 'mysql'
));

//Folders
define('PUBLIC_PATH', getcwd() . DS);
define('LOGS_PATH', ROOT . 'Modules' . DS);
define('LOGS', ROOT . 'Modules' . DS . 'logs.php');
define('LIBS_PATH', ROOT . 'Libs' . DS);
define('CONTROLLER_PATH', ROOT . 'Controller' . DS);
define('MODEL_PATH', ROOT . 'Models' . DS);
define('VIEW_PATH', PUBLIC_PATH . 'Views' . DS);
define('TRAIT_PATH', LIBS_PATH . 'Traits' . DS);
define('INTERFACE_PATH', LIBS_PATH . 'Interfaces' . DS);
define('ERROR_PAGES', VIEW_PATH . 'Errors' . DS);
define('ERROR404', ERROR_PAGES . "404.html");
define('ASSETS_WEB_PATH', DOMAIN . 'Views/Assets/');
define('ASSETS_PATH', VIEW_PATH . 'Assets' . DS);
define('JS_PATH',  'js');
define('CSS_PATH', 'css');
define('ART', ERROR_PAGES . "Art.html");

//Lang
define('LOAD_FILE', true);
define('AUTO_UPDATE', true);
define('LANG_LOAD_FILE', PUBLIC_PATH . 'langs' . DS);

define('LANGS', [
    'ka',
    'en',
    'ru'
]);

//Virtual variables
define('VIRTUAL_VARIABLES_TYPE', [
    'lang' => 1
]);

//Default views
define('DEFAULT_VIEWS', [
    'head'   => VIEW_PATH . 'default' . DS . 'header.php',
    'footer' => VIEW_PATH . 'default' . DS . 'footer.php'
]);

//Assets auto load
define('ASSETS_AUTOLOAD', true);