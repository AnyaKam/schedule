<?php

function __autoload($class)
{
    $parts = explode('\\', $class);
    require_once (dirname(__FILE__) . '\core\\' . end($parts) . '.php');
}
