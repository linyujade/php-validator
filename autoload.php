<?php

/**
 * 自动加载函数
 * @param string $className 加载对象的类名
 */
spl_autoload_register(function ($className) { 
    require_once __DIR__ . DIRECTORY_SEPARATOR . $className . '.php'; 
});