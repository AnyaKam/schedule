<?php
namespace Application;

use Database\Database;
use Route\Route;

class Application
{
    private function __construct () 
    {         
    }

    public function start (&$db) {
        $db = Database::getInstance();
        Route::start();
    }
}