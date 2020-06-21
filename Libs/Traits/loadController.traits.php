<?php
namespace Traits;
use Controller\home;
use Controller\controller;

trait loadController {
    protected static $classInstance;
    protected static $method;

    /**
     * @param string $type
     * @return mixed
     * loads controller by path with was in the session.
     * by getting type of we can call controller method automatically, default loading methods are index/indexPost.
     */
    public static function loadController ($type='get', $path='/home', $paremeters=array()) {
        $controllerMethod = explode('/', $path);

        if ($controllerMethod[0] === "")  {
            $controllerMethod = array_splice($controllerMethod, 1);
        }

        $controller = array_key_exists(0, $controllerMethod) ? $controllerMethod[0] : self::error('controller name is empty');
        $defaultMethod = $type === 'post' ? 'indexPost' : 'index';

        self::$method = array_key_exists(1, $controllerMethod) ? $controllerMethod[1] : $defaultMethod;

        if (empty($path)) return self::error('path is empty for controller');

        $className = '\Controller\\' . str_replace('/', '', $controller);
        if (!class_exists($className)) self::error("controller was called but class dose't exits");

        self::$classInstance = new $className();
        self::$classInstance->passedParameters = $paremeters;

        self::loadMethod ($paremeters);

        die();
    }

    /**
     *  loads method by default name.
     */
    private static function loadMethod ($paremeters=array()) {
        if (!method_exists(self::$classInstance, self::$method)) self::error("modal called but method dos't exists");
        call_user_func([self::$classInstance, self::$method], $paremeters);
    }
}