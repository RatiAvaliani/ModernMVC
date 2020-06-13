<?php

namespace Controller;
use Controller\controller;

class home extends controller {
    public function __construct() {
        parent::__construct();
    }

    public function index () {
        echo '<h1>Welcome</h1>';
    }
}
