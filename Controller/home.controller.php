<?php

namespace Controller;
use Controller\controller;

class home extends controller {

    public function __construct() {
        parent::__construct();
    }

    public function index ($paremeters) {
        var_dump('ss');
        echo '<h1>Welcome</h1>';
    }
}
