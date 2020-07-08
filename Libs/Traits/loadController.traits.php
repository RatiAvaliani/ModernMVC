<?php
namespace Traits;
use Controller\home;
use Controller\controller;

trait loadController {

    protected static $classInstance;
    protected static $method;

    /**
     * @param string $type
     * @param array $path
     * @return mixed
     * loads controller by path with was in the session.
     * by getting type of we can call controller method automatically, default loading methods are index/indexPost.
     */
    public static function loadController ($type='get', $path=array('home'), $paremeters=array()) {
        $controller = array_key_exists(0, $path) ? $path[0] : self::error('controller name is empty');
        $defaultMethod = $type === 'get' ? 'index' : "index" . ucfirst($type);

        self::$method = array_key_exists(1, $path) ? $path[1] : $defaultMethod;

        self::$method = $type === 'get' ? self::$method : self::$method . ucfirst($type);

        if (empty($path)) return self::error('path is empty for controller');

        $className = '\Controller\\' . str_replace('/', '', $controller);

        \Controller\controller::$method = self::$method;

        if (!class_exists($className)) self::error("controller was called but class dose't exits");

        self::$classInstance = new $className();
        self::$classInstance->passedParameters = $paremeters;
        $passedParameters = $_REQUEST;
        array_shift($passedParameters);

        self::loadMethod ($passedParameters, $paremeters);
        self::loadView(self::$method, $controller);
        exit();
    }

    /**
     *  loads method by default name.
     */
    private static function loadMethod ($paremeters=array()) {
        if (!method_exists(self::$classInstance, self::$method)) self::error("modal called but method dos't exists");
        call_user_func([self::$classInstance, self::$method], $paremeters);
    }

    /**
     * @param null $fileName
     * @param null $folderName
     * Loafing default views
     */
    private static function loadView ($fileName=null, $folderName=null) {
        if (is_null($fileName) || is_null($folderName)) self::error('file name or folder name empty');
        self::render(VIEW_PATH . $folderName . DS . $fileName . '.php');
    }
}