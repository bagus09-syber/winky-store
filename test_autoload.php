<?php
require 'vendor/autoload.php';

// Simulate what Laravel does when loading classes
$class = 'App\Http\Controllers\Admin\AdminController';
echo 'Class: ' . $class . PHP_EOL;

// Try class_exists
echo 'class_exists: ' . (class_exists($class) ? 'yes' : 'no') . PHP_EOL;

// Try loading via composer classmap
$classmap = include 'vendor/composer/autoload_classmap.php';
echo 'In classmap: ' . (isset($classmap[$class]) ? 'yes' : 'no') . PHP_EOL;
if (isset($classmap[$class])) {
    echo 'File from classmap: ' . $classmap[$class] . PHP_EOL;
}

// Try the Laravel autoload
$autoload = new Illuminate\Foundation\Autoload();
$file = $autoload->findFile($class);
echo 'Laravel findFile: ' . ($file ?? 'not found') . PHP_EOL;