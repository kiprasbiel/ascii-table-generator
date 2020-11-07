<?php

spl_autoload_register('autoloader');

function autoloader($class){
    $class = str_replace('\\', '/', $class);
    $file = __DIR__ . '/' . "{$class}.php";
    if(file_exists($file)){
        include_once $file;
    }
    else{
        throw new Exception("{$class} nerasta");
    }
}