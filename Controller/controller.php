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
    private $viewLoadPath;
    private $assets = [
        'js' => [
            'https://code.jquery.com/jquery-3.5.1.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js',
            'Modules/Modules'
        ],
        'css' => []
    ];
    protected $pageName;

    public $modal;

    /**
     * controller constructor.
     * gets modal and crates a new instance of it, saves the instance in $this->modal.
     */
    public function __construct () {
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
        if ($_SERVER['REQUEST_METHOD'] !== "GET") return; // if the request is't get header, content and footer will not load. (it will assume the request is for api only)

        self::render(DEFAULT_VIEWS['head']);
        self::render($this->viewLoadPath . DS . $this->pageName . '.php');
        self::render(DEFAULT_VIEWS['footer']);
    }
}