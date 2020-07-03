<?php
use Core\router;

router::get('/')->view('index');
router::get('/settings/admin', function () {
    var_dump('welcome to settings.');
});
router::get('/admin/langs', null,true);
router::get('/admin/langs/add', null,true);