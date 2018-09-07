<?php
use Application\Application;
require('functions.php');
require('autoload.php');

global $db;

Application::start($db);