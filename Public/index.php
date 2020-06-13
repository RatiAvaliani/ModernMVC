<?php
use Core\router;
use Controller\controller;

/*
 * Loading config file.
 */
require_once('../Config/config.php');

/*
 * Getting ready to get requests
 */
require_once(REQUEST);

/*
 * Getting and reading $_REQUEST
 */
(new \Core\request());