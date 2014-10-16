<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//function my_autoloader($class)
//{
//    $filename =  '' . str_replace('\\', '/', $class) . '.php';
//    if(file_exists($filename))
//        include $filename;
//    else{
//        $filename = explode('\\', $class);
//        require end($filename) . '.php';
//    }
//}
spl_autoload_register(function($class)
{
    $filename =  '' . str_replace('\\', '/', $class) . '.php';
    if(file_exists($filename))
        include $filename;
    else{
        $filename = explode('\\', $class);
        require end($filename) . '.php';
    }
});