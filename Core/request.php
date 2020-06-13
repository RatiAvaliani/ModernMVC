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
        'traits'     => TRAIT_PATH
    ];

    /**
     * request constructor.
     * starting autoLoader.
     * starting session.
     * getting routes.
     */
    function __construct () {
        $this->autoLoad()->startSession();
        require_once(ROUTES);
    }

    /**
     *  starting session.
     */
    private function startSession () {
        session_start();
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
