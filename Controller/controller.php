<?php

namespace Controller;

use Model;
use Traits\log;
use Traits\render;
use Libs\database;
use Libs\langs;
use Traits\loadAssets;

abstract class controller {
    use log;
    use render;
    use loadAssets;

    public static $headerInfo = "";
    public static $method = "index";
    private   $modal;
    private   $viewLoadPath;
    protected $pageName;

    /**
     * controller constructor.
     * gets modal and crates a new instance of it, saves the instance in $this->modal.
     */
    public function __construct ($method=null) {
        set_exception_handler(array(__CLASS__, 'error'));

        $this->pageName = str_replace('Controller\\', '' , get_called_class());
        $this->modal = 'Model\\' . $this->pageName . 'Model';
        $this->viewLoadPath = VIEW_PATH . $this->pageName;

        if (!class_exists($this->modal)) self::error("modal dos't exists");
         $this->modal = new $this->modal();
         self::$headerInfo = self::loadDefaultAssets($this->pageName, self::$method);
         $this->loadDefaultView();
    }


    /**
     *  loads default view like -> (/modalName/modalName.php)
     */
    protected function loadDefaultView () {
        self::render(DEFAULT_VIEWS['head']);
        self::render($this->viewLoadPath . DS . $this->pageName . '.php');
        self::render(DEFAULT_VIEWS['footer']);
    }
}