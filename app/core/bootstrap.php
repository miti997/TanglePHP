<?php

use app\core\App;

define('DEV', false);
define('DS', DIRECTORY_SEPARATOR);
define('SRC', ROOT . 'app' . DS . 'src' . DS);
define('CORE', ROOT . 'app' . DS . 'core' . DS);
define('BUILT_COMPONENTS', ROOT . 'app' . DS . 'components' . DS);
define('TEMPLATES', SRC . 'templates' . DS);
define('COMPONENTS', SRC . 'components' . DS);

require_once CORE . 'autoloader.php';
require_once CORE . 'debug.php';

$handler = new App();
$handler->start();
