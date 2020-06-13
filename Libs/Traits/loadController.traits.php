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
    public static function loadController ($type='get') {
        $path = self::getSession('path');
        $controllerMethod = explode('/', $path);
        $controller = $controllerMethod[1];
        $defaultMethod = $type === 'post' ? 'indexPost' : 'index';

        self::$method = array_key_exists(2, $controllerMethod) ? $controllerMethod[2] : $defaultMethod;

        if (empty($path)) return self::error('path is empty for controller');

        $className = '\Controller\\' . str_replace('/', '', $controller);
        self::$classInstance = new $className();

        self::loadMethod ();
    }

    /**
     *  loads method by default name.
     */
    private static function loadMethod () {
        if (!method_exists(self::$classInstance, self::$method)) self::error("modal called but method dos't exists");
        call_user_func([self::$classInstance, self::$method]);
    }
}