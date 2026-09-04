<?php
// Fresh test - clear any opcache
if (function_exists('opcache_get_status')) {
    @opcache_reset();
}
require 'vendor/autoload.php';
$adminCtrl = 'App\Http\Controllers\AdminController';
echo 'Class exists: ' . (class_exists($adminCtrl) ? 'yes' : 'no') . PHP_EOL;
if (class_exists($adminCtrl)) {
    $r = new ReflectionClass($adminCtrl);
    echo 'File: ' . $r->getFileName() . PHP_EOL;
}