<?php
require 'vendor/autoload.php';
\$adminCtrl = 'App\Http\Controllers\AdminController';
echo class_exists(\$adminCtrl) ? 'exists' : 'not found';
echo PHP_EOL;
if (class_exists(\$adminCtrl)) {
    \$r = new ReflectionClass(\$adminCtrl);
    echo 'File: ' . \$r->getFileName();
}