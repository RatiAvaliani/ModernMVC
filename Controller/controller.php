<?php

namespace Controller;
use Model;
use Traits\log;
use Traits\render;
use Libs\database;
use Libs\langs;
abstract class controller {
    use log;
    use render;

    public $db;
    private $modal;
    protected $pageName;

    /**
     * controller constructor.
     * gets modal and crates a new instance of it, saves the instance in $this->modal.
     */
    public function __construct () {
        $this->db = new \Libs\database(DB_CONFIG['drive'], DB_CONFIG['host'], DB_CONFIG['db'], DB_CONFIG['user'], DB_CONFIG['pass']);

        $this->pageName = str_replace('Controller\\', '' , get_called_class());
        $this->modal = 'Model\\' . $this->pageName . 'Model';
        if (!class_exists($this->modal)) self::error("modal dos't exists");
         $this->modal = new $this->modal();
         $this->loadDefaultView();
    }

    /**
     *  loads default view like -> (/modalName/modalName.php)
     */
    protected function loadDefaultView () {
        self::render(VIEW_PATH . DS . $this->pageName . DS . $this->pageName . '.php');
    }
}