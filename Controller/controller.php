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
    public static $footerInfo = "";
    public static $method = "index";
    private $modal;
    private $viewLoadPath;
    private $assets = [
        'js' => [
            'https://cdnjs.cloudflare.com/ajax/libs/platform/1.3.5/platform.min.js',
            'Logs/Logs'
        ],
        'css' => []
    ];
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
         $this->loadeListOfAssets();
         $defaultAssets = self::loadDefaultAssets($this->pageName, self::$method);

         self::$headerInfo .= $defaultAssets['head'];
         self::$footerInfo .= $defaultAssets['footer'];

         $this->loadDefaultView();
    }

    /**
     *  this loads all the assets before the auto asses is loaded (loadDefaultAssets)
     */
    private function loadeListOfAssets () {
        foreach ($this->assets['js'] as $file)  self::$footerInfo .= self::loadJsFile($file);

        foreach ($this->assets['css'] as $file) self::$headerInfo .= self::loadCssFile($file);
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