<?php

use core\Handler;

define('DEV', false);
define('DS', DIRECTORY_SEPARATOR);
require_once  '.' . DS . 'core' . DS . 'autoloader.php';
require_once  '.' . DS . 'core' . DS . 'debug.php';

define('SRC', __DIR__ . DS . 'src' . DS);
define('BUILT_COMPONENTS', __DIR__ . DS . 'components' . DS);
define('TEMPLATES', SRC . 'templates' . DS);
define('COMPONENTS', SRC . 'components' . DS);
define('PAGES', SRC . 'pages' . DS);

$handler = new Handler();
$handler->start();
