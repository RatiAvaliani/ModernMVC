<?php

namespace Core;


class request {

    /*
     * List of paths witch autoLoad needs to read.
     */
    public $autoloadList = [
        'controller' => CONTROLLER_PATH,
        'model'      => MODEL_PATH,
        'view'       => VIEW_PATH,
        'interface'  => INTERFACE_PATH,
        'core'       => CORE,
        'traits'     => TRAIT_PATH,
        'libs'       => LIBS_PATH
    ];

    /**
     * request constructor.
     * starting autoLoader.
     * starting session.
     * getting routes.
     */
    function __construct () {
        $this->autoLoad()->startSession()->initDB()->setPut()->setDelete();
        require_once(ROUTES);

        if (router::$loadComplete === false) $this->return404();
    }

    /**
     * if render failed to load content or view, this code will load 404 error;
     */
    public function return404 () {
        require_once (ERROR404);
        exit();
    }

    /**
     *  starting session.
     */
    private function startSession () {
        session_start();
        return $this;
    }

    /**
     * @return $this
     *  set PUT HTTP/HTTPS request as a variable
     */
    private function setPut () {
        $this->addingRequestTypes('_PUT');

        return $this;
    }

    /**
     * @return $this
     *  set DELETE HTTP/HTTPS request as a variable
     */
    private function setDelete () {
        $this->addingRequestTypes('_DELETE');

        return $this;
    }

    /**
     * @param null $requestType
     * @return null
     * This function is used only to add PUT and DELETE request types (and adding new types witch are not in HTTP/HTTPS request needs to stay out)
     */
    protected function addingRequestTypes ($requestType=null) {
        if (is_null($requestType)) return null;

        $$requestType = [];

        if ($_SERVER['REQUEST_METHOD'] === str_replace('_', '', $requestType)) {
            parse_str(file_get_contents('php://input', false, null, -1, $_SERVER['CONTENT_LENGTH']), $$requestType);
        }
    }

    /**
     * @return $this
     *  sets database class for use
     */
    private function initDB () {
        $GLOBALS['db'] = new \Libs\database(DB_CONFIG['drive'], DB_CONFIG['host'], DB_CONFIG['db'], DB_CONFIG['user'], DB_CONFIG['pass']);
        return $this;
    }

    /**
     * @return $this
     * using spl_autoload_register, its getting class/trait/interface names and there namespaces.
     * it is testing if the file exists and requires it.
     */
    public function autoLoad () {
        spl_autoload_register(function ($fileName=null) {
            if (is_null($fileName)) return null;

            foreach ($this->autoloadList as $fileExt => $path) {
                if (strpos(strtolower($fileName), $fileExt) !== false) {
                    if (file_exists($path . preg_replace('/' . ucfirst($fileExt) . '\\\/ ', '', $fileName) . '.' . $fileExt . '.php')) {
                        require_once ($path . preg_replace('/' . ucfirst($fileExt) . '\\\/ ', '', $fileName) . '.' . $fileExt . '.php');
                    } elseif (file_exists($path . str_replace(str_replace(' ', '', ucfirst($fileExt) . '\ '), "" , $fileName) . '.php')) {
                        require_once ($path . str_replace(str_replace(' ', '', ucfirst($fileExt) . '\ '), "" , $fileName) . '.php');
                    } else {
                        if (file_exists(LIBS_PATH . $fileName . '.php')) {
                            require_once(LIBS_PATH . $fileName . '.php');
                        }
                    }
                }
            }

        });
        return $this;
    }
}
