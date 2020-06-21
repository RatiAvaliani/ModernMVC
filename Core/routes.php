<?php
use Core\router;

router::get ('/')->view('index');
router::get('/settings/admin', function () {
    var_dump('welcome to settings.');
});