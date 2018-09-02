<?php
include ('functions.php');
include ('config.php');

foreach(array_diff(scandir(dirname(__FILE__) . '/core/'), array('.', '..')) as $file) {
    require_once(dirname(__FILE__) . '/core/' . $file);
}

global $db;
$db = DB::getInstance();

Route::start(); 